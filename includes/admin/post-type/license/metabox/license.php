<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$wp_post = $post;
$params  = wp_parse_args( $args['args']['meta'], array(
	'_certify_license_key' => ''
	'_certify_expiry_date',
	'_certify_activations'
)); ?>

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
				value=""
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
				id="certify-expiry-date"
				name="_certify_expiry_date"
				value=""
			>
		</div>
		<div class="span-6">
			<label for="certify-activations">
				<?php _e( 'Number of Activations', 'certify' ); ?>
			</label>
			<input
				type="number"
				id="certify-activations"
				name="_certify_activations"
				value=""
				min="1"
				step="1"
			>
		</div>
	</div>
</div>
