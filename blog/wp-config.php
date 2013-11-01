<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wrd_m4cefcenfe');

/** MySQL database username */
define('DB_USER', 'wrdYYxzek5Z');

/** MySQL database password */
define('DB_PASSWORD', 'FZTzIyPrHH');

/** MySQL hostname */
define('DB_HOST', 'institutoreko.fatcowmysql.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'JUWip4CroXJsn5JyUafHXuzdLhzX6mQKPdP5OG0klOqTQJ3XY89xRL9GJISHQIwp');
define('SECURE_AUTH_KEY',  'JKUu0qU2EdCruMMO7lqw3EILclL9DUf0mucpG7JaVIJdFQKWjaCUh0DRwuquxzQk');
define('LOGGED_IN_KEY',    'pY8VFlGkwvtv9YiOpLrRTDMMJI3RTfO42MPB6VnkwV5kFCX1dWzwO1ukmld6NQG1');
define('NONCE_KEY',        'I49fSjlebg2yDlUuRp1B15tUHopiT6EZAix0dbYQmieQFS5RPJvFAz39upoWVnwd');
define('AUTH_SALT',        'c7W6VRghI5772EhEayTeosB07VNjm6DFhYAG3thlp09tMhZaupAN0aSIIUXAawRV');
define('SECURE_AUTH_SALT', 'AzEOuXGkAYN2Et1a5gR9kH2t9Uc9a8XxyWuLDX8flTCzD7UJchA0GFi5fCJLUczF');
define('LOGGED_IN_SALT',   '071tXcvmjS5Xbl5gostYffoxsUjb35ecOOUxqT0E2n2ZxsG7raEsrNfScSXZAr1L');
define('NONCE_SALT',       '9nLanFG2wM6UNWi7SAnmQ3U66zJaA4koL2IH7HCOUMaWcEeX8i5xguddBAC87J7A');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
