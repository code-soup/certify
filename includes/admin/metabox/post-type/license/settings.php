<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$wp_post     = $post;
$post_id     = $post->ID;
$status      = get_post_meta( $post_id, '_ziploy_status', true ); 
$destination = get_post_meta( $post_id, '_ziploy_destination', true ); ?>

<div class="ziploy">

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
			<label for="ziploy-repository">
				<?php _e( 'Repository URL', 'ziploy' ); ?>
			</label>
			<input
				type="url"
				id="ziploy-repository"
				value="<?php echo get_post_meta( $post_id, '_ziploy_repository', true ); ?>"
				name="_ziploy_repository"
				class="large-text"
				placeholder="Eg: https://github.com/code-soup/ziploy" />
			<small class="description description-block">
				<?php _e( 'Source repository where your code is hosted right now', 'ziploy' ); ?>
			</small>
		</div>
	</div>

	<div class="row">
		<div class="span-12">
			<label for="ziploy-destination">
				<?php _e( 'Destination', 'ziploy' ); ?>
			</label>
			<div class="inner-row row-ziploy-destination">
				<div class="span-button">
					<button
						type="button"
						class="button button-primary"
						data-ziploy="toggle-modal">
						<?php _e( 'Select Folder', 'ziploy' ); ?>
					</button>
				</div>
				<div class="span-input">
					<div class="path-preview">
						<?php if ( $destination )
						{
							echo $destination;
						} else {
							_ex( 'Not Selected', 'Ziployment path not selected', 'ziploy' );
						} ?>
					</div>
				</div>
			</div>
			<small class="description description-block">
				<?php _e( 'Your code will be ziployed into this folder', 'ziploy' ); ?>
			</small>

			<input				
				id="ziploy-destination"
				name="_ziploy_destination"
				type="hidden"
				value="<?php echo $destination; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="span-12">
			<label for="ziploy-secret-key">
				<?php _e( 'Ziploy Secret Key', 'ziploy' ); ?>
			</label>
			<div class="inner-wrap row-ziploy-secret-key">
				<input
					id="ziploy-secret-key"
					type="text"
					name="_ziploy_secret_key"
					value="<?php echo $wp_post->post_password; ?>"
					class="large-text disabled"
					onClick="this.setSelectionRange(0, this.value.length)"
					placeholder="<?php _e( 'Key will be generated here after you save ziployment for the first time.', 'ziploy' ); ?>"
					readonly />

				<a href="#"
					class="icon-copy copy-to-clipboard"
					data-ziploy="copy-secret"
					title="<?php _e('Copy to clipboard', 'ziploy'); ?>" 
				>
					<span class="label">
						<?php _e('Copy', 'ziploy'); ?>
					</span>
				</a>

				<a href="#"
					class="icon-reset"
					data-ziploy="reset-secret"
					title="<?php _e('Regenerate secret key', 'ziploy'); ?>"
				>
					<span class="label">
						<?php _e('Regenerate', 'ziploy'); ?>
					</span>
				</a>
			</div>

			<small class="description description-block">
				<?php _e( 'Paste this super secret key in your repository profile. This can be different based on where your code is hosted. Please refer to <a href="https://www.ziploy.com/documentation">documentation article</a> for instructions.', 'ziploy' ); ?>
			</small>
		</div>
	</div>


</div>
