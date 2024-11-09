<?php
/**
 * phoebes functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package phoebes
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function phoebes_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on phoebes, use a find and replace
		* to change 'phoebes' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'phoebes', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'phoebes' ),
            'menu-2' => esc_html__( 'Secondary', 'phoebes' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
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

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'phoebes_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
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

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function phoebes_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'phoebes_content_width', 640 );
}
add_action( 'after_setup_theme', 'phoebes_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
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

/**
 * Enqueue scripts and styles.
 */
function phoebes_scripts() {
	wp_enqueue_style( 'phoebes-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'phoebes-style', 'rtl', 'replace' );

    //added by Chris Grim
    wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/css/app.css', false, '1.0', 'all' ); 
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    wp_dequeue_style( 'classic-theme-styles' );

	wp_enqueue_script( 'phoebes-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'phoebes_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



add_filter('walker_nav_menu_start_el', 'add_image_to_menu_item', 10, 4);

function add_image_to_menu_item($item_output, $item, $depth, $args) {
    if ($item->ID == 11) {
        $image_url = 'https://thephoebes.com/wp-content/uploads/2023/12/the_phoebes_01.png';
        $item_output = "<div class='menu-centered-item w-40'><a href='" . $item->url . "'>" . 
                       "<img src='" . $image_url . "' alt='' /></a></div>";
    }
    return $item_output;
}


function move_jquery_to_header() {
    wp_deregister_script('jquery');
    
    // Register the script again in the header
    wp_register_script('jquery', includes_url('/js/jquery/jquery.js'), false, NULL, false);
    
    // Add it back into the queue
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'move_jquery_to_header');

// custom code to add video to posts

add_action('add_meta_boxes', 'custom_video_meta_box');
function custom_video_meta_box() {
    add_meta_box('video_meta_box', 'Featured Video', 'video_meta_box_output', 'post', 'normal', 'high');
}

function video_meta_box_output($post) {
    // Enqueue the media uploader script
    wp_enqueue_media();

    $video_url = get_post_meta($post->ID, '_video_url', true);

    echo '<div>';
    echo '<input type="hidden" id="video_url" name="video_url" value="' . esc_attr($video_url) . '">';
    echo '<button type="button" id="upload_video_button">Choose Video</button>';

    // Add a div for the message
    echo '<div id="video_message">' . ($video_url ? 'Video Attached' : 'No Video Attached') . '</div>';

    echo '</div>';

    ?>
    <script>
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

                    // Update the message immediately after a video is selected
                    $('#video_message').text('Video Attached');
                }).open();
            });
        });
    </script>
    <?php
}

add_action('save_post', 'save_video_meta_box');
function save_video_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['video_url'])) {
        update_post_meta($post_id, '_video_url', sanitize_text_field($_POST['video_url']));
    }
}

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
add_action('admin_head', 'custom_admin_style');


function my_theme_enqueue_styles() {
    $theme_version = wp_get_theme()->get('Version');

    wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/custom-style.css', array(), $theme_version );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


function add_custom_meta_box() {
    add_meta_box("post_ranking_meta", "Post Ranking", "post_ranking_meta_box_markup", "post", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function post_ranking_meta_box_markup($object) {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
    <div>
        <label for="ranking">Ranking (0-10)</label>
        <input name="ranking" type="number" value="<?php echo get_post_meta($object->ID, "ranking", true); ?>" min="0" max="10">
    </div>
    <?php  
}

function save_custom_meta_box($post_id, $post, $update) {
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $ranking = isset($_POST['ranking']) ? $_POST['ranking'] : '';
    update_post_meta($post_id, "ranking", $ranking);
}

add_action("save_post", "save_custom_meta_box", 10, 3);

// sorts by ranking when displaying posts
function sort_posts_by_ranking($query) {
    if (!is_admin() && $query->is_main_query() && is_category()) {
        $query->set('meta_key', 'ranking');
        $query->set('orderby', array('meta_value_num' => 'DESC', 'date' => 'DESC'));
    }
}
add_action('pre_get_posts', 'sort_posts_by_ranking');

// makes default ranking 0 for posts
function set_default_post_ranking($post_id, $post, $update) {
    // If it's a new post, $update will be false
    // Check if the post is being created (not updated)
    if (!$update) {
        // Check if the post has a 'ranking' meta value set
        $existing_ranking = get_post_meta($post_id, 'ranking', true);

        // If there's no existing ranking, set it to 0
        if ('' === $existing_ranking) {
            update_post_meta($post_id, 'ranking', 0);
        }
    }
}
add_action('save_post', 'set_default_post_ranking', 10, 3);


function mytheme_enqueue_custom_scripts() {
    if (is_page_template('homepage.php')) { // Replace 'your-template-file.php' with your actual template file name
        wp_enqueue_script(
            'custom-video-script',
            get_template_directory_uri() . '/js/custom-video.js',
            array(), '1.0.0', true
        );
    }
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_custom_scripts');


// adding users are actors or directors

// Register the meta boxes for selecting actors and directors
function add_user_selection_meta_box() {
    add_meta_box(
        'user_selection_meta_box', // Unique ID for the meta box
        'Select Actor and Director', // Title of the meta box
        'user_selection_meta_box_callback', // Callback function to display the fields
        'post', // The post type where this meta box should appear
        'side', // Position where the meta box should appear (side, normal, advanced)
        'default' // Priority of the meta box (default, low, high, core)
    );
}
add_action('add_meta_boxes', 'add_user_selection_meta_box');

// Callback function for displaying the meta box
function user_selection_meta_box_callback($post) {
    // Security nonce for verification
    wp_nonce_field('save_user_selection', 'user_selection_nonce');

    // Fetch all users for the dropdown
    $users = get_users();
    $actor_user_ids = get_post_meta($post->ID, 'actor_user', true);
    $director_user_ids = get_post_meta($post->ID, 'director_user', true);

    // Ensure the values are arrays for multiple selection handling
    if (!is_array($actor_user_ids)) {
        $actor_user_ids = array();
    }
    if (!is_array($director_user_ids)) {
        $director_user_ids = array();
    }

    // Actors selection
    echo '<p><label for="actor_user">Actors:</label>';
    echo '<select id="actor_user" name="actor_user[]" multiple="multiple" style="width: 100%; height: 100px;">';
    foreach ($users as $user) {
        $selected = in_array($user->ID, $actor_user_ids) ? ' selected="selected"' : '';
        echo '<option value="' . esc_attr($user->ID) . '"' . $selected . '>' . esc_html($user->display_name) . '</option>';
    }
    echo '</select></p>';

    // Directors selection
    echo '<p><label for="director_user">Directors:</label>';
    echo '<select id="director_user" name="director_user[]" multiple="multiple" style="width: 100%; height: 100px;">';
    foreach ($users as $user) {
        $selected = in_array($user->ID, $director_user_ids) ? ' selected="selected"' : '';
        echo '<option value="' . esc_attr($user->ID) . '"' . $selected . '>' . esc_html($user->display_name) . '</option>';
    }
    echo '</select></p>';
}

// Save the selected actors and directors when the post is saved
function save_user_selections($post_id) {
    // Check for nonce to secure the data being saved
    if (!isset($_POST['user_selection_nonce']) || !wp_verify_nonce($_POST['user_selection_nonce'], 'save_user_selection')) {
        return;
    }

    // Check for autosave to prevent the meta from being saved during autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save the selected actors
    if (isset($_POST['actor_user'])) {
        $actor_users = array_map('sanitize_text_field', $_POST['actor_user']);
        update_post_meta($post_id, 'actor_user', $actor_users);
    } else {
        delete_post_meta($post_id, 'actor_user');
    }

    // Save the selected directors
    if (isset($_POST['director_user'])) {
        $director_users = array_map('sanitize_text_field', $_POST['director_user']);
        update_post_meta($post_id, 'director_user', $director_users);
    } else {
        delete_post_meta($post_id, 'director_user');
    }
}
add_action('save_post', 'save_user_selections');


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
        'show_in_rest'      => true, // Add this line
    );

    register_taxonomy('festival-editions', array('post'), $args); // Replace 'post' with your custom post type if necessary
}

add_action('init', 'register_festival_editions_taxonomy');




