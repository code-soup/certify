<?php

namespace CodeSoup\Certify\Admin;

// Exit if accessed directly
defined( 'WPINC' ) || die;

class Settings_Page {

    use \CodeSoup\Certify\Traits\HelpersTrait;

    // Main plugin instance.
    protected static $instance = null;

    private $tabs;

    private $options;

    public function __construct() {

        // Main plugin instance.
        $instance     = \CodeSoup\Certify\Init::get_instance();
        $hooker       = $instance->get_hooker();

        $hooker->add_action( 'admin_menu', $this );
        $hooker->add_action( 'admin_init', $this );
    }

    /**
     * Register new page
     */
    public function admin_menu()
    {
        add_submenu_page(
            'certify',
            'Settings',
            'Settings',
            'manage_certify',
            'certify-settings',
            array( $this, 'render_settings_page'),
        );
    }


    /**
     * Register settings sections and fields
     */
    public function admin_init()
    {
        $option_page = 'certify_settigns_page';
        $option_name = 'certify_settings';
        $options     = get_option( 'certify_settings' );

        register_setting( $option_page, $option_name );

        /**
         * Tabs
         */
        $this->tabs = array(
            array(
                'tab_id'      => 'general',
                'tab_title'   => 'General',
                'option_page' => $option_page,
                'option_name' => $option_name,
            ),
            array(
                'tab_id'      => 'email',
                'tab_title'   => 'Email',
                'option_page' => $option_page,
                'option_name' => $option_name,
            ),
            array(
                'tab_id'      => 'paddle',
                'tab_title'   => 'Paddle',
                'option_page' => $option_page,
                'option_name' => $option_name,
            ),
        );

        
        /**
         * Fields
         */
        foreach ( $this->tabs as $tab )
        {
            $fields = require_once "settings/fields/{$tab['tab_id']}.php";
            /**
             * Register section
             */
            add_settings_section(
                $tab['tab_id'],
                $tab['tab_title'],
                NULL,
                $tab['option_page'],
            );

            /**
             * Register fields to section
             */
            foreach ( $fields as $field )
            {
                $name           = str_replace( '-', '_', sanitize_title( $field['id'] ) );
                $field['name']  = $name;
                $field['value'] = isset( $options[ $tab['tab_id'] ][$name] )
                    ? $options[ $tab['tab_id'] ][$name]
                    : '';

                add_settings_field(
                    $field['id'],
                    $field['label'],
                    [$this, 'render_field'],
                    $tab['option_page'],
                    $tab['tab_id'],
                    array(
                        'field'   => $field,
                        'section' => $tab['tab_id'],
                    ),
                );
            }
        }
    }

    public function render_settings_page()
    {
        require 'settings/index.php';
    }

    public function render_field( $args )
    {
        require 'settings/form/index.php';
    }

    /**
     * Get single option value
     * @param  string $name    [description]
     * @param  string $section [description]
     * @return [type]          [description]
     */
    public static function get_option( $name = '', $section = 'general' )
    {
        // Empty
        $key     = str_replace('-', '_', $name);
        $options = empty(self::$options)
            ? get_option( 'certify_settings' )
            : self::$options;


        if ( ! empty($section) && ! empty($key) )
        {
            return isset( $options[ $section ][ $key ] )
                ? $options[ $section ][ $name ]
                : NULL;
        }

        return $options;
    }
}