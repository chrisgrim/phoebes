<?php
/**
 * Author profile / filmography page.
 */

get_header();

$author_id = get_queried_object_id();
$author = get_userdata($author_id);

if (!$author) {
    echo '<main id="primary" class="site-main"><div class="max-w-[1400px] mx-auto px-8 md:px-16 py-16"><p>User not found.</p></div></main>';
    get_footer();
    return;
}

$display_name = $author->display_name;
$bio = get_the_author_meta('description', $author_id);
$avatar_url = get_avatar_url($author_id, array('size' => 400));
$has_gravatar = phoebes_has_gravatar($author_id);

// Films directed
$directed_query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'director_user',
            'value'   => '"' . $author_id . '"',
            'compare' => 'LIKE',
        ),
    ),
));

// Films acted in
$acted_query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'meta_query'     => array(
        array(
            'key'     => 'actor_user',
            'value'   => '"' . $author_id . '"',
            'compare' => 'LIKE',
        ),
    ),
));
?>

<main id="primary" class="site-main">
    <div class="max-w-[1400px] mx-auto px-8 md:px-16">

        <!-- Profile Header -->
        <div class="pt-12 pb-10">
            <div class="flex flex-col md:flex-row gap-8">
                <div class="shrink-0 w-full md:w-64">
                    <img src="<?php echo esc_url($avatar_url); ?>"
                         alt="<?php echo esc_attr($display_name); ?>"
                         class="w-full aspect-[3/4] object-cover rounded-lg bg-black border border-white" <?php if (!$has_gravatar) echo 'style="filter: invert(1);"'; ?>>
                </div>
                <div class="flex-1">
                    <h1 class="font-heading font-bold text-festival-black mb-4" style="font-size: 4rem; line-height: 4rem;"><?php echo esc_html(phoebes_short_name($display_name)); ?></h1>
                    <?php if ($bio) : ?>
                        <div class="prose text-festival-black text-xl leading-relaxed max-w-2xl">
                            <p><?php echo wp_kses_post($bio); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Directed Section -->
    <?php if ($directed_query->have_posts()) : ?>
        <div class="border-t border-festival-border">
            <div class="max-w-[1400px] mx-auto px-8 md:px-16 pt-10 pb-16">
                <h2 class="font-heading font-bold text-festival-black mb-8 text-3xl">Directed</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php while ($directed_query->have_posts()) : $directed_query->the_post();
                        $post_categories = get_the_category();
                        $cat_name = !empty($post_categories) ? $post_categories[0]->name : '';
                    ?>
                        <a href="<?php the_permalink(); ?>" class="group block">
                            <?php if ($cat_name) : ?>
                                <p class="text-festival-gray mb-2" style="font-size: 13px; line-height: 17px; letter-spacing: .1px;"><?php echo esc_html($cat_name); ?></p>
                            <?php endif; ?>
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
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Acted In Section -->
    <?php if ($acted_query->have_posts()) : ?>
        <div class="border-t border-festival-border">
            <div class="max-w-[1400px] mx-auto px-8 md:px-16 pt-10 pb-16">
                <h2 class="font-heading font-bold text-festival-black mb-8 text-3xl">Acted In</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php while ($acted_query->have_posts()) : $acted_query->the_post();
                        $post_categories = get_the_category();
                        $cat_name = !empty($post_categories) ? $post_categories[0]->name : '';
                    ?>
                        <a href="<?php the_permalink(); ?>" class="group block">
                            <?php if ($cat_name) : ?>
                                <p class="text-festival-gray mb-2" style="font-size: 13px; line-height: 17px; letter-spacing: .1px;"><?php echo esc_html($cat_name); ?></p>
                            <?php endif; ?>
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
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Commercials Section -->
    <?php
    $commercial_cat = get_category_by_slug('commercial');
    if (!$commercial_cat) {
        $commercial_cat = get_category_by_slug('commercials');
    }
    if ($commercial_cat) :
        $commercial_query = new WP_Query(array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'category__in'   => array($commercial_cat->term_id),
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => 'director_user',
                    'value'   => '"' . $author_id . '"',
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => 'actor_user',
                    'value'   => '"' . $author_id . '"',
                    'compare' => 'LIKE',
                ),
            ),
        ));

        if ($commercial_query->have_posts()) :
    ?>
        <div class="border-t border-festival-border">
            <div class="max-w-[1400px] mx-auto px-8 md:px-16 pt-10 pb-16">
                <h2 class="font-heading font-bold text-festival-black mb-8 text-3xl">Commercials</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php while ($commercial_query->have_posts()) : $commercial_query->the_post();
                        $post_categories = get_the_category();
                        $cat_name = !empty($post_categories) ? $post_categories[0]->name : '';
                    ?>
                        <a href="<?php the_permalink(); ?>" class="group block">
                            <?php if ($cat_name) : ?>
                                <p class="text-festival-gray mb-2" style="font-size: 13px; line-height: 17px; letter-spacing: .1px;"><?php echo esc_html($cat_name); ?></p>
                            <?php endif; ?>
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
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    <?php
        endif;
    endif;
    ?>

</main><!-- #main -->

<?php get_footer(); ?>
