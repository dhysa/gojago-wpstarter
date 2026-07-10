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

add_filter(
	'render_block_core/navigation',
	function ( $block_content, $block ) {
		$location = isset( $block['attrs']['__unstableLocation'] ) ? sanitize_key( $block['attrs']['__unstableLocation'] ) : '';

		if ( ! $location || ! has_nav_menu( $location ) ) {
			return $block_content;
		}

		$locations = get_registered_nav_menus();
		$label     = isset( $locations[ $location ] ) ? $locations[ $location ] : __( 'Navigation', 'gojago-starter' );
		$classes   = array(
			'wp-block-navigation',
			'is-layout-flex',
			'wp-block-navigation-is-layout-flex',
			'gojago-location-menu',
			'gojago-location-menu--' . $location,
		);

		if ( isset( $block['attrs']['fontSize'] ) ) {
			$classes[] = 'has-' . sanitize_html_class( $block['attrs']['fontSize'] ) . '-font-size';
		}

		$menu = wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'echo'           => false,
				'fallback_cb'    => false,
				'menu_class'     => 'wp-block-navigation__container',
				'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
			)
		);

		if ( ! $menu ) {
			return $block_content;
		}

		return sprintf(
			'<nav class="%1$s" aria-label="%2$s">%3$s</nav>',
			esc_attr( implode( ' ', array_map( 'sanitize_html_class', $classes ) ) ),
			esc_attr( $label ),
			$menu
		);
	},
	10,
	2
);
