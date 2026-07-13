<?php
/**
 * Theme bootstrap.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GOJAGO_STARTER_VERSION', '1.1.2' );
define( 'GOJAGO_STARTER_PATH', trailingslashit( get_template_directory() ) );
define( 'GOJAGO_STARTER_URI', trailingslashit( get_template_directory_uri() ) );

$gojago_starter_includes = array(
	'app/setup/theme-support.php',
	'app/setup/enqueue.php',
	'app/setup/menus.php',
	'app/setup/blocks.php',
	'app/setup/views.php',
	'app/setup/admin-bar.php',
	'app/setup/required-plugins.php',
	'app/acf/acf.php',
	'app/seo/seo.php',
	'app/analytics/analytics.php',
	'app/security/login-hardening.php',
	'app/gravity-forms/gravity-forms.php',
);

foreach ( $gojago_starter_includes as $gojago_starter_file ) {
	require_once GOJAGO_STARTER_PATH . $gojago_starter_file;
}
