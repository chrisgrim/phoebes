<?php
/**
 * Video Upload Functionality
 * Handles email-based access codes and video uploads for film submissions
 */

if (!defined('ABSPATH')) exit;

/**
 * Generate and send access code via email
 */
function phoebes_send_access_code() {
    check_ajax_referer('phoebes_upload_nonce', 'nonce');

    $email = sanitize_email($_POST['email']);

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Please enter a valid email address.']);
    }

    // Generate 6-digit code
    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store code with 1-hour expiration
    set_transient('phoebes_upload_code_' . md5($email), $code, HOUR_IN_SECONDS);

    // Send email
    $subject = "Your Upload Code - The Phoebe's Film Festival";
    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body style="margin: 0; padding: 0; background-color: #000000; font-family: Arial, sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #000000; padding: 40px 20px;">
            <tr>
                <td align="center">
                    <table width="100%" max-width="500" cellpadding="0" cellspacing="0" style="max-width: 500px;">
                        <tr>
                            <td align="center" style="padding-bottom: 30px;">
                                <h1 style="color: #BA9E5E; font-size: 28px; margin: 0; letter-spacing: 4px;">THE PHOEBES</h1>
                                <p style="color: #666666; font-size: 12px; margin: 5px 0 0 0; letter-spacing: 2px;">FILM FESTIVAL</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #1a1a1a; border-radius: 12px; padding: 40px; text-align: center;">
                                <p style="color: #ffffff; font-size: 16px; margin: 0 0 20px 0;">Your upload access code is:</p>
                                <div style="background-color: #000000; border: 2px solid #BA9E5E; border-radius: 8px; padding: 20px; margin: 0 0 20px 0;">
                                    <span style="color: #BA9E5E; font-size: 36px; font-weight: bold; letter-spacing: 8px;">' . $code . '</span>
                                </div>
                                <p style="color: #888888; font-size: 14px; margin: 0;">This code expires in 1 hour.</p>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding-top: 30px;">
                                <p style="color: #444444; font-size: 12px; margin: 0;">If you didn\'t request this code, you can ignore this email.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($email, $subject, $message, $headers)) {
        wp_send_json_success(['message' => 'Code sent! Check your email.']);
    } else {
        wp_send_json_error(['message' => 'Failed to send email. Please try again.']);
    }
}
add_action('wp_ajax_phoebes_send_code', 'phoebes_send_access_code');
add_action('wp_ajax_nopriv_phoebes_send_code', 'phoebes_send_access_code');

/**
 * Verify access code
 */
function phoebes_verify_access_code() {
    check_ajax_referer('phoebes_upload_nonce', 'nonce');

    $email = sanitize_email($_POST['email']);
    $code = sanitize_text_field($_POST['code']);

    $stored_code = get_transient('phoebes_upload_code_' . md5($email));

    if (!$stored_code) {
        wp_send_json_error(['message' => 'Code expired. Please request a new one.']);
    }

    if ($stored_code !== $code) {
        wp_send_json_error(['message' => 'Invalid code. Please try again.']);
    }

    // Generate session token for upload
    $token = wp_generate_password(32, false);
    set_transient('phoebes_upload_token_' . $token, $email, HOUR_IN_SECONDS);

    wp_send_json_success(['token' => $token]);
}
add_action('wp_ajax_phoebes_verify_code', 'phoebes_verify_access_code');
add_action('wp_ajax_nopriv_phoebes_verify_code', 'phoebes_verify_access_code');

/**
 * Handle video upload
 */
function phoebes_handle_video_upload() {
    header('Content-Type: application/json');

    // Verify nonce
    if (!check_ajax_referer('phoebes_upload_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed. Please refresh and try again.']);
    }

    // Verify upload token
    $token = sanitize_text_field($_POST['upload_token'] ?? '');
    $email = get_transient('phoebes_upload_token_' . $token);

    if (!$email) {
        wp_send_json_error(['message' => 'Session expired. Please verify your email again.']);
    }

    // Validate required fields
    if (empty($_POST['movie_title'])) {
        wp_send_json_error(['message' => 'Please enter a movie title.']);
    }

    if (empty($_FILES['video_file']['name'])) {
        wp_send_json_error(['message' => 'Please select a video file.']);
    }

    // Validate video file
    $video = $_FILES['video_file'];
    $allowed_video_types = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'];

    if (!in_array($video['type'], $allowed_video_types)) {
        wp_send_json_error(['message' => 'Invalid video format. Please use MP4, MOV, AVI, or WebM.']);
    }

    $max_video_size = 512 * 1024 * 1024; // 512MB
    if ($video['size'] > $max_video_size) {
        wp_send_json_error(['message' => 'Video file is too large. Maximum size is 512MB.']);
    }

    // Validate thumbnail if provided
    if (!empty($_FILES['thumbnail_file']['name'])) {
        $thumb = $_FILES['thumbnail_file'];
        $allowed_image_types = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($thumb['type'], $allowed_image_types)) {
            wp_send_json_error(['message' => 'Invalid thumbnail format. Please use JPG, PNG, or WebP.']);
        }

        $max_thumb_size = 5 * 1024 * 1024; // 5MB
        if ($thumb['size'] > $max_thumb_size) {
            wp_send_json_error(['message' => 'Thumbnail is too large. Maximum size is 5MB.']);
        }
    }

    // Load required files
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Create post
    $post_data = [
        'post_title'   => sanitize_text_field($_POST['movie_title']),
        'post_content' => sanitize_textarea_field($_POST['movie_description'] ?? ''),
        'post_status'  => 'pending',
        'post_type'    => 'post',
        'meta_input'   => [
            '_submission_email' => $email,
        ]
    ];

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['message' => 'Error creating submission. Please try again.']);
    }

    // Upload video
    $video_upload = wp_handle_upload($video, ['test_form' => false]);

    if (isset($video_upload['error'])) {
        wp_delete_post($post_id, true);
        wp_send_json_error(['message' => 'Video upload failed: ' . $video_upload['error']]);
    }

    // Create video attachment
    $video_attachment = [
        'post_mime_type' => $video_upload['type'],
        'post_title'     => sanitize_text_field($_POST['movie_title']),
        'post_content'   => '',
        'post_status'    => 'inherit'
    ];

    $video_id = wp_insert_attachment($video_attachment, $video_upload['file'], $post_id);
    update_post_meta($post_id, '_video_url', $video_upload['url']);

    // Upload thumbnail if provided
    if (!empty($_FILES['thumbnail_file']['name'])) {
        $thumb_upload = wp_handle_upload($_FILES['thumbnail_file'], ['test_form' => false]);

        if (!isset($thumb_upload['error'])) {
            $thumb_attachment = [
                'post_mime_type' => $thumb_upload['type'],
                'post_title'     => sanitize_text_field($_POST['movie_title']) . ' - Thumbnail',
                'post_content'   => '',
                'post_status'    => 'inherit'
            ];

            $thumb_id = wp_insert_attachment($thumb_attachment, $thumb_upload['file'], $post_id);
            $thumb_metadata = wp_generate_attachment_metadata($thumb_id, $thumb_upload['file']);
            wp_update_attachment_metadata($thumb_id, $thumb_metadata);
            set_post_thumbnail($post_id, $thumb_id);
        }
    }

    // Invalidate upload token (single use)
    delete_transient('phoebes_upload_token_' . $token);

    // Send notification email to admin
    $admin_email = get_option('admin_email');
    $title = sanitize_text_field($_POST['movie_title']);
    $review_url = admin_url("post.php?post={$post_id}&action=edit");

    $subject = "New Film Submission: " . $title;
    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body style="margin: 0; padding: 0; background-color: #000000; font-family: Arial, sans-serif;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #000000; padding: 40px 20px;">
            <tr>
                <td align="center">
                    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 500px;">
                        <tr>
                            <td align="center" style="padding-bottom: 30px;">
                                <h1 style="color: #BA9E5E; font-size: 28px; margin: 0; letter-spacing: 4px;">THE PHOEBES</h1>
                                <p style="color: #666666; font-size: 12px; margin: 5px 0 0 0; letter-spacing: 2px;">FILM FESTIVAL</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #1a1a1a; border-radius: 12px; padding: 40px;">
                                <h2 style="color: #BA9E5E; font-size: 20px; margin: 0 0 20px 0;">New Film Submission</h2>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="color: #888888; font-size: 14px; padding: 8px 0;">Title:</td>
                                        <td style="color: #ffffff; font-size: 14px; padding: 8px 0; text-align: right;">' . esc_html($title) . '</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #888888; font-size: 14px; padding: 8px 0;">Submitted by:</td>
                                        <td style="color: #ffffff; font-size: 14px; padding: 8px 0; text-align: right;">' . esc_html($email) . '</td>
                                    </tr>
                                </table>
                                <div style="margin-top: 30px; text-align: center;">
                                    <a href="' . esc_url($review_url) . '" style="display: inline-block; background-color: #BA9E5E; color: #000000; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold;">Review Submission</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>';

    $headers = ['Content-Type: text/html; charset=UTF-8'];
    wp_mail($admin_email, $subject, $message, $headers);

    wp_send_json_success([
        'message' => 'Your film has been submitted successfully! We\'ll review it and be in touch soon.'
    ]);
}
add_action('wp_ajax_phoebes_upload_video', 'phoebes_handle_video_upload');
add_action('wp_ajax_nopriv_phoebes_upload_video', 'phoebes_handle_video_upload');

/**
 * Increase upload limits for video uploads
 */
function phoebes_upload_size_limit($size) {
    return 536870912; // 512MB
}
add_filter('upload_size_limit', 'phoebes_upload_size_limit');
