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

		// Admin hooks.
		$hooker->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );
		$hooker->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
		$hooker->add_action( 'init', $this, 'register_post_type' );
		$hooker->add_action( 'admin_menu', $this, 'add_menu_page' );

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
	 * Register all post types required a for plugin
	 */
	public function register_post_type() {

		$labels_software = array(
			'name'                  => _x( 'Software', 'Post type general name', 'certify' ),
			'singular_name'         => _x( 'Software', 'Post type singular name', 'certify' ),
			'menu_name'             => _x( 'Software', 'Admin Menu text', 'certify' ),
			'name_admin_bar'        => _x( 'Software', 'Add New on Toolbar', 'certify' ),
			'add_new'               => __( 'Add New', 'certify' ),
			'add_new_item'          => __( 'Add New Software', 'certify' ),
			'new_item'              => __( 'New Software', 'certify' ),
			'edit_item'             => __( 'Edit Software', 'certify' ),
			'view_item'             => __( 'View Software', 'certify' ),
			'all_items'             => __( 'All Software', 'certify' ),
			'search_items'          => __( 'Search Software', 'certify' ),
			'parent_item_colon'     => __( 'Parent Software:', 'certify' ),
			'not_found'             => __( 'No Software found.', 'certify' ),
			'not_found_in_trash'    => __( 'No Software found in Trash.', 'certify' ),
			'featured_image'        => _x( 'Software Cover Image', 'Overrides the “Featured Image” phrase for this post type.', 'certify' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type.', 'certify' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type.', 'certify' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type.', 'certify' ),
			'archives'              => _x( 'Software archives', 'The post type archive label used in nav menus. Default “Post Archives”.', 'certify' ),
			'insert_into_item'      => _x( 'Insert into Software', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post).', 'certify' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this Software', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post).', 'certify' ),
			'filter_items_list'     => _x( 'Filter Software list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”.', 'certify' ),
			'items_list_navigation' => _x( 'Software list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”.', 'certify' ),
			'items_list'            => _x( 'Software list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”.', 'certify' ),
		);

		$args_software = array(
			'can_export'           => false,
			'capability_type'      => 'certify',
			'delete_with_user'     => false,
			'description'          => _x('Software', 'Post type description', 'certify'),
			'exclude_from_search'  => true,
			'has_archive'          => false,
			'hierarchical'         => true,
			'labels'               => $labels_software,
			'menu_icon'            => 'dashicons-html',
			'menu_position'        => 93,
			'public'               => true,
			'publicly_queryable'   => false,
			'query_var'            => false,
			'show_in_admin_bar'    => false,
			'show_in_menu'         => 'certify',
			'show_in_nav_menus'    => false,
			'show_in_rest'         => false,
			'show_ui'              => true,
			'supports'             => array( 'title' ),
			'register_meta_box_cb' => array( $this, 'register_meta_box' ),
		);


		$labels_license = array(
            'name'                  => _x( 'License', 'Post type general name', 'certify' ),
            'singular_name'         => _x( 'License', 'Post type singular name', 'certify' ),
            'menu_name'             => _x( 'License', 'Admin Menu text', 'certify' ),
            'name_admin_bar'        => _x( 'License', 'Add New on Toolbar', 'certify' ),
            'add_new'               => __( 'Add New', 'certify' ),
            'add_new_item'          => __( 'Add New License', 'certify' ),
            'new_item'              => __( 'New License', 'certify' ),
            'edit_item'             => __( 'Edit License', 'certify' ),
            'view_item'             => __( 'View License', 'certify' ),
            'all_items'             => __( 'All Licenses', 'certify' ),
            'search_items'          => __( 'Search Licenses', 'certify' ),
            'parent_item_colon'     => __( 'Parent License:', 'certify' ),
            'not_found'             => __( 'No License found.', 'certify' ),
            'not_found_in_trash'    => __( 'No License found in Trash.', 'certify' ),
            'featured_image'        => _x( 'License Cover Image', 'Overrides the “Featured Image” phrase for this post type.', 'certify' ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type.', 'certify' ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type.', 'certify' ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type.', 'certify' ),
            'archives'              => _x( 'License archives', 'The post type archive label used in nav menus. Default “Post Archives”.', 'certify' ),
            'insert_into_item'      => _x( 'Insert into License', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post).', 'certify' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this License', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post).', 'certify' ),
            'filter_items_list'     => _x( 'Filter License list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”.', 'certify' ),
            'items_list_navigation' => _x( 'License list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”.', 'certify' ),
            'items_list'            => _x( 'License list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”.', 'certify' ),
        );

		$args_license = array(
			'can_export'           => false,
			'capability_type'      => 'certify',
			'delete_with_user'     => false,
			'description'          => _x( 'Licenses', 'Post type description', 'certify' ),
			'exclude_from_search'  => true,
			'has_archive'          => false,
			'hierarchical'         => true,
			'labels'               => $labels_license,
			'menu_icon'            => 'dashicons-tickets',
			'menu_position'        => 94,
			'public'               => true,
			'publicly_queryable'   => false,
			'query_var'            => false,
			'show_in_admin_bar'    => false,
			'show_in_menu'         => 'certify',
			'show_in_nav_menus'    => false,
			'show_in_rest'         => false,
			'show_ui'              => true,
			'supports'             => array( 'title' ),
			'register_meta_box_cb' => array( $this, 'register_meta_box' ),
		);

		register_post_type('license', $args_license);
		register_post_type('software', $args_software);
		
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
	 * Meta Boxes
	 * @param  [type] $post [description]
	 * @return [type]       [description]
	 */
	public function register_meta_box( $post ) {

		add_meta_box(
			$post->post_type . '-settings',
			esc_html__( 'Settings', 'ziploy' ),
			array( $this, 'render_meta_box'),
			$post->post_type,
			'normal',
			'high',
			array($post),
		);
	}


	public function render_meta_box( $post ) {

		include "metabox/post-type/{$post->post_type}/settings.php";
	}
	
}
