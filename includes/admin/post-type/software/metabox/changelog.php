<?php

// Exit if accessed directly
defined( 'WPINC' ) || die; ?>

<div class="certify metabox-code-soup">

	<div class="row">
		<div class="span-12">
			<label class="screen-reader-text" for="certify-changelog">
				<?php _e( 'Changelog', 'certify' ); ?>
			</label>

			<textarea
				id="certify-changelog"
				name="_certify_changelog"
				rows="20"><?php echo $class->the_value('_certify_changelog', $data ); ?></textarea>
		</div>
	</div>
</div>
