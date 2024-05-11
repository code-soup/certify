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
    }



    /**
     * Generate new License key and email it to user
     * 
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function create_license( \WP_REST_Request $request ) {

        $licence = new License;
        $data    = $licence->create( $request->get_params() );

        return rest_ensure_response( $data );
    }


    /**
     * Validate License Key
     * 
     * @param  \WP_REST_Request $request [description]
     * @return bool                    
     */
    public function validate_license( \WP_REST_Request $request )
    {
        $license  = new License( $request->get_param('license_key') );
        $is_valid = $license->validate();
        
        return rest_ensure_response( $is_valid );
    }

    /**
     * Activate license for a domain
     * 
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function activate_license( \WP_REST_Request $request )
    {
        $license_key = $request->get_param('license_key');
        $license     = new License($license_key);
        $response    = $license->activate([
            'host' => $request->get_header('host')
        ]);

        return rest_ensure_response( $response );
    }

    /**
     * Deactivate license for a domain
     * 
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function deactivate_license( \WP_REST_Request $request )
    {
        $license_key = $request->get_param('license_key');
        $license     = new License($license_key);
        $response    = $license->deactivate([
            'host' => $request->get_header('host')
        ]);

        return rest_ensure_response( $response );
    }


    /**
     * Validate License Key
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function handle_plugin_update( \WP_REST_Request $request ) {

        $license = new License( $request->get_param('license_key') );
        $data    = $license->handle_update_response();
        
        return rest_ensure_response( $data );
    }


    /**
     * Attributes
     */
    public function get_endpoint_args_for_item_schema() {

        $params = array();

        $params['id'] = array(
            'default'           => 0,
            'description'       => _x( 'Software ID', 'WP_Post object ID', 'certify' ),
            'type'              => 'integer',
            'sanitize_callback' => 'intval',
            'validate_callback' => 'rest_validate_request_arg',
            'field'             => 'ID',
        );

        $params['secret'] = array(
            'default'           => '',
            'description'       => _x( 'Secret Key', 'Paddle API key', 'certify' ),
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'validate_callback' => 'rest_validate_request_arg',
            'field'             => 'post_password',
        );

        return $params;
    }
}
