<?php
/**
 * Gojago login hardening.
 *
 * Keeps /managesite as the only unauthenticated login entrypoint.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gojago_login_slug() {
	$slug = defined( 'GOJAGO_LOGIN_SLUG' ) ? GOJAGO_LOGIN_SLUG : getenv( 'GOJAGO_LOGIN_SLUG' );
	$slug = $slug ? sanitize_title( $slug ) : 'managesite';

	return $slug ?: 'managesite';
}

function gojago_request_path() {
	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';
	return trim( (string) $path, '/' );
}

function gojago_serve_login() {
	global $pagenow;

	$pagenow = 'wp-login.php';
	require ABSPATH . 'wp-login.php';
	exit;
}

function gojago_is_login_route_url( $url ) {
	if ( ! $url ) {
		return true;
	}

	$path = wp_parse_url( $url, PHP_URL_PATH );
	$path = trim( (string) $path, '/' );

	return in_array( $path, array( gojago_login_slug(), 'wp-login.php' ), true );
}

function gojago_is_login_action_request() {
	$action = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : '';

	return in_array( $action, array( 'logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'postpass' ), true );
}

function gojago_block_default_login() {
	status_header( 404 );
	nocache_headers();
	include get_query_template( '404' );
	exit;
}

add_action(
	'init',
	function () {
		$path = gojago_request_path();
		$slug = gojago_login_slug();

		if ( $slug === $path ) {
			if ( is_user_logged_in() && ! gojago_is_login_action_request() && ( empty( $_SERVER['REQUEST_METHOD'] ) || 'GET' === $_SERVER['REQUEST_METHOD'] ) ) {
				wp_safe_redirect( admin_url() );
				exit;
			}

			gojago_serve_login();
		}

		if ( 'wp-login.php' === $path && ! is_user_logged_in() ) {
			gojago_block_default_login();
		}

		if ( 'wp-admin' === $path && ! is_user_logged_in() ) {
			wp_safe_redirect( home_url( '/' . $slug . '/' ) );
			exit;
		}
	},
	0
);

add_filter(
	'login_url',
	function ( $login_url, $redirect, $force_reauth ) {
		$args = array();

		if ( ! empty( $redirect ) ) {
			$args['redirect_to'] = $redirect;
		}

		if ( $force_reauth ) {
			$args['reauth'] = '1';
		}

		return add_query_arg( $args, home_url( '/' . gojago_login_slug() . '/' ) );
	},
	10,
	3
);

add_filter(
	'login_redirect',
	function ( $redirect_to, $requested_redirect_to, $user ) {
		if ( is_wp_error( $user ) ) {
			return $redirect_to;
		}

		if ( gojago_is_login_route_url( $redirect_to ) || gojago_is_login_route_url( $requested_redirect_to ) ) {
			return admin_url();
		}

		return $redirect_to ?: admin_url();
	},
	10,
	3
);
