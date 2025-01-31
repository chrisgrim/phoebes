jQuery(document).ready(function($) {
    $('#video-upload-form').on('submit', function(e) {
        e.preventDefault();
        
        // Debug: Log form data
        console.log('Form data:', {
            title: $('#movie_title').val(),
            video: $('#video_file')[0].files[0],
            thumbnail: $('#thumbnail_file')[0].files[0],
            credits: $('#movie_credits').val(),
            password: $('input[name="access_password"]').val(),
            nonce: uploadVars.nonce
        });

        const videoFile = $('#video_file')[0].files[0];
        if (videoFile && videoFile.size > 536870912) {
            alert('Video file is too large. Maximum size is 512MB.');
            return;
        }

        const thumbnailFile = $('#thumbnail_file')[0].files[0];
        if (thumbnailFile && thumbnailFile.size > 5242880) {
            alert('Thumbnail file is too large. Maximum size is 5MB.');
            return;
        }

        var formData = new FormData(this);
        formData.append('action', 'handle_video_upload');
        formData.append('video_upload_nonce', uploadVars.nonce);

        // Debug: Log FormData entries
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        $('#upload-progress').removeClass('hidden');
        
        $.ajax({
            url: uploadVars.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('#progress-bar').width(percentComplete + '%').text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                console.log('Raw response:', response);
                
                // Handle different response formats
                let parsedResponse = response;
                if (typeof response === 'string') {
                    try {
                        parsedResponse = JSON.parse(response);
                    } catch (e) {
                        console.log('Failed to parse response:', e);
                    }
                }

                if (parsedResponse && parsedResponse.success) {
                    alert(parsedResponse.data ? parsedResponse.data.message : 'Upload successful!');
                    $('#video-upload-form')[0].reset();
                } else {
                    alert(parsedResponse && parsedResponse.data ? 
                          parsedResponse.data.message : 
                          'Upload failed. Please try again.');
                }
                
                $('#progress-bar').width('0%').text('0%');
                $('#upload-progress').addClass('hidden');
            },
            error: function(xhr, status, error) {
                console.log('Full error details:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                alert('Error uploading video. Please try again.');
                $('#progress-bar').width('0%').text('0%');
                $('#upload-progress').addClass('hidden');
            }
        });
    });
});