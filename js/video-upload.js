jQuery(document).ready(function($) {
    const config = window.phoebesTownload || {};
    let userEmail = '';
    let uploadToken = '';

    // Helper: Format file size
    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        if (bytes < 1024 * 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        return (bytes / (1024 * 1024 * 1024)).toFixed(2) + ' GB';
    }

    // Helper: Show error
    function showError(message) {
        $('#error-message').text(message);
        $('#global-error').removeClass('hidden');
        setTimeout(() => $('#global-error').addClass('hidden'), 5000);
    }

    // Helper: Set button loading state
    function setLoading(btn, loading) {
        if (loading) {
            btn.data('original-text', btn.text());
            btn.text('Please wait...').prop('disabled', true);
        } else {
            btn.text(btn.data('original-text')).prop('disabled', false);
        }
    }

    // Helper: Switch steps
    function showStep(stepId) {
        $('.upload-step').addClass('hidden');
        $('#' + stepId).removeClass('hidden');
    }

    // Step 1: Send email code
    $('#email-form').on('submit', function(e) {
        e.preventDefault();
        userEmail = $('#email').val().trim();

        if (!userEmail) {
            showError('Please enter your email address.');
            return;
        }

        const btn = $('#send-code-btn');
        setLoading(btn, true);

        $.ajax({
            url: config.ajaxurl,
            type: 'POST',
            data: {
                action: 'phoebes_send_code',
                nonce: config.nonce,
                email: userEmail
            },
            success: function(response) {
                setLoading(btn, false);
                if (response.success) {
                    showStep('step-code');
                    $('#code').focus();
                } else {
                    showError(response.data?.message || 'Failed to send code.');
                }
            },
            error: function() {
                setLoading(btn, false);
                showError('Network error. Please try again.');
            }
        });
    });

    // Step 1b: Verify code
    $('#code-form').on('submit', function(e) {
        e.preventDefault();
        const code = $('#code').val().trim();

        if (!code || code.length !== 6) {
            showError('Please enter the 6-digit code.');
            return;
        }

        const btn = $('#verify-code-btn');
        setLoading(btn, true);

        $.ajax({
            url: config.ajaxurl,
            type: 'POST',
            data: {
                action: 'phoebes_verify_code',
                nonce: config.nonce,
                email: userEmail,
                code: code
            },
            success: function(response) {
                setLoading(btn, false);
                if (response.success) {
                    uploadToken = response.data.token;
                    $('#upload_token').val(uploadToken);
                    showStep('step-upload');
                } else {
                    showError(response.data?.message || 'Invalid code.');
                }
            },
            error: function() {
                setLoading(btn, false);
                showError('Network error. Please try again.');
            }
        });
    });

    // Back to email
    $('#back-to-email').on('click', function() {
        showStep('step-email');
        $('#code').val('');
    });

    // Video dropzone
    const videoDropzone = $('#video-dropzone');
    const videoInput = $('#video_file');

    videoDropzone.on('click', () => videoInput.click());

    videoDropzone.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('border-[#BA9E5E]');
    });

    videoDropzone.on('dragleave drop', function(e) {
        e.preventDefault();
        $(this).removeClass('border-[#BA9E5E]');
    });

    videoDropzone.on('drop', function(e) {
        const files = e.originalEvent.dataTransfer.files;
        if (files.length) {
            videoInput[0].files = files;
            videoInput.trigger('change');
        }
    });

    videoInput.on('change', function() {
        const file = this.files[0];
        $('#video-error').addClass('hidden');

        if (!file) return;

        // Validate type
        const allowedTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
        if (!allowedTypes.includes(file.type)) {
            $('#video-error').text('Invalid format. Please use MP4, MOV, AVI, or WebM.').removeClass('hidden');
            this.value = '';
            return;
        }

        // Validate size
        if (file.size > config.maxVideoSize) {
            $('#video-error').text('File too large. Maximum size is 512MB.').removeClass('hidden');
            this.value = '';
            return;
        }

        // Show selected state
        $('#video-filename').text(file.name);
        $('#video-filesize').text(formatSize(file.size));
        $('#video-dropzone-content').addClass('hidden');
        $('#video-selected').removeClass('hidden');
    });

    $('#remove-video').on('click', function(e) {
        e.stopPropagation();
        videoInput.val('');
        $('#video-dropzone-content').removeClass('hidden');
        $('#video-selected').addClass('hidden');
    });

    // Thumbnail dropzone
    const thumbDropzone = $('#thumb-dropzone');
    const thumbInput = $('#thumbnail_file');

    thumbDropzone.on('click', () => thumbInput.click());

    thumbDropzone.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('border-[#BA9E5E]');
    });

    thumbDropzone.on('dragleave drop', function(e) {
        e.preventDefault();
        $(this).removeClass('border-[#BA9E5E]');
    });

    thumbDropzone.on('drop', function(e) {
        const files = e.originalEvent.dataTransfer.files;
        if (files.length) {
            thumbInput[0].files = files;
            thumbInput.trigger('change');
        }
    });

    thumbInput.on('change', function() {
        const file = this.files[0];

        if (!file) return;

        // Validate type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showError('Invalid thumbnail format. Please use JPG, PNG, or WebP.');
            this.value = '';
            return;
        }

        // Validate size
        if (file.size > config.maxThumbSize) {
            showError('Thumbnail too large. Maximum size is 5MB.');
            this.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#thumb-preview').attr('src', e.target.result);
            $('#thumb-filename').text(file.name);
            $('#thumb-dropzone-content').addClass('hidden');
            $('#thumb-selected').removeClass('hidden');
        };
        reader.readAsDataURL(file);
    });

    $('#remove-thumb').on('click', function(e) {
        e.stopPropagation();
        thumbInput.val('');
        $('#thumb-dropzone-content').removeClass('hidden');
        $('#thumb-selected').addClass('hidden');
    });

    // Upload form
    $('#upload-form').on('submit', function(e) {
        e.preventDefault();

        // Validate
        if (!$('#movie_title').val().trim()) {
            showError('Please enter a film title.');
            return;
        }

        if (!videoInput[0].files[0]) {
            showError('Please select a video file.');
            return;
        }

        const formData = new FormData(this);
        formData.append('action', 'phoebes_upload_video');
        formData.append('nonce', config.nonce);

        const btn = $('#upload-btn');
        btn.prop('disabled', true).text('Uploading...');
        $('#upload-progress').removeClass('hidden');

        $.ajax({
            url: config.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        $('#progress-bar').css('width', percent + '%');
                        $('#progress-percent').text(percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    showStep('step-success');
                } else {
                    btn.prop('disabled', false).text('Submit Film');
                    $('#upload-progress').addClass('hidden');
                    $('#progress-bar').css('width', '0%');
                    $('#progress-percent').text('0%');
                    showError(response.data?.message || 'Upload failed. Please try again.');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).text('Submit Film');
                $('#upload-progress').addClass('hidden');
                $('#progress-bar').css('width', '0%');
                $('#progress-percent').text('0%');

                let message = 'Upload failed. ';
                if (xhr.status === 413) {
                    message += 'File too large for server.';
                } else if (xhr.status === 0) {
                    message += 'Connection lost. Please check your internet.';
                } else {
                    message += 'Please try again.';
                }
                showError(message);
            }
        });
    });
});
