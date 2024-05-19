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

        if ( ! empty($administrator) ) {

            $caps = array(
                'edit_certify',
                'read_certify',
                'manage_certify',
                'delete_certify',
                'edit_certifies',
                'edit_others_certifies',
                'publish_certifies',
                'read_private_certifies',
            );

            foreach( $caps as $cap ) {
                $administrator->add_cap($cap, true);
            }
        }
    }
}
