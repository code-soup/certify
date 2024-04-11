<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$wp_post = $post;
$meta    = $args['args']['meta']; ?>

<div class="certify metabox-code-soup">
	<div class="row">
		<div class="span-6">
			<label for="certify-paddle-product-id">
				<?php _e( 'Product ID', 'certify' ); ?>
			</label>
			<input
				id="certify-paddle-product-id"
				type="number"
				name="_certify_paddle_product_id"
				value="<?php echo $meta['_certify_paddle_product_id'][0]; ?>"
			>
		</div>
	
		<div class="span-6">
			<label for="certify-author-name">
				<?php _e( 'Plan ID', 'certify' ); ?>
			</label>
			<input
				id="certify-paddle-plan-id"
				type="number"
				name="_certify_paddle_plan_id"
				value="<?php echo $meta['_certify_paddle_plan_id'][0]; ?>"
			>
		</div>
	</div>
</div>