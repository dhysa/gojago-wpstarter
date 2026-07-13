<?php
/**
 * Admin reminder and ZIP installer for commercial plugins.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gojago_starter_required_plugins() {
	return array(
		'all-in-one-wp-migration' => array(
			'label'       => 'All-in-One WP Migration',
			'plugin_file' => 'all-in-one-wp-migration/all-in-one-wp-migration.php',
			'hints'       => array( 'all-in-one-wp-migration' ),
			'indicator'   => 'all-in-one-wp-migration/all-in-one-wp-migration.php',
			'note'        => __( 'Installed automatically from WordPress.org during local setup when available.', 'gojago-starter' ),
		),
		'wp-cerber'     => array(
			'label'       => 'WP Cerber Security',
			'plugin_file' => 'wp-cerber/wp-cerber.php',
			'hints'       => array( 'wp-cerber', 'cerber' ),
			'indicator'   => 'wp-cerber/wp-cerber.php',
			'note'        => __( 'Installed from plugins/ or the official WP Cerber download source during local setup when available.', 'gojago-starter' ),
		),
		'acf-pro'       => array(
			'label'       => 'ACF Pro',
			'plugin_file' => 'advanced-custom-fields-pro/acf.php',
			'hints'       => array( 'acf-pro', 'advanced-custom-fields-pro' ),
			'indicator'   => 'advanced-custom-fields-pro/acf.php',
		),
		'gravityforms'  => array(
			'label'       => 'Gravity Forms Pro',
			'plugin_file' => 'gravityforms/gravityforms.php',
			'hints'       => array( 'gravityforms', 'gravity-forms' ),
			'indicator'   => 'gravityforms/gravityforms.php',
		),
	);
}

function gojago_starter_plugin_status( $plugin_file ) {
	if ( is_plugin_active( $plugin_file ) ) {
		return 'active';
	}

	$plugins = get_plugins();
	return isset( $plugins[ $plugin_file ] ) ? 'inactive' : 'missing';
}

function gojago_starter_project_plugin_dir() {
	return is_dir( '/project-plugins' ) ? '/project-plugins' : trailingslashit( dirname( GOJAGO_STARTER_PATH, 4 ) ) . 'plugins';
}

function gojago_starter_find_plugin_zip( $plugin ) {
	$dir = gojago_starter_project_plugin_dir();
	if ( ! is_dir( $dir ) ) {
		return '';
	}

	$zips = glob( trailingslashit( $dir ) . '*.zip' );
	if ( ! $zips ) {
		return '';
	}

	foreach ( $zips as $zip ) {
		$name = strtolower( basename( $zip ) );
		foreach ( $plugin['hints'] as $hint ) {
			if ( false !== strpos( $name, $hint ) ) {
				return $zip;
			}
		}
	}

	if ( class_exists( 'ZipArchive' ) ) {
		foreach ( $zips as $zip ) {
			$archive = new ZipArchive();
			if ( true === $archive->open( $zip ) ) {
				for ( $i = 0; $i < $archive->numFiles; $i++ ) {
					if ( $plugin['indicator'] === $archive->getNameIndex( $i ) ) {
						$archive->close();
						return $zip;
					}
				}
				$archive->close();
			}
		}
	}

	return '';
}

add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$rows = array();
		foreach ( gojago_starter_required_plugins() as $slug => $plugin ) {
			$status = gojago_starter_plugin_status( $plugin['plugin_file'] );
			if ( 'active' === $status ) {
				continue;
			}

			$zip = gojago_starter_find_plugin_zip( $plugin );
			$url = wp_nonce_url(
				add_query_arg(
					array(
						'gojago_install_required_plugin' => $slug,
					),
					admin_url()
				),
				'gojago_install_required_plugin_' . $slug
			);

			$rows[] = sprintf(
				'<li><strong>%1$s</strong>: %2$s. %3$s%4$s</li>',
				esc_html( $plugin['label'] ),
				esc_html( $status ),
				$zip ? '<a class="button button-small" href="' . esc_url( $url ) . '">' . esc_html__( 'Install and activate', 'gojago-starter' ) . '</a>' : '<a href="' . esc_url( admin_url( 'plugin-install.php?tab=upload' ) ) . '">' . esc_html__( 'Upload plugin ZIP', 'gojago-starter' ) . '</a>',
				isset( $plugin['note'] ) ? ' <span class="description">' . esc_html( $plugin['note'] ) . '</span>' : ''
			);
		}

		if ( $rows ) {
			echo '<div class="notice notice-warning"><p><strong>Gojago Starter plugin readiness</strong></p><ul>' . wp_kses_post( implode( '', $rows ) ) . '</ul></div>';
		}
	}
);

add_action(
	'admin_init',
	function () {
		if ( empty( $_GET['gojago_install_required_plugin'] ) ) {
			return;
		}

		$slug = sanitize_key( wp_unslash( $_GET['gojago_install_required_plugin'] ) );
		if ( ! isset( gojago_starter_required_plugins()[ $slug ] ) ) {
			wp_die( esc_html__( 'Unknown plugin.', 'gojago-starter' ) );
		}

		check_admin_referer( 'gojago_install_required_plugin_' . $slug );

		if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'gojago-starter' ) );
		}

		$plugin = gojago_starter_required_plugins()[ $slug ];
		$zip    = gojago_starter_find_plugin_zip( $plugin );
		if ( ! $zip ) {
			wp_die( esc_html__( 'Plugin ZIP not found in plugins/.', 'gojago-starter' ) );
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
		$result   = $upgrader->install( $zip );

		if ( is_wp_error( $result ) ) {
			wp_die( esc_html( $result->get_error_message() ) );
		}

		activate_plugin( $plugin['plugin_file'] );
		wp_safe_redirect( admin_url( 'plugins.php' ) );
		exit;
	}
);
