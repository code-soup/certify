<?php

namespace CodeSoup\Certify\RestApi\Controllers;

use CodeSoup\Certify\Admin\License;

// Exit if accessed directly
defined( 'WPINC' ) || die;


/**
 * @file
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Certify {

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
    public function __construct() {

        // Do something if required
    }


    /**
     * Register route for file uploads from remote repository
     */
    public function register_routes() {

        register_rest_route(
            'certify/v1',
            '/add',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'create_license'),
                    'permission_callback' => '__return_true',
                ),
            )
        );

        register_rest_route(
            'certify/v1',
            '/validate',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'validate_license'),
                    'permission_callback' => '__return_true',
                ),
            )
        );

        register_rest_route(
            'certify/v1',
            '/activate-license',
            array(
                array(
                    'methods'             => \WP_REST_Server::EDITABLE,
                    'callback'            => array($this, 'activate_license'),
                    'permission_callback' => '__return_true',
                ),
            )
        );

        register_rest_route(
            'certify/v1',
            '/deactivate-license',
            array(
                array(
                    'methods'             => \WP_REST_Server::EDITABLE,
                    'callback'            => array($this, 'deactivate_license'),
                    'permission_callback' => '__return_true',
                ),
            )
        );

        register_rest_route(
            'certify/v1',
            '/plugin-update',
            array(
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'handle_plugin_update'),
                    'permission_callback' => '__return_true',
                ),
            )
        );

        register_rest_route(
            'certify/v1',
            '/webhook-paddle',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'handle_paddle_webhook'),
                    'permission_callback' => '__return_true',
                ),
            )
        );
    }


    public function handle_paddle_webhook( \WP_REST_Request $request )
    {
        $response = new \WP_Error(
            'webhook-unsupported',
            __('Sorry, this hook is not yet supported.')
        );
        
        $this->log( $this->verify_paddle_signature( $request ) );

        switch( $request->get_param('alert_name') )
        {
            case 'payment_succeeded':
            case 'subscription_created':
                return $this->create_license( $request );
            break;

            case 'payment_refunded':
            case 'subscription_updated':
            case 'subscription_cancelled':
                return $this->update_license( $request );
            break;
        }

        return rest_ensure_response( $response );
    }



    /**
     * Generate new License key and email it to user
     * 
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function create_license( \WP_REST_Request $request ) {

        $license  = new License;
        $response = $license->create( $request->get_params() );

        return rest_ensure_response( $response );
    }


    /**
     * Generate new License key and email it to user
     * 
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function update_license( \WP_REST_Request $request ) {

        $license  = new License;
        $response = $license->update( $request->get_params() );

        return rest_ensure_response( $response );
    }


    /**
     * Validate License Key
     * 
     * @param  \WP_REST_Request $request [description]
     * @return bool                    
     */
    public function validate_license( \WP_REST_Request $request )
    {
        if ( ! empty($request->get_param('license_key')) )
        {
            $license  = new License( $request->get_param('license_key') );
            $response = $license->validate( $request->get_params() );

            return rest_ensure_response( $response );
        }

        return rest_ensure_response( false );
    }

    /**
     * Activate license for a domain
     * 
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function activate_license( \WP_REST_Request $request )
    {   
        if ( ! empty($request->get_param('license_key')) )
        {
            $license  = new License($request->get_param('license_key'), $request->get_params() );
            $response = $license->activate( $request->get_params() );

            return rest_ensure_response( $response );
        }

        return rest_ensure_response( $request->get_param('license_key') );
    }

    /**
     * Deactivate license for a domain
     * 
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function deactivate_license( \WP_REST_Request $request )
    {
        if ( ! empty($request->get_param('license_key')) )
        {
            $license  = new License( $request->get_param('license_key') );
            $response = $license->deactivate( $request->get_params() );

            return rest_ensure_response( $response );
        }

        return rest_ensure_response( false );
    }


    /**
     * Validate License Key
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function handle_plugin_update( \WP_REST_Request $request ) {

        if ( ! empty($request->get_param('license_key')) )
        {
            $license  = new License( $request->get_param('license_key') );
            $response = $license->handle_update_response();
            
            return rest_ensure_response( $response );
        }

        return rest_ensure_response( false );
    }


    /**
     * Validate Paddle Webhook
     * @link https://developer.paddle.com/webhooks/signature-verification
     * 
     * @param  \WP_REST_Request $request
     * @return [type]                   
     */
    public function verify_paddle_signature( \WP_REST_Request $request ) {

        // Invalid request
        if ( empty($request->get_header('Paddle-Signature')) )
        {
            return false;
        }

        parse_str(str_replace(';', '&', $request->get_header('Paddle-Signature')), $sigval);

        $signature = hash_hmac('sha256',
            sprintf(
                '%s:%s',
                $sigval['ts'],
                $request->get_body()
            ),
            $this->get_option('notification_secret_key')
        );

        return $signature === $request->get_param('p_signature');
    }


    /**
     * Attributes
     */
    public function get_endpoint_args_for_item_schema() {

        $params = array();

        $params['license_key'] = array(
            'default'           => '',
            'description'       => __( 'License Key', 'certify' ),
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'validate_callback' => 'rest_validate_request_arg',
        );

        $params['home_url'] = array(
            'default'           => '',
            'description'       => __( 'Home URL from WP being activated or deactivated', 'certify' ),
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_url',
            'validate_callback' => 'rest_validate_request_arg',
        );


        $params['plugin_id'] = array(
            'default'           => '',
            'description'       => __( 'Plugin slug', 'certify' ),
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_title',
            'validate_callback' => 'rest_validate_request_arg',
        );

        $params['plugin_version'] = array(
            'default'           => '',
            'description'       => __( 'Plugin Version', 'certify' ),
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'validate_callback' => 'rest_validate_request_arg',
        );

        return $params;
    }
}
