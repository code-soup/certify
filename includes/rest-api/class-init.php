<?php

namespace CodeSoup\Certify\RestApi;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Class responsible for loading the REST API and all REST API namespaces.
 */
class Init {

    /**
     * Fire
     */
    public function __construct() {

        $instance = certify();
        $hooker   = $instance->get_hooker();

        // Register all Routes
        $hooker->add_action('rest_api_init', $this, 'register_rest_routes');
    }

    /**
     * Register REST API routes.
     */
    public function register_rest_routes() {

        $rest = new \CodeSoup\Certify\RestApi\Controllers\Certify();
        $rest->register_routes();
    }

}
