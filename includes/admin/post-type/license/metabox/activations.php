<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$activations = json_decode( html_entity_decode($post->post_excerpt), true );  ?>

<div class="certify metabox-code-soup">
	<div class="row">
		<div class="span-12">
			<?php if ( empty($activations) ) {
				_e( 'No domain activations made so far', 'certify' );
			} 

			foreach ( $activations as $active ) {

				$date = \DateTime::createFromFormat('U', $active['time'] );

				printf(
					'<p><b>%s</b> <time>%s</time></p><hr>',
					$active['host'],
					$date->format('d. M Y. H:i')
				);
			} ?>
		</div>
	</div>
</div>

<?php wp_nonce_field( 'certify_save_post_meta', '_certify_nonce_cspm' );