<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$wp_post     = $post;
$post_id     = $post->ID;
$status      = get_post_meta( $post_id, '_ziploy_status', true ); 
$destination = get_post_meta( $post_id, '_ziploy_destination', true ); ?>

<div class="certify">

	<div class="row">
		<div class="span-12">
			<label for="ziploy-status">
				<?php _e('Status', 'ziploy'); ?>
			</label>
			<select name="_ziploy_status" id="ziploy-status" class="full-width">
				<option value="active" <?php selected($status, 'active'); ?>>
					<?php _e('Active', 'ziploy'); ?>
				</option>
				<option value="paused" <?php selected($status, 'paused'); ?>>
					<?php _e('Paused', 'ziploy'); ?>
				</option>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="span-12">
			<label for="certify-description">
				<?php _e( 'Product Description', 'ziploy' ); ?>
			</label>
			<textarea id="certify-description" name="_certify_description"><?php echo $post->post_content; ?></textarea>
			<small class="description description-block">
				<?php _e( 'Short product description', 'certify' ); ?>
			</small>
		</div>
	</div>

</div>
