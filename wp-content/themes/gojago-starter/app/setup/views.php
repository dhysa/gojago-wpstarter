<?php
/**
 * Load block theme files from resources/views.
 *
 * @package GojagoStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gojago_starter_view_template_folder( $template_type ) {
	return 'wp_template_part' === $template_type ? 'parts' : 'templates';
}

function gojago_starter_view_template_area( $slug ) {
	if ( in_array( $slug, array( 'header', 'footer' ), true ) ) {
		return $slug;
	}

	return 'uncategorized';
}

function gojago_starter_view_template_file_data( $file, $template_type ) {
	$slug = basename( $file, '.html' );
	$data = array(
		'slug'  => $slug,
		'path'  => $file,
		'theme' => get_stylesheet(),
		'type'  => $template_type,
	);

	if ( 'wp_template_part' === $template_type ) {
		$data['area'] = gojago_starter_view_template_area( $slug );
	}

	return $data;
}

function gojago_starter_get_view_template_files( $template_type ) {
	$folder = gojago_starter_view_template_folder( $template_type );
	$files  = glob( GOJAGO_STARTER_PATH . 'resources/views/' . $folder . '/*.html' );

	if ( ! $files ) {
		return array();
	}

	return array_map(
		function ( $file ) use ( $template_type ) {
			return gojago_starter_view_template_file_data( $file, $template_type );
		},
		$files
	);
}

function gojago_starter_view_file_template( $block_template, $id, $template_type ) {
	if ( null !== $block_template ) {
		return $block_template;
	}

	$parts = explode( '//', $id, 2 );
	if ( 2 !== count( $parts ) || get_stylesheet() !== $parts[0] ) {
		return $block_template;
	}

	$folder = gojago_starter_view_template_folder( $template_type );
	$file   = GOJAGO_STARTER_PATH . 'resources/views/' . $folder . '/' . sanitize_file_name( $parts[1] ) . '.html';

	if ( ! file_exists( $file ) ) {
		return $block_template;
	}

	return _build_block_template_result_from_file(
		gojago_starter_view_template_file_data( $file, $template_type ),
		$template_type
	);
}
add_filter( 'pre_get_block_file_template', 'gojago_starter_view_file_template', 10, 3 );

function gojago_starter_merge_view_templates( $templates, $query, $template_type ) {
	if ( ! in_array( $template_type, array( 'wp_template', 'wp_template_part' ), true ) ) {
		return $templates;
	}

	$existing_slugs = wp_list_pluck( $templates, 'slug' );

	foreach ( gojago_starter_get_view_template_files( $template_type ) as $template_file ) {
		if ( in_array( $template_file['slug'], $existing_slugs, true ) ) {
			continue;
		}

		if ( ! empty( $query['slug__in'] ) && ! in_array( $template_file['slug'], $query['slug__in'], true ) ) {
			continue;
		}

		if ( 'wp_template_part' === $template_type && ! empty( $query['area'] ) && $query['area'] !== $template_file['area'] ) {
			continue;
		}

		$templates[]      = _build_block_template_result_from_file( $template_file, $template_type );
		$existing_slugs[] = $template_file['slug'];
	}

	return $templates;
}
add_filter( 'get_block_templates', 'gojago_starter_merge_view_templates', 10, 3 );

function gojago_starter_register_view_patterns() {
	$files = glob( GOJAGO_STARTER_PATH . 'resources/views/patterns/*.php' );
	if ( ! $files ) {
		return;
	}

	foreach ( $files as $file ) {
		$headers = get_file_data(
			$file,
			array(
				'title'      => 'Title',
				'slug'       => 'Slug',
				'categories' => 'Categories',
			)
		);

		if ( empty( $headers['slug'] ) || empty( $headers['title'] ) ) {
			continue;
		}

		ob_start();
		include $file;
		$content = trim( ob_get_clean() );

		register_block_pattern(
			$headers['slug'],
			array(
				'title'      => $headers['title'],
				'categories' => array_filter( array_map( 'trim', explode( ',', $headers['categories'] ) ) ),
				'content'    => $content,
			)
		);
	}
}
add_action( 'init', 'gojago_starter_register_view_patterns' );
