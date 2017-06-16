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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'IcYksUb0oC');

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
define('AUTH_KEY',         'Mvx]{7r-=#7[flW5X=)<jQ8LOt6wm/%X?w+.xZ^jj%V(Sc5ba?!mqTPH.:jyhF%-');
define('SECURE_AUTH_KEY',  'LL,}~Yu[!JzI3#`G}S[iZ]w0=u+:Vtf9JFtg;-|]gUlMPXmxXal@U~X7*~LtLu8b');
define('LOGGED_IN_KEY',    'mZURCELP=CS_68$tCc9cN0[gR)CP2.RaDCB-!?[#3`%(kC6A>4Lxe~&~cLLPnYX)');
define('NONCE_KEY',        'r=,%Z:H~~Bez&5VQjXgTHM,CeWqkZLW3ze|B90i$tZn3lC*aTXqg}s.(DDyRIY&N');
define('AUTH_SALT',        '9q+<Bd>.:r$!XQ2%I1fw.WvOI7ox=Uq89hRD5`l((-eWUyC+uE~<za++$Ph=0;q2');
define('SECURE_AUTH_SALT', 'z.6rPNOPKP|Ff{4d0=:| dMYSxsHkkIA8D3~`=.@YXMGE}kG4vy7(Oq/j*5I2/gD');
define('LOGGED_IN_SALT',   'Ms,zL=!JNKc3!=D}$,UkEY:<:Olz-x]u-@/PNY[2(xbuQ^%7{Sw=(ENjs]+/U4xc');
define('NONCE_SALT',       'N:sEl=gC}#a]xq`@!pawB$;FGdMq-DJw4hz@c{x6M5:N*RG}j!?W3I?^gU`z=3w;');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
