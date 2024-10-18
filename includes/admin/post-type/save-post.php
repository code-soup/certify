<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

return function( $post_id, $parent ) {
	global $wpdb;

	// Check if our nonce is set.
	if ( ! isset( $_POST['_certify_nonce_cspm'] ) ) {
		return $post_id;
	}

	$nonce = $_POST['_certify_nonce_cspm'];

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
	if ( in_array($_POST['post_type'], ['license', 'software']) && ! current_user_can( 'edit_certify', $post_id ) ) {
		return $post_id;
	}

	// Remove non-certify related data
	unset( $_POST['_certify_nonce_cspm'] );


	/**
	 * Software Specific
	 */
	$_postdata = array_filter($_POST, function($key) {
	    return strpos($key, '_certify') !== false;
	}, ARRAY_FILTER_USE_KEY);

	
	if ( 'software' === $_POST['post_type'] )
	{
		$_savedata = array(
			'post_content' => wp_json_encode( $_postdata )
		);
	}

	/**
	 * License Specific
	 */
	if ( 'license' === $_POST['post_type'] )
	{
		$_savedata = array(
			'post_parent'  => $_postdata['_certify_software'],
		);


		/**
		 * Manually adding license trough WP Admin
		 */
		if ( empty($_POST['_certify_license_key']) )
		{
			$license     = new \CodeSoup\Certify\Admin\License();
			$license_key = $license->generateLicenseKey();

			$_savedata['post_name']            = $license_key;
			$_postdata['_certify_license_key'] = $license_key;
		}

		update_post_meta( $post_id, '_certify_next_bill_date', $_postdata['_certify_next_bill_date'] );
		update_post_meta( $post_id, '_certify_license_email', $_postdata['_certify_license_email'] );
		update_post_meta( $post_id, '_certify_license_holder', $_postdata['_certify_license_holder'] );
	}

	// Shared data
	update_post_meta( $post_id, '_certify_external_id', $_postdata['_certify_external_id'] );
	update_post_meta( $post_id, '_certify_activations_limit', $_postdata['_certify_activations_limit'] );

	// Save
	$wpdb->update(
		$wpdb->posts,
		$_savedata,
		array( 'ID' => $post_id ),
	);
};