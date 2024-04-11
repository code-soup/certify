<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$wp_post = $post;
$meta    = $args['args']['meta']; ?>

<div class="certify metabox-code-soup">
	<div class="row">
	</div>
</div>

<?php wp_nonce_field( 'certify_save_post_meta', '_certify_nonce_cspm' );