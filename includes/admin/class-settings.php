<?php

namespace CodeSoup\Certify\Admin;

// Exit if accessed directly
defined( 'WPINC' ) || die;

class Settings_Page {

    use \CodeSoup\Certify\Traits\HelpersTrait;

    // Main plugin instance.
    protected static $instance = null;

    // Assets loader class.
    protected $assets;

    private $tabs;

    public function __construct() {

        // Main plugin instance.
        $instance     = \CodeSoup\Certify\Init::get_instance();
        $hooker       = $instance->get_hooker();
        $this->assets = $instance->get_assets();

        $hooker->add_action( 'admin_menu', $this );
        $hooker->add_action( 'admin_init', $this );
    }

    public function admin_menu()
    {
        add_submenu_page(
            'certify',
            'Settings',
            'Settings',
            'manage_certify',
            'certify-settings',
            array( &$this, 'render_settings_page'),
        );
    }

    public function admin_init()
    {
        $option_group = 'certify_settigns_page';
        $option_name  = 'certify_settings';
        
        register_setting( $option_group, $option_name );

        /**
         * Tabs
         */
        $this->tabs = array(
            array(
                'tab_id'       => 'general',
                'tab_title'    => 'General',
                'option_group' => $option_group,
                'option_name'  => $option_name,
            ),
            array(
                'tab_id'       => 'email',
                'tab_title'    => 'Email',
                'option_group' => $option_group,
                'option_name'  => $option_name,
            ),
        );

        
        /**
         * Fields
         */
        foreach ( $this->tabs as $tab )
        {
            $fields = require_once "settings/tab-{$tab['tab_id']}.php";
            /**
             * Register section
             */
            add_settings_section(
                $tab['tab_id'],
                $tab['tab_title'],
                null,
                $tab['option_group'],
            );

            /**
             * Register fields to section
             */
            foreach ( $fields as $field )
            {
                add_settings_field(
                    $field['id'],
                    $field['label'],
                    [&$this, 'render_field'],
                    $tab['option_group'],
                    $tab['tab_id'],
                );
            }
        }
    }

    public function render_settings_page()
    {
        include 'settings/index.php';
    }

    public function render_field()
    {
        
    }
}