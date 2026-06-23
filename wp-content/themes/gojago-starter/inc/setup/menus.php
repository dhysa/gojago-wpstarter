<?php
/**
 * Menu registration.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'gojago-starter' ),
				'footer'  => __( 'Footer Menu', 'gojago-starter' ),
			)
		);
	}
);
