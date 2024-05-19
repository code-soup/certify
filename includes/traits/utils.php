<?php

namespace CodeSoup\Certify\Traits;

// Exit if accessed directly
defined( 'WPINC' ) || die;

/**
 * Helper methods
 */
trait UtilsTrait {

    private function get_value( $key, $array = [], $default = '' ) {

        $return = $default;

        if ( isset($array[ $key ]) )
        {
            $return = $array[ $key ];
        }

        return $return;
    }

    private function the_value( $key, $array = [], $default = '' ) {

        printf( $this->get_value( $key, $array, $default ) );
    }
}
