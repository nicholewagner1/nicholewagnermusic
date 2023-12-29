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
define( 'DB_NAME', 'dbgyevd4ragang' );

/** MySQL database username */
define( 'DB_USER', 'uv5emqrw7d9qj' );

/** MySQL database password */
define( 'DB_PASSWORD', '2e7a5ryqk739' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '[y0-GgYen|&58)*zo~KGw|48FFh0.S2#0:{[gOwP@u.^ZE,2HRwwN8tWVqZ6$pSw' );
define( 'SECURE_AUTH_KEY',   'ZTx0+Gs!hnkBt)>,Z lWz7G<~QNpI]P=H1Q37$[,&9t<H7D3YscpgM#rm@2{DI@L' );
define( 'LOGGED_IN_KEY',     'P8V)Iujp5-=^dh9RKd*2RhPF|wkdeV<PoI)f(3iJ>-6 Z47}Y`b.d__nm%J-3~_M' );
define( 'NONCE_KEY',         'xv5[3s?T4VEMQGqm*w1+W07ao,0W~H7(W <i}M88X[4GS>B*|w[;<[H^:eS@wbU,' );
define( 'AUTH_SALT',         '0Ek`DL3>A2E<B)FR^WJfq0rb-6&wSaSN7z{h]y)MdT4},7h`IC#$4x2H?_fO7d?0' );
define( 'SECURE_AUTH_SALT',  ']$u|X8r8a`tHE=u 6ibpSiQ2{u_(U+r.LeXZl&YCxzhQz4~fIp]tVv1NvyT *Fq!' );
define( 'LOGGED_IN_SALT',    '!]?d8gum;b|n[15h5f5<N@yd-{a]NrdFX+MZNjUnZ^@p:RRE}<x*D;igvv3*ICD9' );
define( 'NONCE_SALT',        '$ ]?/pk&c4Y5HhQW4m0SLk bh.W%s}_8[as8N:g>CP$%eJw;wBpP^J#cLUO;u?u1' );
define( 'WP_CACHE_KEY_SALT', 'cp-D/g<:d[v%Nz7LjK.ow}7FXcPY=Jcpl6M>1g1v}8W;^D?%&},uE[FB@kWJD<^,' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
