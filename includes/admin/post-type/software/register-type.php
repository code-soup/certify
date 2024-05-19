<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

$labels = array(
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

return array(
	'can_export'           => false,
	'capability_type'      => 'certify',
	'delete_with_user'     => false,
	'description'          => _x('Software', 'Post type description', 'certify'),
	'exclude_from_search'  => true,
	'has_archive'          => false,
	'hierarchical'         => true,
	'labels'               => $labels,
	'menu_icon'            => 'dashicons-html',
	'menu_position'        => 93,
	'public'               => false,
	'publicly_queryable'   => false,
	'query_var'            => false,
	'show_in_admin_bar'    => false,
	'show_in_menu'         => 'certify',
	'show_in_nav_menus'    => false,
	'show_in_rest'         => false,
	'show_ui'              => true,
	'supports'             => array( 'title' ),
	'register_meta_box_cb' => array( $this, 'register_meta_box' ),
	'capabilities' => array(
        // Meta capabilities
        'edit_post'              => 'edit_certify',
        'read_post'              => 'read_certify',
        'delete_post'            => 'delete_certify',
        // Primitive capabilities used outside of map_meta_cap():
        'edit_posts'             => 'edit_certifies',
        'edit_others_posts'      => 'edit_others_certifies',
        'publish_posts'          => 'publish_certifies',
        'read_private_posts'     => 'read_private_certifies',
        // More capabilities if needed
    ),
);