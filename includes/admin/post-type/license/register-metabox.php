<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

return function ( $post, $meta, $parent ) {
	/**
	 * License
	 */
	add_meta_box(
		$post->post_type . '-license',
		esc_html__( 'License', 'certify' ),
		array( $parent, 'render_meta_box'),
		'license',
		'normal',
		'high',
		array(
			'post'  => $post,
			'group' => 'license',
			'meta'  => $meta,
		),
	);

	add_meta_box(
		$post->post_type . '-paddle',
		esc_html__( 'Paddle Integration', 'certify' ),
		array( $parent, 'render_meta_box'),
		'license',
		'normal',
		'high',
		array(
			'post'  => $post,
			'group' => 'paddle',
			'meta'  => $meta,
		),
	);

	add_meta_box(
		$post->post_type . '-activations',
		esc_html__( 'Activation Log', 'certify' ),
		array( $parent, 'render_meta_box'),
		'license',
		'normal',
		'high',
		array(
			'post'  => $post,
			'group' => 'activations',
			'meta'  => $meta,
		),
	);

	add_meta_box(
		$post->post_type . '-email',
		esc_html__( 'Email Resend', 'certify' ),
		array( $parent, 'render_meta_box'),
		'license',
		'side',
		'',
		array(
			'post'  => $post,
			'group' => 'email',
			'meta'  => $meta,
		),
	);

	remove_meta_box( 'slugdiv', 'license', 'normal' );
	remove_meta_box( 'commentsdiv', 'license', 'normal' );
};