<?php
/**
 * Theme bootstrap.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GOJAGO_STARTER_VERSION', '1.0.0' );
define( 'GOJAGO_STARTER_PATH', trailingslashit( get_template_directory() ) );
define( 'GOJAGO_STARTER_URI', trailingslashit( get_template_directory_uri() ) );

$gojago_starter_includes = array(
	'inc/setup/theme-support.php',
	'inc/setup/enqueue.php',
	'inc/setup/menus.php',
	'inc/setup/blocks.php',
	'inc/setup/required-plugins.php',
	'inc/acf/acf.php',
	'inc/seo/seo.php',
	'inc/analytics/analytics.php',
	'inc/gravity-forms/gravity-forms.php',
);

foreach ( $gojago_starter_includes as $gojago_starter_file ) {
	require_once GOJAGO_STARTER_PATH . $gojago_starter_file;
}
