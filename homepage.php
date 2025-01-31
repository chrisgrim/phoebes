<?php
/**
 * Template Name: Homepage Template
 *
 * @package GeneratePress
 */

get_header();
error_log('Header loaded');
?>

	<main id="primary" class="site-main">
        <div id="header" class="relative">
            <?php
            // Get the URL of the featured image
            $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

            // Check if there is a featured image set for the post
            if ($featured_image_url) {
                // Set the background image style with the featured image URL
                ?>
                <div class="header w-full h-[24rem] bg-cover bg-center" style="background-image: url('<?php echo esc_url($featured_image_url); ?>');">
                    <div class="w-full h-full p-8 flex items-center text-center justify-center bg-black bg-opacity-70">
                        <h1 class="text-white text-2xl md:text-4xl font-medium uppercase tracking-[1.2rem]">
                            <a id="openVideo" style="cursor:pointer;"><?php the_title(); ?></a>
                        </h1>
                    </div>
                </div>
                <?php
            } else {
                echo '<div class="header w-full h-[30rem]"></div>';
            }
            ?>
        </div> 


        <video id="myVideo" class="hidden" controls>
            <source src="https://thephoebes.com/wp-content/uploads/Everywhere-You-Look_01_Small.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>



        <div id="description" class="w-full pt-16">
            <div class="w-full md:w-2/3 lg:w-3/6 m-auto px-8">
                <p class="text-white">
                    Since 2024, the Corner presents the Phoebe's–the phirst critically acclaimed philm phestival Petaluma has never seen. Join us as we gather in celebration of independent storytelling, obscure creativity, and general silliness gracing the greater North Bay Area (and beyond).
                </p>
            </div>
        </div>

        <!-- Annual Categories -->
        <div class="flex justify-center px-4 relative mt-16">
            <div class="flex md:flex-wrap overflow-x-auto md:overflow-x-visible snap-x snap-mandatory space-x-1 md:space-x-0 md:justify-center" style="scrollbar-width: none;">
                <?php
                // Get both annual categories
                $annual_categories = get_categories([
                    'orderby' => 'name',
                    'order'   => 'DESC',
                    'include' => array(
                        get_category_by_slug('2nd-annual')->term_id,
                        get_category_by_slug('1st-annual')->term_id
                    )
                ]);

                foreach ($annual_categories as $category) {
                    $category_image_url = z_taxonomy_image_url($category->term_id);
                    if (!$category_image_url) {
                        $category_image_url = 'path/to/default/image.jpg';
                    }
                    ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="snap-start border border-transparent hover:border-[#ba9e5e]">
                        <div class="bg-cover bg-center text-white h-[28rem] md:h-[30rem] w-72 md:m-1" style="background-image: url('<?php echo esc_url($category_image_url); ?>'); background-blend-mode: darken;">
                            <div class="flex flex-col justify-end text-center bg-black bg-opacity-25 h-full p-4 relative hover:bg-transparent">
                                <h2 class="text-2xl font-bold"><?php echo esc_html($category->name); ?></h2>
                                <p><?php echo esc_html($category->description); ?></p>
                                <span class="bg-[#ba9e5e] h-[0.2rem] w-12 absolute bottom-0 left-1/2" style="transform: translateX(-50%);"></span>
                            </div>
                        </div>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>

        <div id="Sponsors">
            <div class="w-full bg-white my-20 py-24 text-center">
                <h3 class="text-3xl mb-12 font-medium tracking-[1rem]">SPONSORS</h3>
                <div class="flex w-full flex-wrap md:gap-16 justify-center items-center px-4 md:px-32">
                    <?php
                    // Query arguments
                    $args = array(
                        'category_name' => 'advertisement', // Replace 'your-category-slug' with your actual category slug
                        'posts_per_page' => -1, // -1 to show all posts
                    );

                    // The Query
                    $the_query = new WP_Query($args);

                    // The Loop
                    if ($the_query->have_posts()) {
                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            // Check if the post has a Post Thumbnail assigned to it.
                            if (has_post_thumbnail()) {
                                $post_permalink = get_permalink();
                                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full'); // You can change 'full' to a different image size
                                ?>
                                <a class="block w-1/2 md:w-40 px-4 md:px-0" href="<?php echo esc_url($post_permalink); ?>">
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" class="mb-4 hover:scale-110 transition-transform duration-200 transform">
                                </a>
                                <?php
                            }
                        }
                    } else {
                        // No posts found
                        echo '<p>No posts found.</p>';
                    }
                    // Restore original Post Data
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
	</main><!-- #main -->

<?php
get_footer();

