<?php
/**
 * ACF readiness.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'acf/settings/save_json',
	function () {
		return GOJAGO_STARTER_PATH . 'acf-json';
	}
);

add_filter(
	'acf/settings/load_json',
	function ( $paths ) {
		$paths[] = GOJAGO_STARTER_PATH . 'acf-json';
		return $paths;
	}
);

add_action(
	'acf/init',
	function () {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title' => __( 'Site Settings', 'gojago-starter' ),
					'menu_title' => __( 'Site Settings', 'gojago-starter' ),
					'menu_slug'  => 'gojago-site-settings',
					'capability' => 'edit_posts',
				)
			);
		}
	}
);
