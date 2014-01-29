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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'W==&k=fl2 ^ZLg,>#|eQx`gO.ODAE+e;,M3Ze*g8;XR|U8UWssiAE|+sljn)Y[VI');
define('SECURE_AUTH_KEY',  'YrUfqz,I#*nB:0!w&hHsD9^aK9Wf$]u@=P8|lM8g4d!>,-DR.c+tP75Vqvm|js<7');
define('LOGGED_IN_KEY',    'U%Lfq:i^R)+OI]#n;ITY5;uU+/6hm8ye1nJQN_V`Ss@@!z]ruKAdxZOh ) 2?Rf2');
define('NONCE_KEY',        'K Y3HyHE%-+9~~yuRIzdt}{-VI VomlE*!Vjs>n|iO?9+`yq,;#_-lFV}zp$-!<x');
define('AUTH_SALT',        'P1snAqJ4J=@IpbM9ti!OxEcK^|BO#VN%[)lJ-xW)wNx+,f;6||8g-+&ga4E:87On');
define('SECURE_AUTH_SALT', '&#4$u]v39X)N#{ZoFPTEVLbIy~d>jv!=VHK|_tMYA&W|B_CbW71iS1VVM?-d8c(}');
define('LOGGED_IN_SALT',   '+-$#p|hrHf&6p`Y#3KRsZ7D{WqZq0+Ww=F#5{q1|^a2Ju}jskSnN+chrgH>U#f=+');
define('NONCE_SALT',       'KKpWMV&2zaL+>, hxrON*1{,CGYhu@=[9Sjd^JBX,5{T:d-bZti76=7l%SJgx,vs');

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
