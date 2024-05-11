<?php

// Exit if accessed directly
defined( 'WPINC' ) || die; ?>

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
				value="<?php echo $class->the_value('_certify_banner_low', $data ); ?>"
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
				value="<?php echo $class->the_value('_certify_banner_high', $data ); ?>"
			>
			<small class="description description-block">
				Image in jpeg or png format, 1544x500px
			</small>
		</div>
	</div>
</div>
