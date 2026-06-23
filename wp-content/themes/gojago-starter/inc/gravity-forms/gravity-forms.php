<?php
/**
 * Gravity Forms styling hooks.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'gform_submit_button',
	function ( $button, $form ) {
		return str_replace( 'gform_button', 'gform_button gojago-button', $button );
	},
	10,
	2
);
