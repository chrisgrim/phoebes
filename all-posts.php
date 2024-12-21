<?php
/* Template Name: All Posts */
get_header();
?>

<main id="primary" class="site-main">
    <div class="w-full bg-white category-page">
        <div class="flex w-full justify-center pt-12 text-center">
            <h1 class="text-[#666666] text-2xl md:text-4xl font-medium uppercase tracking-[1.2rem]"><?php single_cat_title(); ?></h1>
        </div>
        <?php 
        $all_posts_query = new WP_Query(array('posts_per_page' => -1)); // -1 loads all posts
        if ($all_posts_query->have_posts()) : while ($all_posts_query->have_posts()) : $all_posts_query->the_post() ?>
            <div class="w-full max-w-4xl m-auto md:border-b border-[#6666664c] pb-4 md:pb-12 md:mt-12 relative">
                <div class="flex flex-col md:flex-row border md:border-none border-[#865d015e] m-4 py-4 md:py-0 md:m-0">
                    <div class="w-full md:w-1/3 px-4 md:pl-8 md:pr-0 lg:pl-0">
                        <a href="<?php the_permalink(); ?>" class="block md:border border-[#BA9E5E5E]">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <img class="md:p-2" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="w-full md:w-2/3 px-4 md:px-8 md:pb-4">
                        <p class="mt-4 md:mt-0 text-[#ba9e5e] mb-2 uppercase text-xs tracking-[0.15rem]">
                            <?php 
                            // Fetch categories of the post
                            $categories = get_the_category();
                            $separator = ', ';
                            $output = '';
                            if (!empty($categories)) {
                                foreach ($categories as $category) {
                                    $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-type-link"' . '" alt="' . esc_attr(sprintf(__('View all posts in %s', 'textdomain'), $category->name)) . '">' . esc_html($category->name) . '</a>' . $separator;
                                }
                                echo trim($output, $separator);
                            }
                            ?>
                        </p>
                        <a href="<?php the_permalink(); ?>" class="block">
                            <h2 class="text-2xl text-black"><?php the_title(); ?></h2>
                        </a>
                        <div class="category-desc text-[0.8rem] w-full md:w-2/3 pb-4 md:pb-0 text-black">
                            <?php
                            // Capture the content into a variable
                            $content = get_the_content();

                            // Remove shortcodes and HTML tags to avoid breaking any HTML
                            $content = strip_shortcodes($content);
                            $content = wp_strip_all_tags($content);

                            // Check the length of the content
                            if (strlen($content) > 200) {
                                // Truncate and append an ellipsis
                                $content = substr($content, 0, 200) . '...';
                            }

                            // Display the modified content
                            echo $content;
                            ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="category-button block relative md:absolute md:bottom-12 md:right-8 lg:right-0">
                            <button class="py-4 px-8 border-[#ba9e5e] border-2 w-full text-center md:text-right">Watch Now</button>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; else : ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
</main><!-- #main -->

<?php get_footer(); ?>
