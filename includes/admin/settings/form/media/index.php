<?php // Silence is golden

// Exit if accessed directly
defined( 'WPINC' ) || die;

wp_enqueue_media();

printf(
    '<input id="%s" name="certify_settings[%s][%s]" type="hidden" value="%s" />',
    $params['id'],
    $params['section'],
    $params['name'],
    $params['value']
); 

printf(
    '<div id="preview-%s" class="img-preview" style="max-width: 150px">%s</div>',
    $params['id'],
    empty($params['value']) ? '' : wp_get_attachment_image( $params['value'] )
); ?>

<button id="button-<?php echo $params['id']; ?>" type="button" class="button select-media">
	Select Logo
</button>

<script type="text/javascript">
    jQuery(document).ready(function($){
        var mediaUploader;
        $('#button-<?php echo $params['id']; ?>').on('click', function(e) {
            e.preventDefault();

            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media({
                title: 'Select Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#<?php echo $params['id']; ?>').val(attachment.id);
                $('#preview-<?php echo $params['id']; ?>').html('<img src="' + attachment.url + '" />');
            });

            mediaUploader.open();
        });
    });
</script>