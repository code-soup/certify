<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;
$meta = json_decode( $post->post_excerpt, true ); ?>

<div class="certify metabox-code-soup">
	<div class="row">
		<?php if ( empty($meta['activations']) ) {
			_e( 'No domain activations made so far', 'certify' );
		} ?>
	</div>
</div>

<?php wp_nonce_field( 'certify_save_post_meta', '_certify_nonce_cspm' );