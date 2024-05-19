<?php // Silence is golden

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $wp_settings_sections;

if ( empty($wp_settings_sections['certify_settigns_page']) )
    return;

$tab = empty($_GET['tab'])
    ? 'general'
    : sanitize_title($_GET['tab']); ?>

<div class="wrap">
    <h2>Certify Settings</h2>
    <form action="options.php" method="post">

        <div id="tabs-certify-settings">
        	<nav class="nav-tab-wrapper">
        		<?php foreach ( $wp_settings_sections['certify_settigns_page'] as $id => $args ) {
        			printf(
        				'<a href="#tab-%s" class="nav-tab%s">%s</a>',
        				$args['id'],
        				( $tab === $args['id']) ? ' nav-tab-active' : '',
        				$args['title']
        			);
        		} ?>
        	</nav>
            <div class="tab-content">
                <?php

                settings_fields('certify_settigns_page');

                foreach ( $wp_settings_sections['certify_settigns_page'] as $id => $args )
                {
                    printf(
                        '<div id="tab-%s" class="tab%s">',
                        $args['id'],
                        ( $tab === $args['id']) ? ' tab-active' : '',
                    );
                    
                    echo '<table class="form-table" role="presentation">';
                    do_settings_fields( 'certify_settigns_page', $args['id'] );
                    echo '</table></div>';
                } ?>
            </div>
        </div>
                    
        <?php submit_button(); ?>
    </form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            $('.tab-active').removeClass('tab-active');
            $('.nav-tab-active').removeClass('nav-tab-active');

            e.target.classList.add('nav-tab-active');
            document.querySelector(e.target.hash).classList.add('tab-active');

            // const queryParams = new URLSearchParams(window.location.search);
            // queryParams.set('tab', e.target.hash.replace('#tab-'));
            // const newUrl = `${window.location.origin}${window.location.pathname}?${queryParams.toString()}`;
            // window.history.pushState({}, '', newUrl);
        });
    });
</script>
<style type="text/css">
    .tab:not(.tab-active) {display: none;}
</style>