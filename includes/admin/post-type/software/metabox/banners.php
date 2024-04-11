<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$wp_post = $post;
$meta    = $args['args']['meta']; ?>

<div class="certify metabox-code-soup">

	<div class="row">
		<div class="span-12">
			<label for="certify-banner-low">
				<?php _e( 'Low Resolution', 'certify' ); ?>
			</label>
			<input
				type="url"
				id="certify-banner-low"
				name="_certify_banner_low"
				value="<?php echo $meta['_certify_banner_low'][0]; ?>"
			>
			<small class="description description-block">
				Image in jpeg or png format, 772x250px
			</small>
		</div>
	</div>

	<div class="row">
		<div class="span-12">
			<label for="certify-banner-high">
				<?php _e( 'High Resolution', 'certify' ); ?>
			</label>
			<input
				type="url"
				id="certify-banner-high"
				name="_certify_banner_high"
				value="<?php echo $meta['_certify_banner_high'][0]; ?>"
			>
			<small class="description description-block">
				Image in jpeg or png format, 1544x500px
			</small>
		</div>
	</div>
</div>
