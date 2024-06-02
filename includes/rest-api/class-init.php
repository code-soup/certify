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

        $instance = \CodeSoup\Certify\Init::get_instance();
        $hooker   = $instance->get_hooker();

        // Register all Routes
        $hooker->add_actions([
            ['rest_api_init', $this, 'register_rest_routes'],
            ['wp_ajax_resend_email_license', $this, 'resend_email_license_handler'],
            ['wp_ajax_nopriv_resend_email_license', $this, 'resend_email_license_handler'],
        ]);
    }

    /**
     * Register REST API routes.
     */
    public function register_rest_routes() {

        $rest = new \CodeSoup\Certify\RestApi\Controllers\Certify();
        $rest->register_routes();
    }


    public function resend_email_license_handler()
    {
        if ( empty($_REQUEST['nonce']) || ! wp_verify_nonce( $_REQUEST['nonce'], 'certify_wp_xhr_nonce' ))
        {
            return false;
        }
        
        $post_id = $_REQUEST['post_id'];
        $wp_post = get_post( $post_id );
        $mailer = new \CodeSoup\Certify\EmailCustomer(
            array(
                'recepient'     => get_post_meta( $post_id, '_certify_license_email', true ),
                'subject'       => 'Your License Key',
                'customer_name' => get_post_meta( $post_id, '_certify_license_holder', true ),
                'license_key'   => $wp_post->post_name,
            ),
        );

        $mailer->send();
    }

}
