<?php
/* Template Name: All Posts */
get_header();

// Gather all annual categories and their children for filter options
$annual_cats = get_categories([
    'orderby' => 'name',
    'order'   => 'DESC',
    'slug'    => ['1st-annual', '2nd-annual', '3rd-annual', '4th-annual', '5th-annual'],
    'hide_empty' => true,
]);

// Build year options with derived year
$year_filters = [];
foreach ($annual_cats as $cat) {
    if (preg_match('/^(\d+)/', $cat->slug, $m)) {
        $year = 2023 + (int)$m[1];
        $year_filters[] = [
            'slug' => $cat->slug,
            'label' => $year . ' — ' . $cat->name,
            'term_id' => $cat->term_id,
        ];
    }
}

// Build category (subcategory) options from children of annual cats
$category_filters = [];
$seen_slugs = [];
foreach ($annual_cats as $parent) {
    $children = get_categories(['parent' => $parent->term_id, 'hide_empty' => true]);
    foreach ($children as $child) {
        if (!in_array($child->slug, $seen_slugs)) {
            $category_filters[] = [
                'slug' => $child->slug,
                'label' => $child->name,
            ];
            $seen_slugs[] = $child->slug;
        }
    }
}
?>

<main id="primary" class="site-main">
    <div class="max-w-[1400px] mx-auto px-8 md:px-16">
        <div class="pt-12 pb-6 flex items-end justify-between">
            <h1 class="font-heading font-bold text-festival-black leading-none">All Films</h1>

            <!-- Filter + Search buttons -->
            <div class="flex items-center gap-4">
                <button id="filter-toggle" class="flex flex-col items-center gap-1 text-festival-black hover:text-festival-gold transition-colors bg-transparent border-0 p-0 cursor-pointer" aria-label="Filter">
                    <span class="text-xs font-bold uppercase tracking-widest">Filter</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <line x1="4" y1="8" x2="20" y2="8"/><line x1="4" y1="16" x2="20" y2="16"/>
                        <circle cx="9" cy="8" r="2" fill="currentColor"/><circle cx="15" cy="16" r="2" fill="currentColor"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Active filter pills -->
        <div id="active-filters" class="flex flex-wrap gap-2 pb-4" style="display: none;"></div>

        <!-- Filter Panel -->
        <div id="filter-panel" class="border-t border-festival-border overflow-hidden" style="display: none;">
            <div class="py-8">
                <div class="flex justify-end mb-6">
                    <button id="filter-close" class="text-festival-black hover:text-festival-gold transition-colors bg-transparent border-0 p-0 cursor-pointer" aria-label="Close filters">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-2xl">
                    <!-- Year Filter -->
                    <?php if (!empty($year_filters)) : ?>
                        <div>
                            <button class="filter-section-toggle flex items-center gap-3 w-full text-left mb-4 bg-transparent border-0 p-0 cursor-pointer" data-section="year-options">
                                <svg class="w-5 h-5 filter-icon-plus" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                                <svg class="w-5 h-5 filter-icon-minus hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>
                                <span class="text-xl font-bold text-festival-black">Year</span>
                            </button>
                            <div id="year-options" class="pl-8 space-y-3" style="display: none;">
                                <?php foreach ($year_filters as $yf) : ?>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" class="filter-checkbox" data-type="year" data-value="<?php echo esc_attr($yf['slug']); ?>"
                                               class="w-4 h-4 rounded border-gray-300">
                                        <span class="text-base text-festival-black group-hover:text-festival-gold transition-colors"><?php echo esc_html($yf['label']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Category Filter -->
                    <?php if (!empty($category_filters)) : ?>
                        <div>
                            <button class="filter-section-toggle flex items-center gap-3 w-full text-left mb-4 bg-transparent border-0 p-0 cursor-pointer" data-section="category-options">
                                <svg class="w-5 h-5 filter-icon-plus" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                                <svg class="w-5 h-5 filter-icon-minus hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>
                                <span class="text-xl font-bold text-festival-black">Category</span>
                            </button>
                            <div id="category-options" class="pl-8 space-y-3" style="display: none;">
                                <?php foreach ($category_filters as $cf) : ?>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" class="filter-checkbox" data-type="category" data-value="<?php echo esc_attr($cf['slug']); ?>"
                                               class="w-4 h-4 rounded border-gray-300">
                                        <span class="text-base text-festival-black group-hover:text-festival-gold transition-colors"><?php echo esc_html($cf['label']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        $all_posts_query = new WP_Query(array('posts_per_page' => -1));
        if ($all_posts_query->have_posts()) : ?>
            <div id="films-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 pb-16">
                <?php while ($all_posts_query->have_posts()) : $all_posts_query->the_post();
                    $post_categories = get_the_category();
                    $cat_names = [];
                    $cat_slugs = [];
                    $year_slugs = [];

                    if (!empty($post_categories)) {
                        foreach ($post_categories as $cat) {
                            $cat_names[] = $cat->name;
                            $cat_slugs[] = $cat->slug;
                            // Check if this is an annual category or has an annual parent
                            if (preg_match('/^\d+/', $cat->slug) && strpos($cat->slug, 'annual') !== false) {
                                $year_slugs[] = $cat->slug;
                            }
                            if ($cat->parent) {
                                $parent_cat = get_term($cat->parent, 'category');
                                if ($parent_cat && !is_wp_error($parent_cat)) {
                                    $cat_slugs[] = $parent_cat->slug;
                                    if (preg_match('/^\d+/', $parent_cat->slug) && strpos($parent_cat->slug, 'annual') !== false) {
                                        $year_slugs[] = $parent_cat->slug;
                                    }
                                }
                            }
                        }
                    }

                    $cat_slugs = array_unique($cat_slugs);
                    $year_slugs = array_unique($year_slugs);
                ?>
                    <a href="<?php the_permalink(); ?>" class="film-card group block"
                       data-years="<?php echo esc_attr(implode(' ', $year_slugs)); ?>"
                       data-categories="<?php echo esc_attr(implode(' ', $cat_slugs)); ?>">
                        <?php if (!empty($cat_names)) : ?>
                            <p class="text-festival-gray mb-2" style="font-size: 13px; line-height: 17px; letter-spacing: .1px;"><?php echo esc_html(implode(', ', $cat_names)); ?></p>
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
</main><!-- #main -->

<script>
(function() {
    var panel = document.getElementById('filter-panel');
    var toggleBtn = document.getElementById('filter-toggle');
    var closeBtn = document.getElementById('filter-close');
    var cards = document.querySelectorAll('.film-card');
    var checkboxes = document.querySelectorAll('.filter-checkbox');
    var activeFiltersEl = document.getElementById('active-filters');
    var sectionToggles = document.querySelectorAll('.filter-section-toggle');

    // Toggle filter panel
    toggleBtn.addEventListener('click', function() {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });
    closeBtn.addEventListener('click', function() {
        panel.style.display = 'none';
    });

    // Toggle filter sections (+ / -)
    sectionToggles.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var section = document.getElementById(this.dataset.section);
            var isOpen = section.style.display !== 'none';
            section.style.display = isOpen ? 'none' : 'block';
            var plus = this.querySelector('.filter-icon-plus');
            var minus = this.querySelector('.filter-icon-minus');
            if (isOpen) {
                plus.classList.remove('hidden');
                minus.classList.add('hidden');
            } else {
                plus.classList.add('hidden');
                minus.classList.remove('hidden');
            }
        });
    });

    // Filter logic
    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', applyFilters);
    });

    function applyFilters() {
        var activeYears = [];
        var activeCategories = [];

        checkboxes.forEach(function(cb) {
            if (cb.checked) {
                if (cb.dataset.type === 'year') activeYears.push(cb.dataset.value);
                if (cb.dataset.type === 'category') activeCategories.push(cb.dataset.value);
            }
        });

        // Update active filter pills
        updatePills(activeYears, activeCategories);

        cards.forEach(function(card) {
            var cardYears = card.dataset.years ? card.dataset.years.split(' ') : [];
            var cardCats = card.dataset.categories ? card.dataset.categories.split(' ') : [];

            var yearMatch = activeYears.length === 0 || activeYears.some(function(y) { return cardYears.indexOf(y) !== -1; });
            var catMatch = activeCategories.length === 0 || activeCategories.some(function(c) { return cardCats.indexOf(c) !== -1; });

            card.style.display = (yearMatch && catMatch) ? '' : 'none';
        });
    }

    function updatePills(years, categories) {
        var pills = [];
        years.forEach(function(slug) {
            var label = document.querySelector('[data-type="year"][data-value="' + slug + '"]');
            if (label) pills.push({ type: 'year', value: slug, text: label.parentElement.querySelector('span').textContent });
        });
        categories.forEach(function(slug) {
            var label = document.querySelector('[data-type="category"][data-value="' + slug + '"]');
            if (label) pills.push({ type: 'category', value: slug, text: label.parentElement.querySelector('span').textContent });
        });

        if (pills.length === 0) {
            activeFiltersEl.style.display = 'none';
            activeFiltersEl.innerHTML = '';
            return;
        }

        activeFiltersEl.style.display = 'flex';
        activeFiltersEl.innerHTML = pills.map(function(p) {
            return '<button class="remove-filter inline-flex items-center gap-1 px-4 py-1 rounded-lg border border-festival-black text-sm font-bold text-festival-black hover:bg-festival-light transition-colors" data-type="' + p.type + '" data-value="' + p.value + '">' +
                p.text +
                ' <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>' +
                '</button>';
        }).join('') +
        '<button id="clear-all-filters" class="text-sm font-bold text-festival-black underline hover:text-festival-gold transition-colors ml-2">Clear all</button>';

        // Bind remove buttons
        document.querySelectorAll('.remove-filter').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var cb = document.querySelector('.filter-checkbox[data-type="' + this.dataset.type + '"][data-value="' + this.dataset.value + '"]');
                if (cb) { cb.checked = false; applyFilters(); }
            });
        });
        document.getElementById('clear-all-filters').addEventListener('click', function() {
            checkboxes.forEach(function(cb) { cb.checked = false; });
            applyFilters();
        });
    }
})();
</script>

<?php get_footer(); ?>
