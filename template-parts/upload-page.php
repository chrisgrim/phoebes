<?php
/**
 * Template Name: Video Upload Page
 */
get_header();
?>

<main id="primary" class="site-main min-h-screen bg-black text-festival-black">
    <div id="upload-page-root" class="mx-auto px-4 py-12 w-full" style="max-width: 576px;">

        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">Submit Your Film</h1>
            <p class="text-festival-gray">Share your work with The Phoebe's Film Festival</p>
        </div>

        <!-- Step 1: Email Verification -->
        <div id="step-email" class="upload-step">
            <div class="bg-festival-light border border-festival-border rounded-xl p-8">
                <div class="mb-6">
                    <span class="text-festival-gold text-sm font-medium">Step 1 of 2</span>
                    <h2 class="text-2xl font-bold mt-1">Verify Your Email</h2>
                    <p class="text-festival-gray mt-2">We'll send you a code to access the upload form.</p>
                </div>

                <form id="email-form" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required
                            placeholder="filmmaker@example.com"
                            class="w-full px-4 py-3 bg-festival-light border border-festival-border rounded-lg focus:border-festival-gold focus:ring-1 focus:ring-festival-gold focus:outline-none text-festival-black placeholder-festival-gray">
                    </div>
                    <button type="submit" id="send-code-btn"
                        class="w-full bg-white text-black py-3 px-6 rounded-lg hover:bg-gray-800 transition-colors duration-200 font-medium">
                        Send Code
                    </button>
                </form>
            </div>
        </div>

        <!-- Step 1b: Enter Code -->
        <div id="step-code" class="upload-step hidden">
            <div class="bg-festival-light border border-festival-border rounded-xl p-8">
                <div class="mb-6">
                    <span class="text-festival-gold text-sm font-medium">Step 1 of 2</span>
                    <h2 class="text-2xl font-bold mt-1">Enter Your Code</h2>
                    <p class="text-festival-gray mt-2">Check your email for a 6-digit code.</p>
                </div>

                <form id="code-form" class="space-y-4">
                    <div>
                        <label for="code" class="block text-sm font-medium mb-2">Access Code</label>
                        <input type="text" id="code" name="code" required
                            placeholder="000000"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            class="w-full px-4 py-3 bg-festival-light border border-festival-border rounded-lg focus:border-festival-gold focus:ring-1 focus:ring-festival-gold focus:outline-none text-festival-black placeholder-festival-gray text-center text-2xl tracking-widest">
                    </div>
                    <button type="submit" id="verify-code-btn"
                        class="w-full bg-white text-black py-3 px-6 rounded-lg hover:bg-gray-800 transition-colors duration-200 font-medium">
                        Verify Code
                    </button>
                </form>
                <button id="back-to-email" class="w-full mt-4 text-festival-gray hover:text-festival-black text-sm">
                    Use a different email
                </button>
            </div>
        </div>

        <!-- Step 2: Upload Form -->
        <div id="step-upload" class="upload-step hidden">
            <div class="bg-festival-light border border-festival-border rounded-xl p-8">
                <div class="mb-6">
                    <span class="text-festival-gold text-sm font-medium">Step 2 of 2</span>
                    <h2 class="text-2xl font-bold mt-1">Upload Your Film</h2>
                </div>

                <form id="upload-form" class="space-y-6" enctype="multipart/form-data">
                    <input type="hidden" id="upload_token" name="upload_token" value="">

                    <div>
                        <label for="movie_title" class="block text-sm font-medium mb-2">Film Title <span class="text-red-500">*</span></label>
                        <input type="text" id="movie_title" name="movie_title" required
                            placeholder="Enter your film's title"
                            class="w-full px-4 py-3 bg-festival-light border border-festival-border rounded-lg focus:border-festival-gold focus:ring-1 focus:ring-festival-gold focus:outline-none text-festival-black placeholder-festival-gray">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Video File <span class="text-red-500">*</span></label>
                        <div id="video-dropzone" class="border-2 border-dashed border-festival-border rounded-lg p-8 text-center cursor-pointer hover:border-festival-gold transition-colors">
                            <div id="video-dropzone-content">
                                <svg class="mx-auto h-12 w-12 text-festival-gray mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-festival-gray mb-2">Drag & drop your video here, or click to browse</p>
                                <p class="text-festival-gray text-sm">MP4, MOV, AVI, or WebM (max 512MB)</p>
                            </div>
                            <div id="video-selected" class="hidden">
                                <svg class="mx-auto h-12 w-12 text-festival-gold mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <p class="text-festival-black font-medium" id="video-filename"></p>
                                <p class="text-festival-gray text-sm" id="video-filesize"></p>
                                <button type="button" id="remove-video" class="text-red-500 text-sm mt-2 hover:text-red-600">Remove</button>
                            </div>
                        </div>
                        <input type="file" id="video_file" name="video_file" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm" class="hidden" required>
                        <p id="video-error" class="text-red-500 text-sm mt-2 hidden"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Thumbnail Image <span class="text-festival-gray">(optional)</span></label>
                        <div id="thumb-dropzone" class="border-2 border-dashed border-festival-border rounded-lg p-6 text-center cursor-pointer hover:border-festival-gold transition-colors">
                            <div id="thumb-dropzone-content">
                                <p class="text-festival-gray mb-1">Click to add a thumbnail</p>
                                <p class="text-festival-gray text-sm">JPG, PNG, or WebP (max 5MB)</p>
                            </div>
                            <div id="thumb-selected" class="hidden">
                                <img id="thumb-preview" class="mx-auto max-h-32 rounded mb-2">
                                <p class="text-festival-black text-sm" id="thumb-filename"></p>
                                <button type="button" id="remove-thumb" class="text-red-500 text-sm mt-1 hover:text-red-600">Remove</button>
                            </div>
                        </div>
                        <input type="file" id="thumbnail_file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp" class="hidden">
                    </div>

                    <div>
                        <label for="movie_description" class="block text-sm font-medium mb-2">Description & Credits</label>
                        <textarea id="movie_description" name="movie_description" rows="4"
                            placeholder="Tell us about your film, include credits, cast, etc."
                            class="w-full px-4 py-3 bg-festival-light border border-festival-border rounded-lg focus:border-festival-gold focus:ring-1 focus:ring-festival-gold focus:outline-none text-festival-black placeholder-festival-gray resize-none"></textarea>
                    </div>

                    <div id="upload-progress" class="hidden">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-festival-gray">Uploading...</span>
                            <span id="progress-percent" class="text-festival-gold">0%</span>
                        </div>
                        <div class="w-full bg-festival-border rounded-full h-2 overflow-hidden">
                            <div id="progress-bar" class="h-full bg-festival-gold transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p class="text-festival-gray text-sm mt-2">Large files may take several minutes. Please don't close this page.</p>
                    </div>

                    <button type="submit" id="upload-btn"
                        class="w-full bg-white text-black py-3 px-6 rounded-lg hover:bg-gray-800 transition-colors duration-200 font-medium">
                        Submit Film
                    </button>
                </form>
            </div>
        </div>

        <!-- Success State -->
        <div id="step-success" class="upload-step hidden">
            <div class="bg-festival-light border border-festival-border rounded-xl p-8 text-center">
                <svg class="mx-auto h-16 w-16 text-festival-gold mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold mb-2">Submission Received!</h2>
                <p class="text-festival-gray mb-6">Thank you for submitting your film to The Phoebe's Film Festival. We'll review it and be in touch soon.</p>
                <a href="<?php echo home_url(); ?>" class="inline-block bg-white text-black py-3 px-6 rounded-lg hover:bg-gray-800 transition-colors duration-200 font-medium">
                    Back to Home
                </a>
            </div>
        </div>

        <!-- Error Display -->
        <div id="global-error" class="hidden mt-4 bg-red-50 border border-red-300 rounded-lg p-4 text-red-700">
            <p id="error-message"></p>
        </div>

    </div>
</main>

<?php
wp_enqueue_script('phoebes-upload', get_template_directory_uri() . '/js/video-upload.js', ['jquery'], _S_VERSION, true);
wp_localize_script('phoebes-upload', 'phoebesTownload', [
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('phoebes_upload_nonce'),
    'maxVideoSize' => 512 * 1024 * 1024,
    'maxThumbSize' => 5 * 1024 * 1024,
]);

get_footer();
