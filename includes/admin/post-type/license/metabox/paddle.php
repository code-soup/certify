<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

$software = new \WP_Query(['post_type' => 'software', 'posts_per_page' => 99 ] ); ?>

<div class="certify metabox-code-soup">
	<div class="row">
		<div class="span-6">
			<label for="certify-external-id">
				<?php _e( 'Product / Subscription Plan ID', 'certify' ); ?>
			</label>
			<input
				id="certify-external-id"
				type="number"
				name="_certify_external_id"
				value="<?php echo get_post_meta( $post->ID, '_certify_external_id', true ); ?>"
			>
		</div>
	
		<div class="span-6">
			<label for="certify-software">
				<?php _e( 'Software', 'certify' ); ?>
			</label>
			<select name="_certify_software">
				<option> - Not Selected - </option>
				<?php foreach ( $software->posts as $p )
				{
					printf(
						'<option value="%d"%s>%s</option>',
						$p->ID,
						selected( $p->ID, $post->post_parent, false ),
						$p->post_title
					);
				} ?>
			</select>
		</div>
	</div>
</div>