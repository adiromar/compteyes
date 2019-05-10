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
define('DB_NAME', 'comptoneye');

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
define('AUTH_KEY',         'WQc-.=q~PeEOi<Mm`oas*E#;T&OSZQAkoM%kYT],f=f8N$8!ib5+Yql0%7<h&@B`');
define('SECURE_AUTH_KEY',  ')^BYgJ2iH*Zfg`5BdY(sbr(-ZGqcZB<LhVsw4@~cZ}+pT:@ 8DV%4,Kxv2$4!*J^');
define('LOGGED_IN_KEY',    'EQ&EEi2@;N(%ETU{WsNWC_izN%eBmMW./l@l^-)T=BXq~Lftdf~`(_%tRG}(xdhk');
define('NONCE_KEY',        '#Zb?##M){&HNBQ*[0IqcLl(sa[p-oz(n21!Wf?$Nh4HgC^G;^vgm5^x-E6HJEN{J');
define('AUTH_SALT',        'Rc8J,%)LUKqOW}aiXF@}iGJ Y`ms3AQN^OB)iEjbrs/^[I$4#K)t]^Z?(iku9}P<');
define('SECURE_AUTH_SALT', 'S(ceWBa=Ccfin5`BzlIep Yoi(h&0X0T2jBCWU5MH>^SQ<<rGKZS=-fMSC>Oi[{X');
define('LOGGED_IN_SALT',   'zadYW15Xjz$UTW/8G={gNs{x+Pg.!p-NyglwmDx!F~W$;|X<w=t$@IWlCH4?rkPV');
define('NONCE_SALT',       'Ld}z-E6?IGGAO,B|Wu>1ZSSlKMebH;l9t2|<6=NM9oeh*SI|D2$ZTVx/mO?_|ym/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ce_';

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
