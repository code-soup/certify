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
	use \CodeSoup\Certify\Traits\UtilsTrait;

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
		$instance     = \CodeSoup\Certify\Init::get_instance();
		$hooker       = $instance->get_hooker();
		$this->assets = $instance->get_assets();
		$this->types  = array(
			'license',
			'software',
		);

		// Register multiple actions
		$hooker->add_actions([
			['admin_enqueue_scripts', $this],
			['admin_menu', $this],
			['init', $this],
			['save_post', $this]
		]);

		// Register multiple actions
		$hooker->add_filters([
			['page_row_actions', $this]
		]);

		new Settings_Page;
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function admin_enqueue_scripts() {

		wp_enqueue_style(
			$this->get_plugin_id('/wp/css'),
			$this->assets->get('styles/admin.css'),
			array(),
			$this->get_plugin_version(),
			'all'
		);

		wp_enqueue_script(
			$this->get_plugin_id('/wp/js'),
			$this->assets->get('scripts/admin.js'),
			array(),
			$this->get_plugin_version(),
			false
		);
	}


	/**
	 * Disable quick edit menu in wordpress for custom post type
	 * @param  [type] $actions [description]
	 * @return [type]          [description]
	 */
	public function page_row_actions( $actions ) {
		
		if (get_post_type() == 'license') {
	        unset($actions['inline hide-if-no-js']);
	    }

	    return $actions;
	}

	/**
	 * Create menu items
	 */
	public function admin_menu() {

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
		
		$class = $this;
		$meta  = 'software' === $post->post_type
			? $post->post_content
			: $post->post_excerpt;

		$data = (array) json_decode( $meta, true );

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
		$handler( $post_id, $this );
	}	
}
