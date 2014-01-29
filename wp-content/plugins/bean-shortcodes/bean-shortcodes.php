<?php

/*
Plugin Name: Bean Shortcodes
Plugin URI: http://themebeans.com/plugin/bean-shortcodes-plugin/?ref=plugin_bean_shortcodes ‎
Description: Enables shortcodes to be used in Bean WordPress Themes
Version: 1.3.1
Author: ThemeBeans
Author URI: http://www.themebeans.com/?ref=plugin_bean_shortcodes
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

if ( ! class_exists( 'Bean_BeanShortcodes' ) ) :

class Bean_BeanShortcodes {

    function __construct() {
    	require_once( DIRNAME(__FILE__) . '/bean-theme-shortcodes.php' );

    	define('BEAN_TINYMCE_URI', plugin_dir_url(__FILE__) .'tinymce');
		define('BEAN_TINYMCE_DIR', DIRNAME(__FILE__) .'/tinymce');

        add_action('init', array(&$this, 'action_admin_init'));
        add_action('admin_enqueue_scripts', array(&$this, 'action_admin_scripts_init'));
        add_action('wp_enqueue_scripts', array(&$this, 'action_frontend_scripts'));
	}

	/**
	 * Registers Scripts
	 *
	 * @return	void
	 */

	function action_frontend_scripts() {

		/**
		* Frontend shortcodes style depending on the current theme
		* Fallback to default if the style for current theme is not found
		*/

		// GET CSS PATH
		$current_theme = wp_get_theme();

		$theme_css_path = DIRNAME(__FILE__) . '/themes/' . $current_theme . '/bean-shortcodes.css';
		$theme_css_url = plugin_dir_url(__FILE__) . 'themes/' . $current_theme . '/bean-shortcodes.css';
		$default_css_url = plugin_dir_url(__FILE__) . 'themes/_default/bean-shortcodes.css';
		$default_js_url = plugin_dir_url(__FILE__) . 'js/bean-shortcodes.js';
		$default_prettify_url = plugin_dir_url(__FILE__) . 'js/prettify.js';
	
		wp_enqueue_script('jquery-ui-accordion' );
		wp_enqueue_script('jquery-ui-tabs' );
		wp_enqueue_script('bean-shortcodes', $default_js_url, 'jquery', '1.0', true);
		wp_enqueue_script('prettify', $default_prettify_url, 'jquery', '1.0', true);
		
		// FIX SPACES THAT MAY EXIST IN THEME NAME
		$theme_css_url = str_replace(' ', '%20', $theme_css_url);
	
		if (file_exists($theme_css_path))
			wp_enqueue_style( 'bean-shortcodes-style', $theme_css_url, false, '1.0', 'all' );
		else
			wp_enqueue_style( 'bean-shortcodes-style', $default_css_url, false, '1.0', 'all' );
	}

	/**
	 * Enqueue Scripts and Styles
	 *
	 * @return	void
	 */
	 
	function action_admin_scripts_init() {

		// css
		wp_enqueue_style( 'bean-popup', BEAN_TINYMCE_URI . '/css/popup.css', false, '1.0', 'all' );

		// js
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-livequery', BEAN_TINYMCE_URI . '/js/jquery.livequery.js', false, '1.1.1', false );
		wp_enqueue_script( 'jquery-appendo', BEAN_TINYMCE_URI . '/js/jquery.appendo.js', false, '1.0', false );
		wp_enqueue_script( 'base64', BEAN_TINYMCE_URI . '/js/base64.js', false, '1.0', false );
		wp_enqueue_script( 'bean-popup', BEAN_TINYMCE_URI . '/js/popup.js', false, '1.0', false );
		wp_localize_script( 'jquery', 'BeanShortcodes', array('plugin_folder' => plugin_dir_url(__FILE__)) );
	}

	/**
	 * REGISTERS TINYMCE RICH EDITOR BUTTONS
	 *
	 * @return void
	 */
	function action_admin_init() {

		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;

		if ( get_user_option('rich_editing') == 'true' && is_admin() ) {
			add_filter( 'mce_external_plugins', array(&$this, 'add_rich_plugins') );
			add_filter( 'mce_buttons', array(&$this, 'register_rich_buttons') );
		}
	}

	// --------------------------------------------------------------------------

	/**
	 * Defines TinyMCE rich editor js plugin
	 *
	 * @return	void
	 */
	function add_rich_plugins( $plugin_array ) {

		$plugin_array['BeanShortcodes'] = BEAN_TINYMCE_URI . '/plugin.js';

		return $plugin_array;
	}

	// --------------------------------------------------------------------------

	/**
	 * Adds TinyMCE rich editor buttons
	 *
	 * @return	void
	 */
	function register_rich_buttons( $buttons ) {

		array_push( $buttons, "|", 'bean_button' );

		return $buttons;
	}

}

new Bean_BeanShortcodes;

endif;

?>