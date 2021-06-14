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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'clearvie_wp' );

/** MySQL database username */
define( 'DB_USER', 'clearvie_wp' );

/** MySQL database password */
define( 'DB_PASSWORD', 'wu3y6GR4wVKI' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define( 'WP_MEMORY_LIMIT', '256M' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'C=4*wgW4j7AM*@;zB7JCI|1@[p/`];CKS8,_*}T)Hyo<@XP#_UzCqS0<Ig@ZM,AY' );
define( 'SECURE_AUTH_KEY',   'd5)iC8Mi6ZSX#e`)vE)%$sN-4}F2bO4.8I8:^(C225u,ido4:>53@hUoa6_C]*,o' );
define( 'LOGGED_IN_KEY',     ':G)(;DNhn#A5`XN8Vfl&@k|E*|^)J! -U3`@vB@/r34=a,?Ja(BBG)ByV)fcxFs8' );
define( 'NONCE_KEY',         'j)LleeK|{^wNW5<YsNI&<c,N=^q:;eQa;C!eVLrqPHo1w)K(B(=^LaNXzZFcTa(#' );
define( 'AUTH_SALT',         '8](^?U!%T6WX}]bBv<=q>TU(S`0k9^DzZviGU8OM|.,z(#~e/ld!DAy,ogBrxeJ<' );
define( 'SECURE_AUTH_SALT',  '#hGafYjX.,M(~CaXS}[1|]87B-[.mRZQmqqeg|{D1uF6gA;Vf8+i=QWy- bV_m:@' );
define( 'LOGGED_IN_SALT',    '7Z(Gu7t)5!9cQ&1r_m$~]Ck]ju[4qH>ONwWZ@I8Bih=ztH%6PMcV5.z$Cs^AuDud' );
define( 'NONCE_SALT',        '8mhxakT j;7w#({W8^x]>cGRQ%7|+:B QW-W4CU+<Q$5jZX_H^0/R|Ieis/5qaSI' );
define( 'WP_CACHE_KEY_SALT', '.t 6g3RRRlN@_|mxzMY!V~.Jb{EG0ThLl Il?QOfwU1)&hH7tU`lEBy5A|Da^Y]s' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
