<?php

namespace CodeSoup\Certify\Frontend;

// Exit if accessed directly
defined( 'WPINC' ) || die;

/**
 * @file
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Init {


    use \CodeSoup\Certify\Traits\HelpersTrait;


    // Main plugin instance
    protected static $instance = null;


    // Assets loader class.
    protected $assets;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {

        // Main plugin instance
        $instance     = certify();
        $hooker       = $instance->get_hooker();
        $this->assets = $instance->get_assets();

        $hooker->add_action( 'wp_enqueue_scripts', $this, 'enqueue_styles' );
        $hooker->add_action( 'wp_enqueue_scripts', $this, 'enqueue_scripts' );
    }


    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * NOTE: Remember to enqueue your styles only on pages where needed
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->get_plugin_id('/css'),
            $this->assets->get('styles/main.css'),
            array(),
            $this->get_plugin_version(),
            'all'
        );
    }


    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * NOTE: Remember to enqueue your scripts only on templates where needed
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->get_plugin_id('/js'),
            $this->assets->get('scripts/main.js'),
            array(),
            $this->get_plugin_version(),
            false
        );
    }
}