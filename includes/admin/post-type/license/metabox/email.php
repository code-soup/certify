<?php

// Exit if accessed directly
defined( 'WPINC' ) || die;

$software = new \WP_Query(['post_type' => 'software', 'posts_per_page' => 99 ] ); ?>

<div class="certify metabox-code-soup">
	<div class="row">
		<div class="span-12">
			<p>Re-send License email to customer email.</p>
			<p>
				<button id="license-resend" type="button" class="button button-primary">Re-send License Email</button>
			</p>
		</div>
	</div>
</div>