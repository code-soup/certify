<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

global $post;

$wp_post = $post;
$meta    = $args['args']['meta']; ?>

<div class="certify metabox-code-soup">
	<div class="row">
		<div class="span-6">
			<label for="certify-author-name">
				<?php _e( 'Author Name', 'certify' ); ?>
			</label>
			<input
				type="text"
				id="certify-author-name"
				name="_certify_author_name"
				value="<?php echo $meta['_certify_author_name'][0]; ?>"
			>
		</div>
	
		<div class="span-6">
			<label for="certify-author-name">
				<?php _e( 'Author Profile URL', 'certify' ); ?>
			</label>
			<input
				type="url"
				id="certify-author-profile-url"
				name="_certify_author_profile_url"
				value="<?php echo $meta['_certify_author_profile_url'][0]; ?>"
			>
		</div>
	</div>

	<div class="row">
		<div class="span-3">
			<label for="certify-plugin-version">
				<?php _e( 'Plugin Version', 'certify' ); ?>
			</label>
			<input
				type="text"
				id="certify-plugin-version"
				name="_certify_plugin_version"
				value="<?php echo $meta['_certify_plugin_version'][0]; ?>"
			>
		</div>

		<div class="span-3">
			<label for="certify-php-required">
				<?php _e( 'Required PHP version', 'certify' ); ?>
			</label>
			<input
				type="text"
				id="certify-php-required"
				name="_certify_php_required"
				value="<?php echo $meta['_certify_php_required'][0]; ?>"
			>
		</div>

		<div class="span-3">
			<label for="certify-wp-required">
				<?php _e( 'Required WP version', 'certify' ); ?>
			</label>
			<input
				type="text"
				id="certify-wp-required"
				name="_certify_wp_required"
				value="<?php echo $meta['_certify_wp_required'][0]; ?>"
			>
		</div>
	
		<div class="span-3">
			<label for="certify-wp-tested">
				<?php _e( 'Tested WP version', 'certify' ); ?>
			</label>
			<input
				id="certify-wp-tested"
				type="text"
				name="_certify_wp_tested"
				value="<?php echo $meta['_certify_wp_tested'][0]; ?>">
		</div>
	</div>

	<div class="row">
		<div class="span-12">
			<label for="certify-description">
				<?php _e( 'Product Description', 'certify' ); ?>
			</label>
			<textarea
				id="certify-description"
				name="_certify_description"
				rows="5"><?php echo $wp_post->post_excerpt; ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="span-12">
			<label for="certify-installation-instructions">
				<?php _e( 'Installation instructions', 'certify' ); ?>
			</label>
			<textarea
				id="certify-installation-instructions"
				name="_certify_installation_instructions"
				rows="3"><?php echo $meta['_certify_installation_instructions'][0]; ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="span-12">
			<label for="certify-plugin-download-url">
				<?php _e( 'Plugin Download URL', 'certify' ); ?>
			</label>
			<input
				type="url"
				id="certify-plugin-download-url"
				name="_certify_plugin_download_url"
				value="<?php echo $meta['_certify_plugin_download_url'][0]; ?>">
		</div>
	</div>
</div>

<?php wp_nonce_field( 'certify_save_post_meta', '_certify_nonce_cspm' );