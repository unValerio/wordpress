<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'wordpress');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'root');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'KO ,yWie%z2dt/_Kdo2DDP%RFNDpg <eqYNi3&9e%M6Iz~FHcl.v;ALM1ss)[}G?');
define('SECURE_AUTH_KEY', 'W_D=ITPT!nZHqnZ6wV,=DlTrqzk=$<)oG9oO|vxl~TO(_?^<@)%k%-xd%?-D@!*4');
define('LOGGED_IN_KEY', 'h>e4oudqB+gEbd4[K0`ii]Z*`@G51}6wamAvmK.R{wG<[cFuhcD/&_IuhPPRV2NK');
define('NONCE_KEY', ']#e~S^6za]BqQTe0f-44Erm,LkFj1Ga`j_Kt[Qhik~_7W6?#!Mv%c=@S-gk@H^XO');
define('AUTH_SALT', 'lQm2qqZ&t=>`<41r8bfd~G*]L^oP,fagfIf]F|n [As0~_UXQ =V0F<7FGB}5vE?');
define('SECURE_AUTH_SALT', '<B0dP8BA`wbM0Z}ezq-H9KDq6.bsC>{o9ezu(5-b*?)T?y7D~,}?5!/q7z>7)>|?');
define('LOGGED_IN_SALT', '=5|7@x.Ac5v^#A_MWI?Q}XDW0`p`h20VPEv{%?8v~AK7y)/gD%m:&jmr-h,K~B*y');
define('NONCE_SALT', 'k=KJn YhQan?c3h-q!ExI5C8d !5YfE5`H.|=!#{vRk)-^-Kq8=><v^9?~n44uDV');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

