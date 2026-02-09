<?php
/* Template Name: Events */
get_header();
?>

<main id="primary" class="site-main">
    <div class="max-w-[1400px] mx-auto px-8 md:px-16">
        <div class="pt-12 pb-6">
            <h1 class="font-heading font-bold text-festival-black leading-none"><?php the_title(); ?></h1>
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

        if ($events_query->have_posts()) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pb-16">
                <?php while ($events_query->have_posts()) : $events_query->the_post() ?>
                    <a href="<?php the_permalink(); ?>" class="group block">
                        <p class="text-festival-gray mb-2" style="font-size: 13px; line-height: 17px; letter-spacing: .1px;">The Phoebes Gala</p>
                        <div class="overflow-hidden rounded-lg bg-festival-light mb-3">
                            <?php if (has_post_thumbnail()) : ?>
                                <img class="w-full aspect-video object-cover group-hover:scale-105 transition-transform duration-300" src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php else : ?>
                                <div class="w-full aspect-video bg-festival-light flex items-center justify-center">
                                    <span class="text-festival-gray text-sm">No image</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="font-heading text-lg text-festival-black mb-1 group-hover:text-festival-gold transition-colors"><?php the_title(); ?></h3>
                        <p class="text-festival-gray mb-0 line-clamp-3"><?php
                            $content = get_the_content();
                            $content = strip_shortcodes($content);
                            $content = wp_strip_all_tags($content);
                            if (strlen($content) > 120) {
                                $content = substr($content, 0, 120) . '...';
                            }
                            echo esc_html($content);
                        ?></p>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </div>
</main><!-- #main -->

<?php get_footer(); ?>
