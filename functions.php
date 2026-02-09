<?php
/**
 * Phoebes functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package phoebes
 */

// Abbreviate last name: "Chris Grim" → "Chris G."
function phoebes_short_name($full_name) {
    $parts = explode(' ', trim($full_name));
    if (count($parts) < 2) return $full_name;
    return $parts[0] . ' ' . strtoupper(mb_substr(end($parts), 0, 1)) . '.';
}

// Check if user has a custom Gravatar (cached per request)
function phoebes_has_gravatar($user_id) {
    static $cache = [];
    if (isset($cache[$user_id])) return $cache[$user_id];
    $url = get_avatar_url($user_id, ['size' => 1, 'default' => '404']);
    $response = wp_remote_head($url);
    $cache[$user_id] = !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    return $cache[$user_id];
}

if ( ! defined( '_S_VERSION' ) ) {
    define( '_S_VERSION', '1.0.0' );
}

function phoebes_setup() {
    load_theme_textdomain( 'phoebes', get_template_directory() . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    register_nav_menus(
        array(
            'menu-1' => esc_html__( 'Primary', 'phoebes' ),
            'menu-2' => esc_html__( 'Secondary', 'phoebes' ),
        )
    );
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
    add_theme_support(
        'custom-background',
        array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )
    );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );
}
add_action( 'after_setup_theme', 'phoebes_setup' );

function phoebes_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'phoebes_content_width', 640 );
}
add_action( 'after_setup_theme', 'phoebes_content_width', 0 );

function phoebes_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Sidebar', 'phoebes' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'phoebes' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action( 'widgets_init', 'phoebes_widgets_init' );

function phoebes_scripts() {
    // Styles
    wp_enqueue_style('phoebes-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_style_add_data('phoebes-style', 'rtl', 'replace');
    wp_enqueue_style('my-style', get_stylesheet_directory_uri() . '/css/app.css', false, filemtime(get_stylesheet_directory() . '/css/app.css'), 'all');
    wp_enqueue_style('custom-style', get_template_directory_uri() . '/custom-style.css', array(), wp_get_theme()->get('Version'));
    
    // Scripts - jQuery will be handled by defer_scripts() function below
    wp_enqueue_script('phoebes-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

// Remove other wp_enqueue_scripts actions
remove_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
remove_action('wp_enqueue_scripts', 'move_jquery_to_header');
remove_action('wp_enqueue_scripts', 'mytheme_enqueue_custom_scripts');

// Add the consolidated enqueue function
add_action('wp_enqueue_scripts', 'phoebes_scripts');

// Helper function to get current template
function get_current_template() {
    global $template;
    return basename($template);
}

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if ( defined( 'JETPACK__VERSION' ) ) {
    require get_template_directory() . '/inc/jetpack.php';
}

// Video upload functionality
require get_template_directory() . '/inc/video-upload.php';

add_action('add_meta_boxes', 'custom_video_meta_box');
function custom_video_meta_box() {
    add_meta_box('video_meta_box', 'Featured Video', 'video_meta_box_output', 'post', 'normal', 'high');
}

function video_meta_box_output($post) {
    wp_enqueue_media();
    wp_enqueue_script('phoebes-video-meta-box', get_template_directory_uri() . '/js/video-meta-box.js', array('jquery'), _S_VERSION, true);
    wp_nonce_field('video_meta_box_nonce_action', 'video_meta_box_nonce');
    $video_url = get_post_meta($post->ID, '_video_url', true);
    echo '<div>';
    echo '<input type="hidden" id="video_url" name="video_url" value="' . esc_attr($video_url) . '">';
    echo '<button type="button" id="upload_video_button">Choose Video</button>';
    echo '<div id="video_message">' . ($video_url ? 'Video Attached' : 'No Video Attached') . '</div>';
    echo '</div>';
}

add_action('save_post', 'save_video_meta_box');
function save_video_meta_box($post_id) {
    // Check nonce
    if (!isset($_POST['video_meta_box_nonce']) || !wp_verify_nonce($_POST['video_meta_box_nonce'], 'video_meta_box_nonce_action')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (isset($_POST['video_url'])) {
        update_post_meta($post_id, '_video_url', sanitize_text_field($_POST['video_url']));
    }
}

add_action('admin_head', 'custom_admin_style');
function custom_admin_style() {
    ?>
    <style>
        #video_message {
            margin-top: 10px;
            padding: 5px;
            background-color: #f1f1f1;
            border: 1px solid #dcdcdc;
            text-align: center;
        }
    </style>
    <?php
}

add_action("add_meta_boxes", "add_custom_meta_box");
function add_custom_meta_box() {
    add_meta_box("post_ranking_meta", "Post Ranking", "post_ranking_meta_box_markup", "post", "side", "high", null);
}

function post_ranking_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
    <div>
        <label for="ranking">Ranking (0-10)</label>
        <input name="ranking" type="number" value="<?php echo get_post_meta($object->ID, "ranking", true); ?>" min="0" max="10">
    </div>
    <?php  
}

add_action("save_post", "save_custom_meta_box", 10, 3);
function save_custom_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    if($post->post_type != "post")
        return $post_id;
    $ranking = isset($_POST['ranking']) ? $_POST['ranking'] : '';
    update_post_meta($post_id, "ranking", $ranking);
}

add_action('pre_get_posts', 'sort_posts_by_ranking');
function sort_posts_by_ranking($query) {
    if (!is_admin() && $query->is_main_query() && is_category()) {
        $query->set('meta_key', 'ranking');
        $query->set('orderby', array('meta_value_num' => 'DESC', 'date' => 'DESC'));
    }
}

add_action('save_post', 'set_default_post_ranking', 10, 3);
function set_default_post_ranking($post_id, $post, $update) {
    if (!$update) {
        $existing_ranking = get_post_meta($post_id, 'ranking', true);
        if ('' === $existing_ranking) {
            update_post_meta($post_id, 'ranking', 0);
        }
    }
}

add_action('add_meta_boxes', 'add_user_selection_meta_box');
function add_user_selection_meta_box() {
    add_meta_box(
        'user_selection_meta_box',
        'Select Actor and Director',
        'user_selection_meta_box_callback',
        'post',
        'side',
        'default'
    );
}

function user_selection_meta_box_callback($post) {
    wp_nonce_field('save_user_selection', 'user_selection_nonce');
    $users = get_users();
    $actor_user_ids = get_post_meta($post->ID, 'actor_user', true) ?: array();
    $director_user_ids = get_post_meta($post->ID, 'director_user', true) ?: array();
    echo '<p><label for="actor_user">Actors:</label><select id="actor_user" name="actor_user[]" multiple="multiple" style="width: 100%; height: 100px;">';
    foreach ($users as $user) {
        echo '<option value="' . esc_attr($user->ID) . '"' . (in_array($user->ID, $actor_user_ids) ? ' selected="selected"' : '') . '>' . esc_html($user->display_name) . '</option>';
    }
    echo '</select></p><p><label for="director_user">Directors:</label><select id="director_user" name="director_user[]" multiple="multiple" style="width: 100%; height: 100px;">';
    foreach ($users as $user) {
        echo '<option value="' . esc_attr($user->ID) . '"' . (in_array($user->ID, $director_user_ids) ? ' selected="selected"' : '') . '>' . esc_html($user->display_name) . '</option>';
    }
    echo '</select></p>';
}

add_action('save_post', 'save_user_selections');
function save_user_selections($post_id) {
    if (!isset($_POST['user_selection_nonce']) || !wp_verify_nonce($_POST['user_selection_nonce'], 'save_user_selection') || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['actor_user'])) {
        update_post_meta($post_id, 'actor_user', array_map('sanitize_text_field', $_POST['actor_user']));
    } else {
        delete_post_meta($post_id, 'actor_user');
    }
    if (isset($_POST['director_user'])) {
        update_post_meta($post_id, 'director_user', array_map('sanitize_text_field', $_POST['director_user']));
    } else {
        delete_post_meta($post_id, 'director_user');
    }
}

add_action('init', 'register_festival_editions_taxonomy');
function register_festival_editions_taxonomy() {
    $labels = array(
        'name'              => _x('Festival Editions', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Festival Edition', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Festival Editions', 'textdomain'),
        'all_items'         => __('All Festival Editions', 'textdomain'),
        'parent_item'       => __('Parent Festival Edition', 'textdomain'),
        'parent_item_colon' => __('Parent Festival Edition:', 'textdomain'),
        'edit_item'         => __('Edit Festival Edition', 'textdomain'),
        'update_item'       => __('Update Festival Edition', 'textdomain'),
        'add_new_item'      => __('Add New Festival Edition', 'textdomain'),
        'new_item_name'     => __('New Festival Edition Name', 'textdomain'),
        'menu_name'         => __('Festival Editions', 'textdomain'),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'festival-editions'),
        'show_in_rest'      => true,
    );
    register_taxonomy('festival-editions', array('post'), $args);
}

// Add custom mime types
function add_custom_mime_types($mimes) {
    $mimes['mp4'] = 'video/mp4';
    $mimes['mov'] = 'video/quicktime';
    $mimes['avi'] = 'video/x-msvideo';
    $mimes['webm'] = 'video/webm';
    return $mimes;
}
add_filter('upload_mimes', 'add_custom_mime_types');

function add_google_analytics() {
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XEN6QQQXHG"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-XEN6QQQXHG');
    </script>
    <?php
}
add_action('wp_head', 'add_google_analytics');

// Add to functions.php - Aggressively purge Nginx cache on ANY WordPress update
function purge_all_nginx_cache() {
    // Don't run during autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    // Execute the shell command to purge the entire cache
    exec('rm -rf /var/run/nginx-cache/*');
}

// Hook into ALL possible update actions
// Content and post updates
add_action('save_post', 'purge_all_nginx_cache');
add_action('edit_post', 'purge_all_nginx_cache');
add_action('delete_post', 'purge_all_nginx_cache');
add_action('wp_trash_post', 'purge_all_nginx_cache');
add_action('publish_post', 'purge_all_nginx_cache');
add_action('publish_page', 'purge_all_nginx_cache');
add_action('wp_update_nav_menu', 'purge_all_nginx_cache');

// Comments
add_action('edit_comment', 'purge_all_nginx_cache');
add_action('delete_comment', 'purge_all_nginx_cache');
add_action('wp_set_comment_status', 'purge_all_nginx_cache');
add_action('wp_insert_comment', 'purge_all_nginx_cache');

// Terms and taxonomies
add_action('create_term', 'purge_all_nginx_cache');
add_action('edit_term', 'purge_all_nginx_cache');
add_action('delete_term', 'purge_all_nginx_cache');

// Theme and plugin changes
add_action('switch_theme', 'purge_all_nginx_cache');
add_action('activated_plugin', 'purge_all_nginx_cache');
add_action('deactivated_plugin', 'purge_all_nginx_cache');

// Widgets and customizer
add_action('update_option', 'purge_all_nginx_cache');
add_action('updated_option', 'purge_all_nginx_cache');
add_action('added_option', 'purge_all_nginx_cache');
add_action('customize_save', 'purge_all_nginx_cache');
add_action('customize_save_after', 'purge_all_nginx_cache');
add_action('widgets_save_hook', 'purge_all_nginx_cache');
add_action('sidebar_admin_setup', 'purge_all_nginx_cache');

// Upload attachment
add_action('add_attachment', 'purge_all_nginx_cache');
add_action('edit_attachment', 'purge_all_nginx_cache');
add_action('delete_attachment', 'purge_all_nginx_cache');

// User updates
add_action('user_register', 'purge_all_nginx_cache');
add_action('profile_update', 'purge_all_nginx_cache');
add_action('deleted_user', 'purge_all_nginx_cache');

// Also purge when entering/exiting the admin area
add_action('admin_init', 'purge_all_nginx_cache');
add_action('wp_logout', 'purge_all_nginx_cache');


function defer_scripts() {
    if (!is_admin()) {
        wp_dequeue_script('jquery');
        wp_deregister_script('jquery');
        wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), false, null, true);
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'defer_scripts', 999);

/**
 * Category Images - replacement for Categories Images plugin
 */

// Get category image URL (checks new key, falls back to old plugin's key)
function phoebes_get_category_image_url($term_id) {
    // Check new key first
    $image_id = get_term_meta($term_id, 'category_image_id', true);

    // Fall back to old Categories Images plugin key
    if (!$image_id) {
        $image_id = get_term_meta($term_id, 'z_taxonomy_image_id', true);
    }

    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        return $image_url ? $image_url : '';
    }
    return '';
}

// Add image field to category add form
function phoebes_category_add_image_field() {
    wp_enqueue_media();
    ?>
    <div class="form-field">
        <label><?php _e('Category Image', 'phoebes'); ?></label>
        <input type="hidden" name="category_image_id" id="category_image_id" value="">
        <div id="category_image_preview"></div>
        <button type="button" class="button" id="upload_category_image"><?php _e('Upload Image', 'phoebes'); ?></button>
        <button type="button" class="button" id="remove_category_image" style="display:none;"><?php _e('Remove Image', 'phoebes'); ?></button>
    </div>
    <?php
    phoebes_category_image_script();
}
add_action('category_add_form_fields', 'phoebes_category_add_image_field');

// Add image field to category edit form
function phoebes_category_edit_image_field($term) {
    wp_enqueue_media();
    $image_id = get_term_meta($term->term_id, 'category_image_id', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
    ?>
    <tr class="form-field">
        <th scope="row"><label><?php _e('Category Image', 'phoebes'); ?></label></th>
        <td>
            <input type="hidden" name="category_image_id" id="category_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div id="category_image_preview">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width:200px;height:auto;">
                <?php endif; ?>
            </div>
            <button type="button" class="button" id="upload_category_image"><?php _e('Upload Image', 'phoebes'); ?></button>
            <button type="button" class="button" id="remove_category_image" <?php echo $image_id ? '' : 'style="display:none;"'; ?>><?php _e('Remove Image', 'phoebes'); ?></button>
        </td>
    </tr>
    <?php
    phoebes_category_image_script();
}
add_action('category_edit_form_fields', 'phoebes_category_edit_image_field');

// Save category image
function phoebes_save_category_image($term_id) {
    if (isset($_POST['category_image_id'])) {
        $image_id = absint($_POST['category_image_id']);
        if ($image_id) {
            update_term_meta($term_id, 'category_image_id', $image_id);
        } else {
            delete_term_meta($term_id, 'category_image_id');
        }
    }
}
add_action('created_category', 'phoebes_save_category_image');
add_action('edited_category', 'phoebes_save_category_image');

// Media uploader script for category images
function phoebes_category_image_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        var frame;
        $('#upload_category_image').on('click', function(e) {
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({
                title: '<?php _e('Select Category Image', 'phoebes'); ?>',
                button: { text: '<?php _e('Use Image', 'phoebes'); ?>' },
                multiple: false
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $('#category_image_id').val(attachment.id);
                $('#category_image_preview').html('<img src="' + attachment.url + '" style="max-width:200px;height:auto;">');
                $('#remove_category_image').show();
            });
            frame.open();
        });
        $('#remove_category_image').on('click', function(e) {
            e.preventDefault();
            $('#category_image_id').val('');
            $('#category_image_preview').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}