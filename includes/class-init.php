<?php

namespace CodeSoup\Certify;

// Exit if accessed directly
defined( 'WPINC' ) || die;

/**
 * @file
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
final class Init {

	/**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass. You'll
     * see how this works in a moment.
     */
    private static $instances = [];


	/**
	 * Define plugin constants
	 *
	 * @var array
	 */
	private $constants = array(
		'CS_MIN_WP_VERSION_SUPPORT_TERMS' => '6.0',
		'CS_MIN_WP_VERSION'               => '6.0',
		'CS_MIN_PHP_VERSION'              => '8.1',
		'CS_MIN_MYSQL_VERSION'            => '5.0.0',
		'CS_PLUGIN_PREFIX'                => 'CERTIFY',
		'CS_PLUGIN_NAME'                  => 'Certify',
		'CS_PLUGIN_VERSION'               => '1.0.0',
	);


	/**
	 * The hooker that's responsible for maintaining and registering all hooks that
	 * are loaded with the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hooker    $hooker    Maintains and registers all hooks for the plugin.
	 */
	protected $hooker;


	/**
	 * The assets loader is responsible for all plugin assets, Js, CSS and images.
	 * Loads appropriate hashed files based on current environment
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hooker    $hooker    Maintains and registers all hooks for the plugin.
	 */
	protected $assets;


	/**
	 * Make constructor protected, to prevent direct instantiation
	 *
	 * @since    1.0.0
	 */
	protected function __construct() {}


	/**
	 * Main Instance.
	 *
	 * Ensures only one instance is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 * @return Main instance.
	 */
	public static function get_instance()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        $instance = self::$instances[$cls];

        return $instance;
    }

	
	/**
     * Singletons should not be cloneable.
     */
    private function __clone()
    {
    	throw new \Exception('Cannot clone ' . __CLASS__);
    }

    
    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize ' . __CLASS__);
    }



	/**
	 * Run everything on init
	 * @return void
	 */
	public function init() {

		// Define Constants.
		$this->set_constants();

		// Hooks loader.
		$this->hooker = new Hooker();

		// Assets loader.
		$this->assets = new Assets();

		// Internationalizations.
		new I18n();

		// WP Admin related stuff.
		new Admin\Init();

		// Public related stuff.
		// new Frontend\Init();

		// REST API handler
		new RestApi\Init();

		// Run all hooks
		$this->run();
	}


	/**
	 * Define Constants.
	 */
	private function set_constants() {

		$constants = $this->get_constants();

		foreach ( $constants as $define => $value )
		{
			if ( ! defined($define) )
			{
				define( $define, $value );
			}
		}
	}


	/**
	 * Run the hooker to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->hooker->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hooker    Orchestrates the hooks of the plugin.
	 */
	public function get_hooker() {
		return $this->hooker;
	}

	/**
	 * The reference to the class that orchestrates the assets with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hooker    Orchestrates the assets of the plugin.
	 */
	public function get_assets() {
		return $this->assets;
	}

	/**
	 * The reference to the class that orchestrates the assets with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hooker    Orchestrates the assets of the plugin.
	 */
	public function get_constants() {
		return $this->constants;
	}
}
