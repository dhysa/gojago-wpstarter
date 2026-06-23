<?php
/**
 * Lightweight SEO helpers.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_head',
	function () {
		if ( function_exists( 'wp_get_environment_type' ) && 'local' === wp_get_environment_type() ) {
			echo "<meta name=\"robots\" content=\"noindex,nofollow\">\n";
		}

		if ( is_singular() ) {
			$title       = get_the_title();
			$description = has_excerpt() ? get_the_excerpt() : get_bloginfo( 'description' );
			printf( "<meta property=\"og:title\" content=\"%s\">\n", esc_attr( $title ) );
			printf( "<meta property=\"og:description\" content=\"%s\">\n", esc_attr( wp_strip_all_tags( $description ) ) );
			printf( "<meta property=\"og:url\" content=\"%s\">\n", esc_url( get_permalink() ) );
			echo "<meta property=\"og:type\" content=\"article\">\n";
		}
	},
	5
);
