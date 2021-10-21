<?php

/**
 * @author            Code Soup
 * @copyright         2021 Code Soup
 * @license           GPL-3.0+
 * @link              https://www.codesoup.co
 * @since             1.0.0
 * 
 * @wordpress-plugin
 * Plugin Name:       Certify
 * Plugin URI:        https://github.com/code-soup/certify
 * Description:       WordPress Plugin for managing license keys sold through paddle.com
 * Version:           1.0.0
 * Author:            Code Soup
 * Author URI:        https://www.codesoup.co
 * Contributors:      bobz
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wppb
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

/**
 * NOTE: Activation hooks need to be inside index.php file or it might not work properly
 * It can fail without error, WordPress is silently failing in case of error
 *
 * 
 * The code that runs during plugin activation.
 * - includes/Activator.php
 */
register_activation_hook( __FILE__, function() {

    // On activate do this
    CS\Certify\Activator::activate();
});



/**
 * The code that runs during plugin deactivation.
 * - includes/Deactivator.php
 */
register_deactivation_hook( __FILE__, function () {
    
    // On deactivate do that
    CS\Certify\Deactivator::deactivate();
});


// Run plugin, run
include "run.php";