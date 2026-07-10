<?php
/**
 * Analytics placeholders.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gojago_starter_config_value( $constant, $env ) {
	if ( defined( $constant ) && constant( $constant ) ) {
		return (string) constant( $constant );
	}

	$value = getenv( $env );
	return $value ? (string) $value : '';
}

add_action(
	'wp_head',
	function () {
		$gtm = gojago_starter_config_value( 'GOJAGO_GTM_CONTAINER_ID', 'GOJAGO_GTM_CONTAINER_ID' );
		$ga4 = gojago_starter_config_value( 'GOJAGO_GA_MEASUREMENT_ID', 'GOJAGO_GA_MEASUREMENT_ID' );

		if ( $gtm ) {
			printf( "<!-- GTM container configured: %s. Add production snippet here after approval. -->\n", esc_html( $gtm ) );
		}

		if ( $ga4 ) {
			printf( "<!-- GA4 measurement ID configured: %s. Add production snippet here after approval. -->\n", esc_html( $ga4 ) );
		}
	},
	20
);
