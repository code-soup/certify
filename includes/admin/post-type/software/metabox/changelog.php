<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$wp_post = $post;
$meta    = $args['args']['meta']; ?>

<div class="certify metabox-code-soup">

	<div class="row">
		<div class="span-12">
			<label class="sr-only" for="certify-changelog">
				<?php _e( 'Changelog', 'certify' ); ?>
			</label>

			<?php wp_editor(
				$wp_post->post_content,
				'certify-changelog',
				array(
					'media_buttons'    => false,
					'drag_drop_upload' => false,
					'textarea_name'    => '_certify_changelog',
					'teeny'            => false,
				)
			); ?>
		</div>
	</div>
</div>
