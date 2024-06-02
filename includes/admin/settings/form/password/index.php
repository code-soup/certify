<?php // Silence is golden

// Exit if accessed directly
defined( 'WPINC' ) || die;

printf(
    '<input id="%s" name="certify_settings[%s][%s]" type="password" value="%s" class="regular-text" />',
    $params['id'],
    $params['section'],
    $params['name'],
    $params['value']
);