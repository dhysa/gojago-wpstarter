<?php
/**
 * Theme support and starter content.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'gojago-starter', GOJAGO_STARTER_PATH . 'languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ) );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
		);

		add_editor_style( 'assets/css/main.css' );
	}
);

add_action(
	'after_switch_theme',
	function () {
		$pages = array(
			'Home'     => gojago_starter_home_content(),
			'About'    => '<!-- wp:heading --><h2>About Gojago Starter</h2><!-- /wp:heading --><!-- wp:paragraph --><p>This page is ready for client-specific story, proof, and team content.</p><!-- /wp:paragraph -->',
			'Services' => '<!-- wp:heading --><h2>Services</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Add service descriptions, pricing notes, or project workflows here.</p><!-- /wp:paragraph -->',
			'Contact'  => '<!-- wp:heading --><h2>Contact</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Add contact details or a Gravity Forms embed after installing Gravity Forms Pro.</p><!-- /wp:paragraph -->',
		);

		$page_ids = array();
		foreach ( $pages as $title => $content ) {
			$page = get_page_by_title( $title );
			if ( ! $page ) {
				$page_id = wp_insert_post(
					array(
						'post_title'   => $title,
						'post_name'    => sanitize_title( $title ),
						'post_status'  => 'publish',
						'post_type'    => 'page',
						'post_content' => $content,
					)
				);
			} else {
				$page_id = $page->ID;
			}

			if ( ! is_wp_error( $page_id ) ) {
				$page_ids[ $title ] = (int) $page_id;
			}
		}

		if ( isset( $page_ids['Home'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $page_ids['Home'] );
		}

		gojago_starter_seed_classic_menu( 'Primary Menu', 'primary', array( 'Home', 'About', 'Services', 'Contact' ), $page_ids );
		gojago_starter_seed_classic_menu( 'Footer Menu', 'footer', array( 'About', 'Services', 'Contact' ), $page_ids );
	}
);

function gojago_starter_seed_classic_menu( $menu_name, $location, $page_titles, $page_ids ) {
	$menu = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
	} else {
		$menu_id = (int) $menu->term_id;
	}

	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$existing = wp_get_nav_menu_items( $menu_id );
	if ( empty( $existing ) ) {
		foreach ( $page_titles as $title ) {
			if ( isset( $page_ids[ $title ] ) ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'     => $title,
						'menu-item-object-id' => $page_ids[ $title ],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}
		}
	}

	$locations              = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $location ] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

function gojago_starter_home_content() {
	$pattern_files = array(
		'patterns/hero.php',
		'patterns/about.php',
		'patterns/feature-row.php',
		'patterns/service-cards.php',
		'patterns/media-gallery.php',
		'patterns/cta.php',
	);

	$content = '';
	foreach ( $pattern_files as $pattern_file ) {
		ob_start();
		include GOJAGO_STARTER_PATH . $pattern_file;
		$content .= trim( ob_get_clean() ) . "\n\n";
	}

	return trim( $content );
}
