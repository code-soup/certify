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
	use \CodeSoup\Certify\Traits\UtilsTrait;

	// Main plugin instance.
	// protected static $instance = null;


	// Assets loader class.
	// protected $assets;


	// License WP_Post->ID
	protected $post_id;


	protected $license_key;

	
	protected $license_object;


	protected $send_new_license_email = true;


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
		$license  = $this->generateLicenseKey();
		$postdata = array(
            'post_title'     => $array['checkout_id'],
            'post_name'      => $license,
            'post_type'      => 'license',
            'post_status'    => 'private',
            'post_content'   => wp_json_encode( $array ), // Webhook data
            'post_excerpt'   => wp_json_encode( array() ), // Activations
            'post_parent'    => $this->get_software_post_id( $array ),
            'post_author'    => $array['user_id'],
            'post_mime_type' => 'application/json',
            'meta_input'     => array(
				'_certify_license_holder'  => $array['customer_name'],
				'_certify_license_email'   => $array['email'],
            ),
        );


		/**
		 * Different data for post type based on the webhook
		 */
        switch( $array['alert_name'] )
        {
        	/**
        	 * Fired when a new subscription is created, and a customer has successfully subscribed.
        	 */
        	case 'subscription_created':
        		
        		$order_key = 'subscription_id';

        		$postdata['meta_input'] = array_merge(
	        		$postdata['meta_input'],
	        		array(
						'_certify_external_id'     => $array['subscription_plan_id'],
						'_certify_subscription_id' => $array['subscription_id'],
						'_certify_next_bill_date'  => $array['next_bill_date'],
	        		)
	        	);

        	break;


        	/**
        	 * Fired when a payment is made into your Paddle account.
        	 */
        	case 'payment_succeeded':
        		
        		$order_key = 'order_id';

        		$postdata['meta_input'] = array_merge(
	        		$postdata['meta_input'],
	        		array(
						'_certify_external_id' => $array['product_id'],
						'_certify_order_id'    => $array['order_id'],
	        		)
	        	);
        	break;
        }

        $post_id = wp_insert_post( $postdata );

		/**
		 * Insert
		 */
        $this->add_log_entry([
        	'post_id' => $post_id,
        	'type'    => $array['alert_name'],
        	'data'    => wp_json_encode( $array ),
        	'user'    => $array['customer_name'],
        ]);

        
        /**
         * Send License to user via email
         */
        if ( $this->send_new_license_email )
        {
        	$mailer = new \CodeSoup\Certify\EmailCustomer(
				array(
					'recepient'     => $array['email'],
					'subject'       => sprintf('Order #%d', $array[ $order_key ]),
					'order_id'      => $array[ $order_key ],
					'customer_name' => $array['customer_name'],
					'license_key'   => $license,
					'order_data'    => $array
				),
			);

			$mailer->send();
        }

        return [ 
        	$post_id,
        	$license
        ];
	}

	
	/**
	 * Update specific license
	 */
	public function update( array $array )
	{
		$updated = false;
		$post    = $this->get_subscription_license_post( $array );

		if ( empty($post) )
		{
			return $updated;
		}

		/**
		 * Different data for post type based on the webhook
		 */
        switch( $array['alert_name'] )
        {
        	/**
        	 * Fired when an existing subscription changes (eg. a customer changes plan via upgrade or downgrade).
        	 */
        	case 'subscription_updated':
        	case 'subscription_cancelled':
        		
        		updated_post_meta( $post->ID, '_certify_next_bill_date', $array['cancellation_effective_date'] );
        		$updated = true;
        	break;

       
        	/**
        	 * Fired when a payment is refunded.
        	 */
        	case 'payment_refunded':

        		updated_post_meta( $post->ID, '_certify_next_bill_date', $array['event_time'] );
        		$updated = true;
        	break;
        }

		/**
		 * Insert
		 */
        $this->add_log_entry([
        	'post_id' => $post->ID,
        	'type'    => $array['alert_name'],
        	'data'    => wp_json_encode( $array ),
        	'user'    => $array['customer_name'],
        ]);


        return $updated;
	}


	/**
	 * Activate license for a single domain
	 */
	public function activate( $args = [] )
	{

		$obj     = $this->license_object;
		$response = new \WP_Error('invalid-request', __('Something went wrong, please contact support', 'certify') );

		if ( empty($args['host']) || is_wp_error( $obj) )
		{
			return $response;
		}

		// Add to list
		$updated   = []; $this->remove_activation( $args['host'], $obj['activations'] );
		$updated[] = array(
			'host' => $args['host'],
			'time' => time()
		);
		$updated[] = array(
			'host' => $args['host'],
			'time' => time()
		);

		// Save to DB
		wp_update_post([
			'ID'           => $obj['id'],
			'post_excerpt' => wp_json_encode($updated),
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
			'post_excerpt' => wp_json_encode( $updated, JSON_PRETTY_PRINT ),
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
	public function validate()
	{
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
			'slug'           => dirname( $this->get_value('_certify_plugin_slug', $meta) ),
			'path'           => $this->get_value('_certify_plugin_slug', $meta),
			'author'         => sprintf(
				'<a href="%s">%s</a>',
				$this->get_value('_certify_author_profile_url', $meta),
				$this->get_value('_certify_author_name', $meta)
			),
			'homepage'       => $this->get_value('_certify_homepage_url', $meta),
			'donate_link'    => $this->get_value('_certify_donate_link', $meta),
			'author_profile' => $this->get_value('_certify_author_profile_url', $meta),
			'version'        => $this->get_value('_certify_plugin_version', $meta),
			'new_version'    => $this->get_value('_certify_plugin_version', $meta),
			'download_url'   => $this->get_value('_certify_plugin_download_url', $meta),
			'requires'       => $this->get_value('_certify_wp_required', $meta),
			'tested'         => $this->get_value('_certify_wp_tested', $meta),
			'requires_php'   => $this->get_value('_certify_php_required', $meta),
			'last_updated'   => $wp_post->post_modified,
		    'sections' => array(
				'description'  => $this->get_value('_certify_description', $meta),
				'installation' => $this->get_value('_certify_installation_instructions', $meta),
				'changelog'    => $this->get_value('_certify_changelog', $meta),
		    ),
		    'banners' => array(
				'low'  => $this->get_value('_certify_banner_low', $meta),
				'high' => $this->get_value('_certify_banner_high', $meta),
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


	private function get_license_post( array $array )
	{
		// Subscription purchase meta
		if ( isset($array['subscription_id']) )
		{
			$meta_query = array(
				array(
					'key'    => '_certify_subscription_id',
					'value'  => $array['subscription_id'],
				),
				array(
					'key'    => '_certify_subscription_plan_id',
					'value'  => $array['subscription_plan_id'],
				),
			);
		}
		// One off purchase
		else
		{
			$meta_query = array(
				array(
					'key'    => '_certify_order_id',
					'value'  => $array['order_id'],
				)
			);
		}

		$posts = get_posts([
			'post_type'   => 'license',
			'post_author' => $array['user_id'],
			'meta_query'  => $meta_query,
		]);

		// Not found
		if ( empty( $posts[0] ) )
		{
			// Log that for some reason post was not found
			$this->log('License post request not found.' . print_r($meta_query, true) );
			return false;
		}

		return $post[0];
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
	public function generateLicenseKey()
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