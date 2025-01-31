<?php
/**
 * Template Name: Video Upload Page
 */

// Get password from wp-config.php
$upload_password = defined('UPLOAD_ACCESS_PASSWORD') ? UPLOAD_ACCESS_PASSWORD : '';
$is_authorized = false;

// Check if password is submitted and correct
if (isset($_POST['access_password']) && $_POST['access_password'] === $upload_password) {
    $is_authorized = true;
}

get_header();
?>

<main id="primary" class="site-main min-h-screen bg-black text-white">
    <div class="container mx-auto px-4 py-12 max-w-2xl">
        <h1 class="text-4xl font-bold mb-12 text-center"><?php the_title(); ?></h1>

        <?php if (!$is_authorized): ?>
            <form method="post" class="space-y-8">
                <div class="form-group space-y-2">
                    <label for="access_password" class="block text-sm font-medium">Enter Password to Upload</label>
                    <input type="text" id="access_password" name="access_password" required
                        class="w-full px-4 py-2 bg-white border border-gray-600 rounded-lg focus:border-[#BA9E5E] focus:ring-1 focus:ring-[#BA9E5E] focus:outline-none text-white placeholder-gray-400">
                </div>
                <button type="submit" 
                    class="w-full bg-[#BA9E5E] text-white py-3 px-6 rounded-lg hover:bg-[#A18543] transition-colors duration-200">
                    Access Upload Form
                </button>
            </form>
        <?php else: ?>
            <form id="video-upload-form" class="space-y-8" enctype="multipart/form-data">
                <div class="form-group space-y-2">
                    <label for="movie_title" class="block text-sm font-medium">Movie Title</label>
                    <input type="text" id="movie_title" name="movie_title" required
                        class="w-full px-4 py-2 bg-white border border-gray-600 rounded-lg focus:border-[#BA9E5E] focus:ring-1 focus:ring-[#BA9E5E] focus:outline-none text-white placeholder-gray-400">
                </div>

                <div class="form-group space-y-2">
                    <label for="video_file" class="block text-sm font-medium">Video File</label>
                    <input type="file" id="video_file" name="video_file" accept="video/mp4,video/quicktime,video/x-msvideo" required
                        class="block w-full text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-[#BA9E5E] file:text-white hover:file:bg-[#A18543] cursor-pointer">
                </div>

                <div class="form-group space-y-2">
                    <label for="thumbnail_file" class="block text-sm font-medium">Thumbnail Image (optional)</label>
                    <input type="file" id="thumbnail_file" name="thumbnail_file" accept="image/jpeg,image/png,image/gif"
                        class="block w-full text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-[#BA9E5E] file:text-white hover:file:bg-[#A18543] cursor-pointer">
                    <small class="block mt-1 text-gray-400">Recommended size: 1920x1080px. Will use video frame if not provided.</small>
                </div>

                <div class="form-group space-y-2">
                    <label for="movie_credits" class="block text-sm font-medium">Movie Description and Credits</label>
                    <textarea id="movie_credits" name="movie_credits" required rows="6"
                        class="w-full px-4 py-2 bg-white border border-gray-600 rounded-lg focus:border-[#BA9E5E] focus:ring-1 focus:ring-[#BA9E5E] focus:outline-none text-white placeholder-gray-400"></textarea>
                </div>

                <input type="hidden" name="access_password" value="<?php echo esc_attr($_POST['access_password']); ?>">
                <?php wp_nonce_field('video_upload', 'video_upload_nonce'); ?>
                <button type="submit" 
                    class="w-full bg-[#BA9E5E] text-white py-3 px-6 rounded-lg hover:bg-[#A18543] transition-colors duration-200">
                    Upload Video
                </button>
            </form>

            <div id="upload-progress" class="hidden mt-6">
                <div class="w-full bg-gray-700 rounded-lg overflow-hidden">
                    <div id="progress-bar" class="h-8 bg-[#BA9E5E] text-white text-center leading-8 transition-all duration-300" style="width: 0%">0%</div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
if ($is_authorized) {
    wp_enqueue_script('video-upload', get_template_directory_uri() . '/js/video-upload.js', array('jquery'), '1.0', true);
    wp_localize_script('video-upload', 'uploadVars', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('video_upload')
    ));
}
get_footer();