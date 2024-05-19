<?php

// Exit if accessed directly
defined( 'WPINC' ) || die; ?>

<div class="certify metabox-code-soup">
	<div class="row">
		<div class="span-6">
			<label for="certify-paddle-product-id">
				<?php _e( 'Product / Subscription Plan ID', 'certify' ); ?>
			</label>
			<input
				id="certify-external-id"
				type="number"
				name="_certify_external_id"
				value="<?php echo $class->the_value('_certify_external_id', $data ); ?>"
			>
		</div>

		<div class="span-6">
			<label for="certify-activations">
				<?php _e( 'Activations Limit', 'certify' ); ?>
			</label>
			<input
				type="number"
				id="certify-activations-limit"
				name="_certify_activations_limit"
				value="<?php echo $class->the_value('_certify_activations_limit', $data ); ?>"
				min="1"
				step="1"
			>
		</div>
	</div>
</div>