<?php
/* Template Name: Events */
get_header();
?>

<main id="primary" class="site-main">
    <div class="w-full bg-white events-page">
        <div class="flex w-full justify-center pt-12 text-center">
            <h1 class="text-[#666666] text-2xl md:text-4xl font-medium uppercase tracking-[1.2rem]"><?php the_title(); ?></h1>
        </div>
        <?php 
        // Get all pages that are direct children of this page
        $events_query = new WP_Query(array(
            'post_type' => 'page',
            'post_parent' => get_the_ID(),
            'orderby' => 'title',
            'order' => 'DESC',
            'posts_per_page' => -1
        ));

        if ($events_query->have_posts()) : while ($events_query->have_posts()) : $events_query->the_post() ?>
            <div class="w-full max-w-4xl m-auto md:border-b border-[#6666664c] pb-4 md:pb-12 md:mt-12 relative">
                <div class="flex flex-col md:flex-row border md:border-none border-[#865d015e] m-4 py-4 md:py-0 md:m-0">
                    <div class="w-full md:w-1/3 px-4 md:pl-8 md:pr-0 lg:pl-0">
                        <a href="<?php the_permalink(); ?>" class="block md:border border-[#BA9E5E5E] md:p-2">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div style="padding-bottom: 75%" class="relative w-full">
                                    <img class="absolute top-0 left-0 w-full h-full object-cover md:p-2" 
                                         src="<?php the_post_thumbnail_url(); ?>" 
                                         alt="<?php the_title(); ?>">
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="w-full md:w-2/3 px-4 md:px-8 md:pb-4">
                        <p class="mt-4 md:mt-0 text-[#ba9e5e] mb-2 uppercase text-xs tracking-[0.15rem]">
                            The Phoebes Gala
                        </p>
                        <a href="<?php the_permalink(); ?>" class="block">
                            <h2 class="text-2xl text-black"><?php the_title(); ?></h2>
                        </a>
                        <div class="category-desc text-[0.8rem] w-full md:w-2/3 pb-4 md:pb-0 text-black">
                            <?php
                            // Capture the content into a variable
                            $content = get_the_content();

                            // Remove shortcodes and HTML tags
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
                            <button class="py-4 px-8 border-[#ba9e5e] border-2 w-full text-center md:text-right">View Gallery</button>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; endif; 
        wp_reset_postdata(); ?>
    </div>
</main><!-- #main -->

<?php get_footer(); ?>