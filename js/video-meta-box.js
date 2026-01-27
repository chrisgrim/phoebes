/**
 * Video Meta Box - Media Uploader
 * Handles video selection in post editor meta box
 */
jQuery(document).ready(function($) {
    $('#upload_video_button').click(function(e) {
        e.preventDefault();
        
        var mediaUploader = wp.media({
            title: 'Choose Video',
            button: {
                text: 'Choose Video'
            },
            library: {
                type: 'video'
            },
            multiple: false
        }).on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#video_url').val(attachment.url);
            $('#video_message').text('Video Attached');
        }).open();
    });
});

