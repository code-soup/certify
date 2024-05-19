<?php

// If this file is called directly, abort.
defined('WPINC') || die;

// Autoload all classes via composer.
require "vendor/autoload.php";

// Init plugin and make instance globally available
$plugin = \CodeSoup\Certify\Init::get_instance();
$plugin->init();