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
    <div class="w-full bg-white category-page">
        <div class="flex w-full justify-center pt-12 text-center flex-col">
            <h1 class="text-[#666666] text-2xl md:text-4xl font-medium uppercase tracking-[1.2rem]"><?php single_cat_title(); ?></h1>
            
            <?php if (!empty($subcategories)) : ?>
                <div class="subcategory-filters flex justify-center gap-4 mt-8 flex-wrap px-4">
                    <?php foreach ($subcategories as $subcat) : ?>
                        <button class="filter-btn py-2 px-4 border-[#ba9e5e] border text-[#ba9e5e] hover:bg-[#ba9e5e] hover:text-white transition-colors" 
                                data-category="<?php echo esc_attr($subcat->slug); ?>">
                            <?php echo esc_html($subcat->name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($category_query->have_posts()) : while ($category_query->have_posts()) : $category_query->the_post(); 
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
        ?>
            <div class="post-item w-full max-w-4xl m-auto md:border-b border-[#6666664c] pb-4 md:pb-12 md:mt-12 relative" 
                 data-categories="<?php echo esc_attr(implode(' ', $category_slugs)); ?>">
                <div class="flex flex-col md:flex-row border md:border-none border-[#865d015e] m-4 py-4 md:py-0 md:m-0">
                    <div class="w-full md:w-1/3 px-4 md:pl-8 md:pr-0 lg:pl-0">
                        <a href="<?php the_permalink(); ?>" class="block md:border border-[#BA9E5E5E]">
                            <?php if (has_post_thumbnail()) : ?>
                                <img class="md:p-2" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="w-full md:w-2/3 px-4 md:px-8 md:pb-4">
                        <p class="mt-4 md:mt-0 text-[#ba9e5e] mb-2 uppercase text-xs tracking-[0.15rem]">
                            <?php 
                            $subcats = array_filter($post_categories, function($cat) use ($current_cat) {
                                return $cat->parent === $current_cat->term_id;
                            });
                            if (!empty($subcats)) {
                                echo esc_html(reset($subcats)->name);
                            } else {
                                single_cat_title();
                            }
                            ?>
                        </p>
                        <a href="<?php the_permalink(); ?>" class="block">
                            <h2 class="text-2xl text-black"><?php the_title(); ?></h2>
                        </a>
                        <div class="category-desc text-[0.8rem] w-full md:w-2/3 pb-4 md:pb-0 text-black">
                            <?php
                            $content = get_the_content();
                            $content = strip_shortcodes($content);
                            $content = wp_strip_all_tags($content);
                            if (strlen($content) > 200) {
                                $content = substr($content, 0, 200) . '...';
                            }
                            echo $content;
                            ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="category-button block relative md:absolute md:bottom-12 md:right-8 lg:right-0">
                            <button class="py-4 px-8 border-[#ba9e5e] border-2 w-full text-center md:text-right">Watch Now</button>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; 
        wp_reset_postdata();
        else : ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const posts = document.querySelectorAll('.post-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all other buttons
                    filterButtons.forEach(btn => {
                        if (btn !== this) {
                            btn.classList.remove('active');
                        }
                    });

                    // Toggle current button
                    this.classList.toggle('active');
                    
                    // If no buttons are active, show all posts
                    const activeButtons = document.querySelectorAll('.filter-btn.active');
                    if (activeButtons.length === 0) {
                        posts.forEach(post => post.style.display = 'block');
                        return;
                    }
                    
                    // Filter posts based on active category
                    const selectedCategory = this.dataset.category;
                    posts.forEach(post => {
                        const postCategories = post.dataset.categories.split(' ');
                        const shouldShow = postCategories.includes(selectedCategory);
                        post.style.display = shouldShow ? 'block' : 'none';
                    });
                });
            });
        });
    </script>

    <style>
        .filter-btn.active {
            background-color: #ba9e5e;
            color: white;
        }
    </style>
</main>

<?php get_footer(); ?>