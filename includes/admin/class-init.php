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
class Init {

	use \CodeSoup\Certify\Traits\HelpersTrait;

	// Main plugin instance.
	protected static $instance = null;

	
	// Assets loader class.
	protected $assets;


	// Assets loader class.
	protected $types;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// Main plugin instance.
		$instance     = certify();
		$hooker       = $instance->get_hooker();
		$this->assets = $instance->get_assets();
		$this->types  = array(
			'license',
			'software',
		);

		$hooker->add_action( 'init', $this );

		// Admin hooks.
		$hooker->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );
		$hooker->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
		

		$hooker->add_action( 'admin_menu', $this, 'add_menu_page' );
		$hooker->add_action( 'save_post', $this );

	}

	/**
	 * Enqueue the stylesheets for wp-admin.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style(
			$this->get_plugin_id('/wp/css'),
			$this->assets->get('styles/admin.css'),
			array(),
			$this->get_plugin_version(),
			'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			$this->get_plugin_id('/wp/js'),
			$this->assets->get('scripts/admin.js'),
			array(),
			$this->get_plugin_version(),
			false
		);
	}

	/**
	 * Create menu items
	 */
	public function add_menu_page() {

    	add_menu_page( 
        	'Certify',
        	'Certify',
        	'manage_certify',
        	'certify',
        	NULL,
        	'dashicons-admin-network',
        );
	}

	/**
	 * Register all post types required a for plugin
	 */
	public function init()
	{
		foreach ( $this->types as $name )
		{
			$args = require_once "post-type/{$name}/register-type.php";
			register_post_type( $name, $args );	
		}
	}

	/**
	 * Register Meta Boxes
	 */
	public function register_meta_box( $post )
	{
		$meta = get_post_meta( $post->ID );

		$register = include "post-type/{$post->post_type}/register-metabox.php";
		$register( $post, $meta, $this);
	}


	public function render_meta_box( $post, $args ) {
		include "post-type/{$post->post_type}/metabox/{$args['args']['group']}.php";
	}
	

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_post( $post_id )
	{
		$handler = include "post-type/save-post.php";
		$handler( $post_id, $this);
	}	
}
