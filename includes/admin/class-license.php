<?php

namespace CodeSoup\Certify\Admin;

// Exit if accessed directly
defined( 'WPINC' ) || die;

/**
 * @file
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class License {

	use \CodeSoup\Certify\Traits\HelpersTrait;

	// Main plugin instance.
	protected static $instance = null;


	// Assets loader class.
	protected $assets;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		// Main plugin instance.
		$instance     = certify();
		$hooker       = $instance->get_hooker();
		$this->assets = $instance->get_assets();
	}

	public function create( array $array )
	{
		$license = $this->generateLicenseKey();
		$post_id = wp_insert_post( array(
            'post_title'     => $license,
            'post_type'      => 'license',
            'post_status'    => 'publish',
            'post_content'   => wp_json_encode( $array ),
            'post_author'    => $array['user_id'],
            'post_mime_type' => 'application/json',
            'meta_input'     => array(
            	'next_bill_date' => $array['next_bill_date'], // YYYY-MM-DD
            ),
        ));

        return [ $post_id, $license ];
	}


	/**
	 * Generate a License Key.
	 * @return  string
	 */
	function generateLicenseKey() {

	    $unique_id = uniqid(rand(), true);
	    $license_key = sha1($unique_id);

	    return sprintf(
	    	'%s-%s-%s-%s-%s',
	    	substr($license_key, 0, 5),
	    	substr($license_key, 5, 5),
	    	substr($license_key, 10, 5),
	    	substr($license_key, 15, 5),
	    	substr($license_key, 20, 5)
	    );
	}
}