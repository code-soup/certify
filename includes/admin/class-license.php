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
	// protected static $instance = null;


	// Assets loader class.
	// protected $assets;


	// License WP_Post->ID
	protected $post_id;


	protected $license_key;

	protected $license_object;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( string $license_key = '' )
	{
		// Main plugin instance.
		// $instance     = \CodeSoup\Certify\Init::get_instance();
		// $hooker       = $instance->get_hooker();
		// $this->assets = $instance->get_assets();

		if ( ! empty($license_key) )
		{
			$this->license_key    = sanitize_text_field( $license_key );
			$this->license_object = $this->get_license_object( $this->license_key );
		}
	}

	public function create( array $array )
	{
		$license = $this->generateLicenseKey();
		$post_id = wp_insert_post( array(
            'post_title'     => sprintf('Order #%s', $array['order_id']),
            'post_name'      => $license,
            'post_type'      => 'license',
            'post_status'    => 'private',
            'post_content'   => wp_json_encode( $array ), // Subscription webhook data
            'post_excerpt'   => array(), // Activations are saved here
            'post_parent'    => $this->get_software_post_id( $array ),
            'post_author'    => $array['user_id'],
            'post_mime_type' => 'application/json',
            'meta_input'     => array(
				'_certify_next_bill_date'  => $array['next_bill_date'], // YYYY-MM-DD
				'_certify_external_id'     => $array['subscription_plan_id'],
				'_certify_subscription_id' => $array['subscription_id']
            ),
        ));

        $this->add_log_entry([
        	'post_id' => $post_id,
        	'type'    => $array['alert_name'],
        	'data'    => $array,
        	'user'    => $array['customer_name'],
        ]);

        /**
         * Send License to user via email
         */
		$mailer = new \CodeSoup\Certify\EmailCustomer(
			array(
				'recepient'     => $array['email'],
				'subject'       => sprintf('Order #%s', $array['order_id']),
				'order_id'      => $array['order_id'],
				'customer_name' => $array['customer_name'],
				'license_key'   => $license,
			),
		);

		$mailer->send();

        return [ $post_id, $license ];
	}

	
	/**
	 * Update specific license
	 */
	public function update( $args = [] ) {}


	/**
	 * Activate license for a single domain
	 */
	public function activate( $args = [] ) {

		$obj     = $this->license_object;
		$response = new \WP_Error('invalid-request', __('Something went wrong, please contact support', 'certify') );

		if ( empty($args['host']) || is_wp_error( $obj) )
		{
			return $response;
		}

		// Add to list
		$updated   = $this->remove_activation( $args['host'], $obj['activations'] );
		$updated[] = array(
			'host' => $args['host'],
			'time' => time()
		);

		// Save to DB
		wp_update_post([
			'ID'           => $obj['id'],
			'post_excerpt' => wp_json_encode( $updated ),
		]);

		// Log
		$this->add_log_entry([
        	'post_id' => $obj['id'],
        	'type'    => 'license_activate',
        	'data'    => $args['host'],
        	'user'    => '',
        ]);

		return true;
	}


	/**
	 * Deactivate license for a single domain
	 */
	public function deactivate( $args = [] )
	{
		$obj     = $this->license_object;
		$response = new \WP_Error('invalid-request', __('Something went wrong, please contact support', 'certify') );

		if ( empty($args['host']) || is_wp_error( $obj) )
		{
			return $response;
		}

		// Remove from list
		$updated = $this->remove_activation( $args['host'], $obj['activations'] );

		// Save to DB
		wp_update_post([
			'ID'           => $obj['id'],
			'post_excerpt' => wp_json_encode( $updated ),
		]);

		// Log
		$this->add_log_entry([
        	'post_id' => $obj['id'],
        	'type'    => 'license_deactivate',
        	'data'    => $args['host'],
        	'user'    => '',
        ]);

		return true;
	}


	/**
	 * Check if license key is valid
	 */
	public function validate() {

		$obj = $this->license_object;

		// Error Getting License Key
		if ( is_wp_error( $obj ) )
		{
			return array(
				'valid'  => false,
				'expiry' => 0,
			);
		}

		return array(
			'valid'  => $obj['can_update'],
			'expiry' => strtotime($obj['next_bill_date']),
		);
	}


	/**
	 * Log every webhook call and activation/deactivation
	 * @param [type] $post_id [description]
	 * @param array  $data    [description]
	 */
	public function add_log_entry( array $args = [] )
	{
		wp_insert_comment(array(
			'comment_post_ID' => $args['post_id'],
			'comment_content' => wp_json_encode( $args['data'] ),
			'comment_type'    => $args['type'],
			'comment_author'  => empty($args['user']) ? 'system' : $args['user'],
        ));
	}


	/**
	 * Get single post object by subscription_id
	 */
	public function get_license_object()
	{
		global $wpdb;

		$response      = new \WP_Error('invalid-key', __('License key invalid', 'certify') );
		$this->post_id = $wpdb->get_var(
			sprintf("SELECT ID FROM {$wpdb->posts} WHERE post_name = '%s'", $this->license_key )
		);

		// Not found
		if ( empty($this->post_id) )
		{
			return $response;
		}

		/**
		 * Get WP_Post object
		 */
		$post = get_post( $this->post_id, ARRAY_A );

		if ( is_wp_error($post) || empty($post) || 'publish' != $post['post_status'] )
		{
			return $response;
		}
		
		$data = array(
			'id'                => $post['ID'],
			'plugin_post_id'    => $post['post_parent'],
			'plugin_name'       => get_the_title( $post['post_parent'] ),
			'subscription_id'   => intval( get_post_meta( $post['ID'], '_certify_subscription_id', true ) ),
			'next_bill_date'    => get_post_meta( $post['ID'], '_certify_next_bill_date', true ),
			'external_id'       => intval( get_post_meta( $post['ID'], '_certify_external_id', true ) ),
			'activations_limit' => intval( get_post_meta( $post['post_parent'], '_certify_activations_limit', true ) ),
			'days_left'         => 0,
			'expired'           => true,
			'can_update'        => false,
		);

		// Current activations
		$data['activations'] = empty($post->post_excerpt)
			? array()
			: (array) json_decode($post->post_excerpt, true);

		// Calculate availability
		$data['activations_available'] = max(0, ( intval($data['activations_limit']) - count( $data['activations'] )) );

		// How many days left
		if ( ! empty($data['next_bill_date']) )
		{
			$date     = \DateTime::createFromFormat('Y-m-d', $data['next_bill_date']);
			$now      = new \DateTime('now');
			$interval = $now->diff($date);

			$data['days_left'] = max(0, $interval->days);
		}

		$data['expired']    = $data['days_left'] <= 0;
		$data['can_update'] = ($data['days_left'] > 0 && $data['activations_available'] > 0);

		return $data;
	}



	public function handle_update_response()
	{
		$plugin = $this->license_object;

		if ( empty( $plugin['can_update'] ) )
		{
			return NULL;
		}

		$post_id = $plugin['plugin_post_id'];
		$wp_post = get_post( $post_id );
		$meta    = json_decode( $wp_post->post_content, true );

		return array(
			'name'           => $wp_post->post_title,
			'slug'           => dirname($meta['_certify_plugin_slug']),
			'path'           => $meta['_certify_plugin_slug'],
			'author'         => sprintf(
				'<a href="%s">%s</a>',
				$meta['_certify_author_profile_url'],
				$meta['_certify_author_name']
			),
			'homepage'       => $meta['_certify_homepage_url'],
			'donate_link'    => $meta['_certify_donate_link'],
			'author_profile' => $meta['_certify_author_profile_url'],
			'version'        => $meta['_certify_plugin_version'],
			'new_version'    => $meta['_certify_plugin_version'],
			'download_url'   => $meta['_certify_plugin_download_url'],
			'requires'       => $meta['_certify_wp_required'],
			'tested'         => $meta['_certify_wp_tested'],
			'requires_php'   => $meta['_certify_php_required'],
			'last_updated'   => $wp_post->post_modified,
		    'sections' => array(
				'description'  => $meta['_certify_description'],
				'installation' => $meta['_certify_installation_instructions'],
				'changelog'    => $meta['_certify_changelog'],
		    ),
		    'banners' => array(
				'low'  => $meta['_certify_banner_low'],
				'high' => $meta['_certify_banner_high'],
		    )
		);
	}


	/**
	 * Internal Software WP_Post
	 */
	private function get_software_post_id( $plan_id = 0 )
	{
		global $wpdb;

		return $wpdb->get_var(
			sprintf(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE 'meta_key' = '_certify_paddle_id' AND 'meat_value' = %d",
				$plan_id
			)
		);
	}


	/**
	 * Remove activation if exists from all current activations
	 * 
	 * @param  [type] $new_host    [description]
	 * @param  [type] $activations [description]
	 * @return [type]              [description]
	 */
	private function remove_activation( string $new_host = '', array $activations = [] )
	{
		$updated = $activations;

	    foreach ($activations as $key => $activation)
	    {
        	if ( ! is_array($activation))
        	{
        		continue;
        	}

        	if ( $activation['host'] === $new_host ) 
        	{
        		unset($updated[$key]);
        	}
        }
    	
    	return $updated;
	}


	/**
	 * Generate a License Key.
	 * @return  string
	 */
	function generateLicenseKey()
	{
		$hash = sha1( uniqid(rand(), true) );

	    return sprintf(
	    	'%s-%s-%s-%s-%s',
	    	substr($hash, 0, 5),
	    	substr($hash, 5, 5),
	    	substr($hash, 10, 5),
	    	substr($hash, 15, 5),
	    	substr($hash, 20, 5)
	    );
	}
}