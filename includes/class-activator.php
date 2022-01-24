<?php

namespace CodeSoup\Certify;

// Exit if accessed directly.
defined( 'WPINC' ) || die;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 */
class Activator {

    public static function activate() {

        $administrator = get_role('administrator');
        $administrator->add_cap('manage_certify');

        error_log( print_r($administrator, true) );
    }
}
