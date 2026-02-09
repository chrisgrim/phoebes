<?php
/**
 * Sponsors slider - shows sponsors grouped by year category.
 * Sponsor categories follow the pattern: "2024-sponsors", "2025-sponsors", etc.
 * Falls back to "advertisement" category if no year-based categories exist.
 */

// Find all year-based sponsor categories
$all_cats = get_categories(['hide_empty' => true]);
$sponsor_cats = array_filter($all_cats, function($cat) {
    return preg_match('/^\d{4}-sponsors$/', $cat->slug);
});

// Sort newest year first
usort($sponsor_cats, function($a, $b) {
    return strcmp($b->slug, $a->slug);
});

// Fallback to "advertisement" category
if (empty($sponsor_cats)) {
    $advert_cat = get_category_by_slug('advertisement');
    if ($advert_cat) {
        $sponsor_cats = [$advert_cat];
    }
}

if (empty($sponsor_cats)) return;

// Build slides data
$slides = [];
foreach ($sponsor_cats as $cat) {
    $sponsor_query = new WP_Query([
        'cat' => $cat->term_id,
        'posts_per_page' => -1,
    ]);

    $posts_with_thumbs = [];
    if ($sponsor_query->have_posts()) {
        while ($sponsor_query->have_posts()) {
            $sponsor_query->the_post();
            if (has_post_thumbnail()) {
                $posts_with_thumbs[] = [
                    'id'    => get_the_ID(),
                    'url'   => get_permalink(),
                    'img'   => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                    'title' => get_the_title(),
                ];
            }
        }
    }
    wp_reset_postdata();

    if (!empty($posts_with_thumbs)) {
        preg_match('/^(\d{4})/', $cat->slug, $m);
        $year_label = $m ? $m[1] . ' SPONSORS' : strtoupper($cat->name);
        $slides[] = [
            'label' => $year_label,
            'posts' => $posts_with_thumbs,
        ];
    }
}

if (empty($slides)) return;

$slider_id = 'sponsor-slider-' . wp_unique_id();
$slide_count = count($slides);

// Determine initial slide from passed args (e.g., initial_year => 2024)
$initial_slide = 0;
$initial_year = isset($args['initial_year']) ? $args['initial_year'] : null;
if ($initial_year) {
    foreach ($slides as $i => $s) {
        if (strpos($s['label'], (string)$initial_year) === 0) {
            $initial_slide = $i;
            break;
        }
    }
}
?>

<div id="Sponsors" class="border-t border-festival-border bg-white">
    <div class="max-w-[1400px] mx-auto px-8 md:px-16 py-16">
        <div class="relative flex items-center" id="<?php echo esc_attr($slider_id); ?>">

            <!-- Vertical label left -->
            <div class="hidden md:flex items-center justify-center shrink-0 w-16">
                <span class="sponsor-label-left uppercase tracking-widest text-sm font-bold text-black whitespace-nowrap transition-opacity duration-300"
                      style="writing-mode: vertical-rl; transform: rotate(180deg);">
                    <?php echo esc_html($slides[$initial_slide]['label']); ?>
                </span>
            </div>

            <!-- Slides -->
            <div class="flex-1 min-w-0 overflow-hidden">
                <!-- Mobile label -->
                <p class="md:hidden text-sm font-bold uppercase tracking-widest text-black text-center mb-8 sponsor-label-mobile transition-opacity duration-300">
                    <?php echo esc_html($slides[$initial_slide]['label']); ?>
                </p>

                <div class="sponsor-track flex transition-transform duration-500 ease-in-out" style="width: <?php echo $slide_count * 100; ?>%; transform: translateX(-<?php echo $initial_slide * (100 / $slide_count); ?>%);">
                    <?php foreach ($slides as $i => $slide) : ?>
                        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16" style="width: <?php echo 100 / $slide_count; ?>%;">
                            <?php foreach ($slide['posts'] as $sponsor) : ?>
                                <a class="block w-1/3 md:w-40 px-2 md:px-0" href="<?php echo esc_url($sponsor['url']); ?>">
                                    <img src="<?php echo esc_url($sponsor['img']); ?>"
                                         alt="<?php echo esc_attr($sponsor['title']); ?>"
                                         class="hover:scale-110 transition-transform duration-200 transform">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Vertical label right -->
            <div class="hidden md:flex items-center justify-center shrink-0 w-16">
                <span class="sponsor-label-right uppercase tracking-widest text-sm font-bold text-black whitespace-nowrap transition-opacity duration-300"
                      style="writing-mode: vertical-rl;">
                    <?php echo esc_html($slides[$initial_slide]['label']); ?>
                </span>
            </div>
        </div>

        <?php if ($slide_count > 1) : ?>
            <!-- Dot navigation -->
            <div class="flex justify-center gap-3 mt-10">
                <?php foreach ($slides as $i => $slide) : ?>
                    <button class="sponsor-dot w-3 h-3 rounded-full transition-colors duration-200 <?php echo $i === $initial_slide ? 'bg-black' : 'bg-gray-300'; ?>"
                            data-slide="<?php echo $i; ?>"
                            aria-label="<?php echo esc_attr($slide['label']); ?>"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($slide_count > 1) : ?>
<script>
(function() {
    var container = document.getElementById('<?php echo esc_js($slider_id); ?>');
    if (!container) return;

    var currentSlide = <?php echo $initial_slide; ?>;
    var totalSlides = <?php echo $slide_count; ?>;
    var track = container.querySelector('.sponsor-track');
    var wrapper = container.closest('#Sponsors');
    var dots = wrapper.querySelectorAll('.sponsor-dot');
    var labelLeft = container.querySelector('.sponsor-label-left');
    var labelRight = container.querySelector('.sponsor-label-right');
    var labelMobile = container.querySelector('.sponsor-label-mobile');
    var labels = <?php echo json_encode(array_column($slides, 'label')); ?>;

    function goToSlide(target) {
        if (target === currentSlide || target < 0 || target >= totalSlides) return;

        // Update dots
        dots[currentSlide].classList.remove('bg-black');
        dots[currentSlide].classList.add('bg-gray-300');
        dots[target].classList.remove('bg-gray-300');
        dots[target].classList.add('bg-black');

        // Slide the track
        track.style.transform = 'translateX(-' + (target * (100 / totalSlides)) + '%)';

        // Fade labels out, swap text, fade back in
        [labelLeft, labelRight, labelMobile].forEach(function(el) {
            if (!el) return;
            el.style.opacity = '0';
            setTimeout(function() {
                el.textContent = labels[target];
                el.style.opacity = '1';
            }, 250);
        });

        currentSlide = target;
    }

    dots.forEach(function(dot) {
        dot.addEventListener('click', function() {
            goToSlide(parseInt(this.dataset.slide));
        });
    });
})();
</script>
<?php endif; ?>
