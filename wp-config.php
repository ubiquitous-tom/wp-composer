<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $_SERVER['DB_NAME']);

/** MySQL database username */
define('DB_USER', $_SERVER['DB_USER']);

/** MySQL database password */
define('DB_PASSWORD', $_SERVER['DB_PASSWORD']);

/** MySQL hostname */
define('DB_HOST', $_SERVER['DB_HOST']);

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
define('AUTH_KEY',         '522c54280cf2bd153a9e718ddbdc712a83da9358');
define('SECURE_AUTH_KEY',  '7a43e6ca236a0d2ffb664a3e3b64e5d49c873ecf');
define('LOGGED_IN_KEY',    '41eef1d6c5c853be1ccf71c495358cf034bef42d');
define('NONCE_KEY',        'b9e2988ccdf26d9208fac3ce26b8fe40445eb4c0');
define('AUTH_SALT',        '87ff972a022518bdf62065c6f2144ad492ecdbe8');
define('SECURE_AUTH_SALT', '5b77dbad08151e15249dfaba7c27d0699ffcf35e');
define('LOGGED_IN_SALT',   '5272939977c05535cb4659b72293f44f4c3d2247');
define('NONCE_SALT',       'a260ed86b171b22f86359d86884c90738c2d6f3d');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */

 // Set to true or false in env.
if ( isset( $_SERVER['WP_DEBUG'] ) ) {
	$isDebugEnabled = filter_var( $_SERVER['WP_DEBUG'], FILTER_VALIDATE_BOOLEAN );
	define(' WP_DEBUG', $isDebugEnabled );
	define( 'WP_DEBUG_LOG', $isDebugEnabled );
	define( 'WP_DEBUG_DISPLAY', $isDebugEnabled );
	define( 'SCRIPT_DEBUG', $isDebugEnabled );
	
	// For query-monitor plugin.
	define( 'WP_LOCAL_DEV', $isDebugEnabled );
	
	// For jetpack plugin.
	define( 'JETPACK_DEV_DEBUG', $isDebugEnabled );
}


define('WP_ALLOW_MULTISITE', true);
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', $_SERVER['DOMAIN_CURRENT_SITE']);
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);




// if (isset($_SERVER['WP_LOCAL_DEV'])) {
//	define( 'WP_LOCAL_DEV', $_SERVER['WP_LOCAL_DEV'] );
// }

define( 'WP_REDIS_HOST', $_SERVER['WP_REDIS_HOST'] );
define( 'WP_REDIS_PORT', $_SERVER['WP_REDIS_PORT'] );
define( 'WP_REDIS_CLIENT', 'pecl' );


define( 'AWS_ACCESS_KEY_ID', $_SERVER['AWS_ACCESS_KEY_ID'] );
define( 'AWS_SECRET_ACCESS_KEY', $_SERVER['AWS_SECRET_KEY'] );

define( 'GLOBAL_SMTP_HOST', $_SERVER['GLOBAL_SMTP_HOST'] );
define( 'GLOBAL_SMTP_USER', $_SERVER['GLOBAL_SMTP_USER'] );
define( 'GLOBAL_SMTP_PASSWORD', $_SERVER['GLOBAL_SMTP_PASSWORD'] );
define( 'GLOBAL_SMTP_PORT', $_SERVER['GLOBAL_SMTP_PORT'] );

// If we're behind a proxy server and using HTTPS, we need to alert Wordpress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
}

// THESE ARE SET WITHIN WORDPRESS THEME SETTINGS
// define( 'ENVIRONMENT', $_SERVER['ENVIRONMENT'] );
// define('RLJE_BASE_URL', $_SERVER['RLJE_BASE_URL'] );
// define('CONTENT_BASE_URL', $_SERVER['CONTENT_BASE_URL'] );
// define('SAILTHRU_CUSTOMER_ID', $_SERVER['SAILTHRU_CUSTOMER_ID'] );


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
