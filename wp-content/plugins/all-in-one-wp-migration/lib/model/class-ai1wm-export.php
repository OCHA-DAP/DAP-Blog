<?php
/**
 * Copyright (C) 2013 ServMask LLC
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Ai1wm_Export
{
	const EXPORT_ARCHIVE_NAME  = 'dump';
	const EXPORT_DATABASE_NAME = 'database.sql';
	const EXPORT_PACKAGE_NAME  = 'package.json';
	const EXPORT_MEDIA_NAME    = 'media';
	const EXPORT_PLUGINS_NAME  = 'plugins';
	const EXPORT_THEMES_NAME   = 'themes';
	const EXPORT_LAST_OPTIONS  = 'ai1wm_export_last_options';

	protected $connection = null;

	public function __construct() {
		$this->connection = MysqlDumpFactory::makeMysqlDump(
			DB_HOST,
			DB_USER,
			DB_PASSWORD,
			DB_NAME,
			(
				class_exists(
					'PDO'
				) && in_array( 'mysql', PDO::getAvailableDrivers() )
			)
		);
	}

	/**
	 * Export archive file (database, media, package.json)
	 *
	 * @param  resource $output_file Pointer to file resource
	 * @param  array    $options     Export settings
	 * @return string                Absolute file path
	 */
	public function export( $output_file, array $options = array() ) {
		global $wp_version;
		$options['plugin_version'] = AI1WM_VERSION;
		$options['wp_version']     = $wp_version;
		$options['php_version']    = phpversion();
		$options['ZipArchive']     = class_exists( 'ZipArchive' ) ? 1 : 0;
		$options['ZLIB_installed'] = function_exists( 'gzopen' ) ? 1 : 0;
		$options['PDO_available']  = class_exists( 'PDO' ) ? 1 : 0;
		$options['home_url']       = home_url();

		// Export last options
		update_option( self::EXPORT_LAST_OPTIONS, $options );

		// Make archive
		$archive = ZipFactory::makeZipArchiver( $output_file, ! class_exists( 'ZipArchive' ) );

		// Should we export database?
		if ( ! isset( $options['export-database' ] ) ) {
			$database_file = tmpfile();
			$database_file = $this->prepare_database( $database_file, $options );
			$archive->addFile(
				$database_file,
				self::EXPORT_DATABASE_NAME
			);
		}

		// Should we export media?
		if ( ! isset( $options['export-media'] ) ) {
			$archive->addDir(
				$this->prepare_media( $options ),
				self::EXPORT_MEDIA_NAME
			);
		}

		// Should we export themes?
		if ( ! isset( $options['export-themes'] ) ) {
			$archive->addDir(
				$this->prepare_themes( $options ),
				self::EXPORT_THEMES_NAME
			);
		}

		// Should we export plugins?
		if ( ! isset( $options['export-plugins'] ) ) {
			$archive->addDir(
				$this->prepare_plugins( $options ),
				self::EXPORT_PLUGINS_NAME
			);
		}

		// Add package
		$archive->addFromString(
			self::EXPORT_PACKAGE_NAME,
			$this->prepare_package( $options )
		);

		return $archive->getArchive();
	}

	/**
	 * Export database in SQL format
	 *
	 * @param  resource $output_file Pointer to file resource
	 * @param  array    $options     Export settings
	 * @return string                Absolute file path
	 */
	public function prepare_database( $output_file, array $options = array() ) {
		global $wpdb;

		$_f = new Ai1wm_File();

		// Set include tables
		$includeTables = array();
		if ( isset( $options['include-tables'] ) ) {
			$includeTables = $options['include-tables'];
		}

		// Set exclude tables
		$excludeTables = array();
		if ( isset( $options['exclude-tables' ] ) ) {
			$excludeTables = $options['exclude-tables'];
		}

		// Set no table data
		$noTableData = false;
		if ( isset( $options['no-table-data'] ) ) {
			$noTableData = true;
		}

		$clauses = array();

		// Spam comments
		if ( isset( $options['export-spam-comments'] ) ) {
			$clauses[ $wpdb->comments ]    = ' WHERE comment_approved != "spam" ORDER BY comment_ID ';
			$clauses[ $wpdb->commentmeta ] = sprintf(
				' INNER JOIN `%1$s`
				ON `%1$s`.comment_ID = `%2$s`.comment_id AND `%1$s`.comment_approved != \'spam\'
				ORDER BY `%2$s`.meta_id ', $wpdb->comments, $wpdb->commentmeta
			);
		}

		// Post revisions
		if ( isset( $options['export-revisions'] ) ) {
			$clauses[ $wpdb->posts ] = ' WHERE post_type != "revision" ORDER BY ID ';
		}

		// No table data, but leave Administrator account unchanged
		if ( isset( $options['export-table-data'] ) ) {
			$clauses[ $wpdb->users ]    = ' WHERE id = 1 ';
			$clauses[ $wpdb->usermeta ] = ' WHERE user_id = 1 ';
		}

		$output_meta = stream_get_meta_data( $output_file );

		// Set dump options
		$this->connection
			->setFileName( $output_meta['uri'] )
			->setIncludeTables( $includeTables )
			->setExcludeTables( $excludeTables )
			->setNoTableData( $noTableData )
			->setOldTablePrefix( $wpdb->prefix )
			->setNewTablePrefix( AI1WM_TABLE_PREFIX )
			->setQueryClauses( $clauses );

		// Make dump
		$this->connection->dump();

		// Replace Old/New Values
		if (
			isset( $options['replace'] ) &&
			( $replace = $options['replace'] )
		) {
			$old_values = array();
			$new_values = array();
			for ( $i = 0; $i < count( $replace['old-value'] ); $i++ ) {
				if (
					! empty( $replace['old-value'][$i] ) &&
					! empty( $replace['new-value'][$i] ) &&
					$replace['old-value'][$i] != $replace['new-value'][$i]
				) {
					$old_values[] = $replace['old-value'][$i];
					$new_values[] = $replace['new-value'][$i];
				}
			}
			// Do String Replacement
			if ( $old_values && $new_values ) {
				$output_file = $_f->str_replace_file(
					$output_file,
					$old_values,
					$new_values
				);
			}
		}

		return $_f->preg_replace_file(
			$output_file,
			'/s:(\d+):([\\\\]?"[\\\\]?"|[\\\\]?"((.*?)[^\\\\])[\\\\]?");/'
		);
	}

	/**
	 * Unescape to avoid dump-text issues
	 *
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public static function unescape_mysql( $value ) {
		return str_replace(
			array( '\\\\', '\\0', "\\n", "\\r", '\Z', "\'", '\"', ),
			array( '\\', '\0', "\n", "\r", "\x1a", "'", '"', ),
			$value
		);
	}

	/**
	 * Fix strange behaviour if you have escaped quotes in your replacement
	 *
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public static function unescape_quotes( $value ) {
		return str_replace( '\"', '"', $value );
	}

	/**
	 * Export media library base directory
	 *
	 * @param  array  $options Export settings
	 * @return string          Media base directory
	 */
	public function prepare_media( array $options = array() ) {
		if ( ! isset( $options['export-media'] ) ) {
			$upload_dir = wp_upload_dir();

			return $upload_dir['basedir'];
		}
	}

	/**
	 * Export themes root directory
	 *
	 * @param  array  $options Export settings
	 * @return string          Themes root directory
	 */
	public function prepare_themes( array $options = array() ) {
		if ( ! isset( $options['export-themes'] ) ) {
			$themes_dir = get_theme_root();

			return $themes_dir;
		}
	}

	/**
	 * Export plugins root directory
	 *
	 * @param  array  $options Export settings
	 * @return string          Plugins root directory
	 */
	public function prepare_plugins( array $options = array() ) {
		if ( ! isset( $options['export-plugins'] ) ) {

			return WP_PLUGIN_DIR;
		}
	}

	/**
	 * Export package json file which includes information about installed plugins and etc.
	 *
	 * @param  array  $options Export settings
	 * @return string          Package config
	 */
	public function prepare_package( array $options = array() ) {
		$config = array();

		$config['Plugins'] = array();
		if ( ! isset( $options['export-plugins' ] ) ) {
			if ( isset( $options['include-plugins'] ) && ( $include_plugins = $options['include-plugins'] ) ) {
				foreach ( $include_plugins as $key => $plugin_name ) {
					$slug = current( explode( DIRECTORY_SEPARATOR, $key ) );

					$config['Plugins'][] = array(
						'Name' => $plugin_name,
						'Slug' => $slug,
					);
				}
			}
		}

		return json_encode( $config );
	}
}
