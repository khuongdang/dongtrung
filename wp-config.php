<?php
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '256M' );


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
define('DB_NAME', 'dongt542_trsinh');

/** MySQL database username */
define('DB_USER', 'dongt542_trsinh');

/** MySQL database password */
define('DB_PASSWORD', 'trsinh987456321');

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
define('AUTH_KEY',         'ehvenuq5wbog1uvaksxeypzv3mrlqj3rybhyucjxr28kekysapvogydspz8z02i8');
define('SECURE_AUTH_KEY',  '5wckhmefm9gnsntadcv0wuxnxyyzrzamshtsiuzvfhpmlmihvnrmo8otq8xp8eli');
define('LOGGED_IN_KEY',    'cbd1xtiekb3t06oeedmfitsybymoennodeucmjhb8qelbjxfm1bkgjqqftvvi9z4');
define('NONCE_KEY',        'iupfou8znnpr1sd75hr9hivw1h5ufkzhdjqmbf4venqebyltfk60rpjlayuavm7h');
define('AUTH_SALT',        'm7wsxximbhxvnpivwwu5ldid3vldcf6gxrp4cxow9faj5al7pqy75ta1zkdxr6hu');
define('SECURE_AUTH_SALT', 'jqgme7nzsjepejzvvyhanqq3k6gh4i7ihs5gxlfki310iy1pa6v7esbxymep2uvt');
define('LOGGED_IN_SALT',   'c2pu5w5yzetwus1acxduyrls2wcf1dneccbpylttv77uvvlvlxoke4m7n2et8q75');
define('NONCE_SALT',       'cvtfx2cr7g8qqntje2viz3sqjrrhxgjant3vehvyuautjp63ls5mnyo5dyqaa9ve');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'trgsih_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
