<?php // Silence is golden

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $wp_settings_sections;

if ( empty($wp_settings_sections['certify_settigns_page']) )
    return;

// Load settings
$tab  = isset($_GET['tab'])
	? sanitize_title($_GET['tab'])
	: 'general'; ?>

<div class="wrap">
    <h2>Certify Settings</h2>
    <form method="post" action="options.php">

        <div id="tabs-certify-settings">
        	<nav class="nav-tab-wrapper">
        		<?php foreach ( $wp_settings_sections['certify_settigns_page'] as $id => $args ) {
        			printf(
        				'<a href="?page=certify-settings&tab=%s" class="nav-tab%s">%s</a>',
        				$args['id'],
        				( $tab === $args['id']) ? ' nav-tab-active' : '',
        				$args['title']
        			);
        		} ?>
        	</nav>

        	<div class="tab-content">
        		<?php
                settings_fields('certify_settings_page'); 
                do_settings_fields( 'certify_settigns_page', $tab ); ?>
        	</div>
        </div>
                    
        <?php submit_button(); ?>
    </form>
</div>
