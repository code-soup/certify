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
                    'permission_callback' => array($this, 'create_license_permissions_check'),
                ),
            )
        );

        register_rest_route(
            'certify/v1',
            '/validate',
            array(
                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'validate_license'),
                    'permission_callback' => array($this, 'validate_license_permissions_check'),
                ),
            )
        );
    }




    public function create_license( \WP_REST_Request $request ) {

        $licence = new License;
        $data = $licence->create( (array) $request->get_params() );

        return rest_ensure_response( $data );
    }


    /**
     * Validate License Key
     * @param  \WP_REST_Request $request [description]
     * @return [type]                    [description]
     */
    public function validate_license( \WP_REST_Request $request ) {

        return rest_ensure_response($request);
    }


    /**
     * Validate user permissions when trying to deploy from docker
     */
    public function create_license_permissions_check( \WP_REST_Request $request ) {

        return true;
    }


    /**
     * Validate user permissions when trying to deploy from docker
     */
    public function validate_license_permissions_check( \WP_REST_Request $request ) {

        return true;
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
