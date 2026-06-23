<?php
/**
 * Asset loading.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'gojago-starter-main',
			GOJAGO_STARTER_URI . 'assets/css/main.css',
			array(),
			GOJAGO_STARTER_VERSION
		);

		$script_path = GOJAGO_STARTER_PATH . 'assets/js/main.js';
		if ( file_exists( $script_path ) ) {
			wp_enqueue_script(
				'gojago-starter-main',
				GOJAGO_STARTER_URI . 'assets/js/main.js',
				array(),
				filemtime( $script_path ),
				true
			);
		}
	}
);
