<?php

// If this file is called directly, abort.
defined('WPINC') || die;

// Autoload all classes via composer.
require "vendor/autoload.php";

/**
 * Make main plugin class available via global function call.
 *
 * @since    1.0.0
 */
function certify() {

    return CodeSoup\Certify\Init::get_instance();
}

// Init plugin and make instance globally available
$certify = certify();
$certify->init();

$GLOBALS['certify'] = $certify;