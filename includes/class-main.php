<?php

namespace CodeSoup\Certify;

// Exit if accessed directly
defined( 'WPINC' ) || die;

/**
 * @file
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Main {

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
	public function __construct( $pos) {

		// Main plugin instance.
		$instance     = \CodeSoup\Certify\Init::get_instance();
		$hooker       = $instance->get_hooker();
		$this->assets = $instance->get_assets();
	}
}
