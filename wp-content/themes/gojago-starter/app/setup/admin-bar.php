<?php
/**
 * Admin bar cleanup.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'admin_bar_menu',
	function ( $wp_admin_bar ) {
		if ( ! is_admin_bar_showing() || ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$wp_admin_bar->remove_node( 'site-editor' );
		$wp_admin_bar->remove_node( 'customize' );

		$wp_admin_bar->add_node(
			array(
				'id'    => 'gojago-customize-css',
				'title' => __( 'Customize CSS', 'gojago-starter' ),
				'href'  => admin_url( 'customize.php?autofocus[section]=custom_css' ),
				'meta'  => array(
					'class' => 'gojago-customize-css',
				),
			)
		);
	},
	100
);

add_action(
	'admin_menu',
	function () {
		global $submenu;

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$submenu['themes.php'][] = array(
			__( 'Customize CSS', 'gojago-starter' ),
			'edit_theme_options',
			admin_url( 'customize.php?autofocus[section]=custom_css' ),
		);
	},
	20
);
