<?php
/**
 * Block registration.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'init',
	function () {
		register_block_pattern_category(
			'gojago-starter',
			array( 'label' => __( 'Gojago Starter', 'gojago-starter' ) )
		);

		register_block_type( GOJAGO_STARTER_PATH . 'public/blocks/example-block' );
	}
);
