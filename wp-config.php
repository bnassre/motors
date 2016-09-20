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
define('DB_NAME', 'motors');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'biVmgrM@V*2n#*y0]#/-nZ,(n^nVU,-p*wuN^J;z+C6J5y~!lPxDKDdB(esau-6p');
define('SECURE_AUTH_KEY',  ')~|e$Am_[~5P%m 4:$/ypt4[FVVizTk}i}ZLNWq&Q3y*h1`tl_2:MR+aK^p78#[`');
define('LOGGED_IN_KEY',    'wM:4t?3andD]bxikQ7CH$t-$eJJrb}LH,S7nhJ-6;>.CQYD39-|JGS[s8eStZTn8');
define('NONCE_KEY',        'cL]$9[n_P^IJkOi[5At(xT[|cXVgn}H+n+K>x]!L{L<-#B/L@:71;%Ws}Nu&#*`f');
define('AUTH_SALT',        '}t}Hw&jDfNZBEpO5RLMRAa7].wq&xQdyAMU|GN58h~#TiiAbw d|?o@v!iunvRM^');
define('SECURE_AUTH_SALT', 'dAo&Mq+DAg1P<Sv0n=odcp7z-0H?(Dmj$TT6Ta9t6D+S:mUj27LFinh}61ql=X})');
define('LOGGED_IN_SALT',   'Z1v-]9.a$EC@xYw%eUv_xl80RUKW`?-%9+Ooj2cuxN]+l#l[6F16f&pabMNqf<n4');
define('NONCE_SALT',       '@%@9S<KP{HW 0NOPoQF[0Mp42l1bHlsuHjU3Oh`WD7(BQLj99%c!SbT?]K8?>m]L');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
