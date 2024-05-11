<?php

// Exit if accessed directly
defined( 'WPINC' ) || die; ?>

<div class="certify metabox-code-soup">
	<div class="row">
		<div class="span-12">
			<label for="certify-license-key">
				<?php _e( 'License Key', 'certify' ); ?>
			</label>
			<input
				type="text"
				id="certify-license-key"
				name="_certify_license_key"
				value="<?php echo $post->post_name; ?>"
				readonly
			>
		</div>
	</div>

	<div class="row">
		<div class="span-6">
			<label for="certify-expiry-date">
				<?php _e( 'Expiry Date', 'certify' ); ?>
			</label>
			<input
				type="date"
				id="certify-next-bill-date"
				name="_certify_next_bill_date"
				value="<?php echo get_post_meta( $post->ID, '_certify_next_bill_date', true ); ?>"
			>
		</div>
		<div class="span-6">
			<label for="certify-activations">
				<?php _e( 'Max Allowed Activations', 'certify' ); ?>
			</label>
			<input
				type="number"
				id="certify-activations-limit"
				name="_certify_activations_limit"
				value="<?php echo max(1, intval( get_post_meta( $post->ID, '_certify_activations_limit', true ) )); ?>"
				min="1"
				step="1"
			>
		</div>
	</div>
</div>
