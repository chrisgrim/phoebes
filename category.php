<?php

/*
Template Name: Category Template
*/

get_header();

// Get current category
$current_cat = get_queried_object();
// Get subcategories of current category
$subcategories = get_categories(array(
    'parent' => $current_cat->term_id,
    'hide_empty' => true
));

// Query for posts
$args = array(
    'category__in' => array($current_cat->term_id),
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $current_cat->term_id,
            'include_children' => true
        ),
    ),
);

$category_query = new WP_Query($args);
?>

<main id="primary" class="site-main">
    <div class="max-w-[1400px] mx-auto px-8 md:px-16">
        <div class="pt-16 pb-6">
            <h1 class="font-heading font-bold text-festival-black leading-none"><?php single_cat_title(); ?></h1>

            <?php if (!empty($subcategories)) : ?>
                <!-- Desktop: joined button bar, right-aligned -->
                <div class="hidden md:flex justify-end mt-8 mb-8">
                    <div class="subcategory-filters inline-flex">
                        <?php
                        $total = count($subcategories);
                        $index = 0;
                        foreach ($subcategories as $subcat) :
                            $index++;
                            $is_first = ($index === 1);
                            $is_last = ($index === $total);
                            $round = '';
                            if ($is_first) $round = 'rounded-l-lg';
                            if ($is_last) $round = 'rounded-r-lg';
                        ?>
                            <button class="filter-btn rounded-none <?php echo $round; ?> py-2 px-6 text-festival-black text-sm font-bold hover:bg-festival-light transition-colors duration-200 <?php echo !$is_first ? '-ml-px' : ''; ?>"
                                    style="border: 1px solid #ffffff;"
                                    data-category="<?php echo esc_attr($subcat->slug); ?>">
                                <?php echo esc_html($subcat->name); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Mobile: dropdown -->
                <div class="md:hidden mt-6 mb-6">
                    <select id="mobile-category-filter" class="w-full py-3 px-4 text-festival-black text-sm font-bold rounded-lg bg-black cursor-pointer appearance-none" style="border: 1px solid #ffffff; background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2212%22%20height%3D%2212%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23ffffff%22%20stroke-width%3D%222.5%22%3E%3Cpath%20d%3D%22M6%209l6%206%206-6%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center;">
                        <option value="">All</option>
                        <?php foreach ($subcategories as $subcat) : ?>
                            <option value="<?php echo esc_attr($subcat->slug); ?>"><?php echo esc_html($subcat->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($category_query->have_posts()) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pb-16">
                <?php while ($category_query->have_posts()) : $category_query->the_post();
                    $post_categories = get_the_terms(get_the_ID(), 'category');
                    $category_slugs = array();

                    if ($post_categories && !is_wp_error($post_categories)) {
                        foreach ($post_categories as $category) {
                            $category_slugs[] = $category->slug;
                            if ($category->parent) {
                                $parent_cat = get_term($category->parent, 'category');
                                if ($parent_cat && !is_wp_error($parent_cat)) {
                                    $category_slugs[] = $parent_cat->slug;
                                }
                            }
                        }
                    }

                    $category_slugs = array_unique($category_slugs);

                    // Get subcategory name for label
                    $subcats = array_filter($post_categories ?: [], function($cat) use ($current_cat) {
                        return $cat->parent === $current_cat->term_id;
                    });
                    $subcat_name = !empty($subcats) ? reset($subcats)->name : '';
                ?>
                    <a href="<?php the_permalink(); ?>" class="post-item group block"
                       data-categories="<?php echo esc_attr(implode(' ', $category_slugs)); ?>">
                        <?php if ($subcat_name) : ?>
                            <p class="text-festival-gray mb-2" style="font-size: 13px; line-height: 17px; letter-spacing: .1px;"><?php echo esc_html($subcat_name); ?></p>
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
        <?php else : ?>
            <p class="text-festival-gray py-12">No posts found.</p>
        <?php endif; ?>
    </div>

    <?php
    // Determine which year to start on based on annual category
    // "1st-annual" = 2024, "2nd-annual" = 2025, etc.
    $cat_to_check = $current_cat;
    if ($cat_to_check->parent) {
        $parent = get_term($cat_to_check->parent, 'category');
        if ($parent && !is_wp_error($parent)) {
            $cat_to_check = $parent;
        }
    }
    $initial_year = null;
    if (preg_match('/^(\d+)/', $cat_to_check->slug, $matches)) {
        $initial_year = 2023 + (int)$matches[1];
    }
    get_template_part('template-parts/sponsors-slider', null, ['initial_year' => $initial_year]);
    ?>
</main>

<?php
// Enqueue category filter script
wp_enqueue_script('phoebes-category-filters', get_template_directory_uri() . '/js/category-filters.js', array(), '1.0.0', true);

get_footer();
?>
