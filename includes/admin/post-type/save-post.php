<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

return function( $post_id, $parent ) {
	global $wpdb;

	// Check if our nonce is set.
	if ( ! isset( $_POST['_certify_nonce_csps'] ) ) {
		return $post_id;
	}

	$nonce = $_POST['_certify_nonce_csps'];

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'certify_save_post_meta' ) ) {
		return $post_id;
	}

	/*
	 * If this is an autosave, our form has not been submitted,
	 * so we don't want to do anything.
	 */
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Check the user's permissions.
	if ( 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	$fields = array(
		'_certify_author_name',
		'_certify_author_profile_url',
		'_certify_plugin_version',
		'_certify_php_required',
		'_certify_wp_required',
		'_certify_wp_tested',
		'_certify_installation_instructions',
		'_certify_plugin_download_url',
		'_certify_banner_low',
		'_certify_banner_high',
		'_certify_paddle_product_id',
		'_certify_paddle_plan_id',
		'_certify_expiry_date',
		'_certify_license_key',
		'_certify_paddle_product_id',
		'_certify_paddle_plan_id',
		'_certify_activations'
	);


	foreach ( $fields as $meta_key )
	{
		if ( ! isset($_POST[$meta_key]) )
		{
			continue;
		}

		update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[$meta_key] ) );
	}

	
	if ( isset($_POST['_certify_changelog']) && isset($_POST['_certify_description']) )
	{
		$wpdb->update(
			$wpdb->prefix. 'posts',
			array(
				'post_content' => sanitize_text_field( $_POST['_certify_changelog'] ),
				'post_excerpt' => sanitize_text_field( $_POST['_certify_description'] ),
			),
			array( 'ID' => $post_id ),
		);
	}
};