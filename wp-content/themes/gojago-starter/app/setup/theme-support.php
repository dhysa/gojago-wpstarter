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

		add_editor_style( 'public/css/app.css' );
	}
);

add_action(
	'after_switch_theme',
	function () {
		gojago_starter_seed_content();
	}
);

function gojago_starter_seed_content() {
	$pages = array(
		'Home'             => gojago_starter_home_content(),
		'Starter Features' => gojago_starter_features_content(),
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

	gojago_starter_seed_classic_menu( 'Primary Menu', 'primary', array( 'Home', 'Starter Features' ), $page_ids );
	gojago_starter_seed_classic_menu( 'Footer Menu', 'footer', array( 'Starter Features' ), $page_ids );
}

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
	if ( empty( $existing ) || gojago_starter_is_replaceable_seed_menu( $existing ) ) {
		if ( ! empty( $existing ) ) {
			foreach ( $existing as $item ) {
				wp_delete_post( $item->ID, true );
			}
		}

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

function gojago_starter_is_replaceable_seed_menu( $items ) {
	$starter_titles = array( 'Home', 'Starter Features', 'About', 'Services', 'Contact' );

	foreach ( $items as $item ) {
		if ( ! in_array( $item->title, $starter_titles, true ) ) {
			return false;
		}
	}

	return true;
}

function gojago_starter_home_content() {
	return gojago_starter_applied_features_content(
		__( 'Applied Starter Features', 'gojago-starter' ),
		__( 'This first page lists the starter features already applied to this WordPress install. It is temporary starter content, so it is safe to delete or replace when the real client homepage is ready.', 'gojago-starter' )
	);
}

function gojago_starter_features_content() {
	return gojago_starter_applied_features_content(
		__( 'Starter Features', 'gojago-starter' ),
		__( 'This is a temporary starter inventory page. Review it during setup, then delete it when the real client sitemap is ready.', 'gojago-starter' )
	);
}

function gojago_starter_applied_features_content( $title, $intro ) {
	return trim(
		'<!-- wp:group {"align":"full","className":"section","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","right":"var:preset|spacing|40","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull section" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40)"><!-- wp:group {"align":"wide","className":"section-readable","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide section-readable"><!-- wp:heading {"level":1,"fontSize":"heading-1"} -->
<h1 class="wp-block-heading has-heading-1-font-size">' . esc_html( $title ) . '</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"lead"} -->
<p class="has-lead-font-size">' . esc_html( $intro ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><!-- wp:list-item -->
<li>Gutenberg-first block theme with templates, parts, patterns, and a custom block example.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Tailwind-backed theme styles and editor styles.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Native WordPress primary and footer menu locations.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Custom login entry at /managesite with wp-login.php hardening.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Required plugin setup for WP Cerber Security, All-in-One WP Migration, ACF Pro, and Gravity Forms Pro.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ZIP plugin bootstrap followed by the WordPress updater when a valid official update channel is available.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Admin cleanup that hides Edit Site and exposes Customize CSS.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Default WordPress plugins such as Hello Dolly and Akismet removed during setup.</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Only the custom starter theme kept active after setup; bundled Twenty themes are removed when possible.</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->'
	);
}
