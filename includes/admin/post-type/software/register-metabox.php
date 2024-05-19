<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

return function ( $post, $meta, $parent ) {
	
	add_meta_box(
		$post->post_type . '-paddle',
		esc_html__( 'Paddle', 'certify' ),
		array( $parent, 'render_meta_box'),
		'software',
		'normal',
		'high',
		array(
			'post'  => $post,
			'group' => 'paddle',
			'meta'  => $meta,
		),
	);

	add_meta_box(
		$post->post_type . '-settings',
		esc_html__( 'Plugin Info', 'certify' ),
		array( $parent, 'render_meta_box'),
		'software',
		'normal',
		'high',
		array(
			'post'  => $post,
			'group' => 'settings',
			'meta'  => $meta,
		),
	);


	add_meta_box(
		$post->post_type . '-banners',
		esc_html__( 'Banners', 'certify' ),
		array( $parent, 'render_meta_box'),
		'software',
		'normal',
		'high',
		array(
			'post'  => $post,
			'group' => 'banners',
			'meta'  => $meta,
		),
	);

	
	add_meta_box(
		$post->post_type . '-changelog',
		esc_html__( 'Changelog', 'certify' ),
		array( $parent, 'render_meta_box'),
		'software',
		'normal',
		'high',
		array(
			'post'  => $post,
			'group' => 'changelog',
			'meta'  => $meta,
		),
	);
};