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
	},
	100
);
