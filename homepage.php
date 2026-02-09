<?php
/**
 * Template Name: Homepage Template
 *
 * @package GeneratePress
 */

get_header();
?>

	<main id="primary" class="site-main">


        <!-- Festival Editions Slider -->
        <?php
        $annual_categories = get_categories([
            'orderby' => 'name',
            'order'   => 'DESC',
            'include' => array(
                get_category_by_slug('2nd-annual') ? get_category_by_slug('2nd-annual')->term_id : 0,
                get_category_by_slug('1st-annual') ? get_category_by_slug('1st-annual')->term_id : 0
            )
        ]);

        if (!empty($annual_categories)) :
            $edition_slides = [];
            foreach ($annual_categories as $cat) {
                $image_url = phoebes_get_category_image_url($cat->term_id);
                // Derive year from slug: "1st-annual" → 2024, "2nd-annual" → 2025
                $cat_year = '';
                if (preg_match('/^(\d+)/', $cat->slug, $m)) {
                    $cat_year = 2023 + (int)$m[1];
                }
                $edition_slides[] = [
                    'name'        => $cat->name,
                    'year'        => $cat_year,
                    'description' => $cat->description,
                    'image'       => $image_url,
                    'link'        => get_category_link($cat->term_id),
                ];
            }
            $edition_count = count($edition_slides);
        ?>
        <!-- Mobile slider (stacked) -->
        <div class="md:hidden w-full overflow-hidden">
            <div id="edition-track-mobile" class="flex transition-transform duration-500 ease-in-out">
                <?php foreach ($edition_slides as $i => $slide) : ?>
                    <div class="edition-slide-mobile shrink-0 w-full">
                        <!-- Image full width -->
                        <?php if ($slide['image']) : ?>
                            <img src="<?php echo esc_url($slide['image']); ?>"
                                 alt="<?php echo esc_attr($slide['name']); ?>"
                                 class="w-full object-cover" style="height: 260px;">
                        <?php else : ?>
                            <div class="w-full bg-gray-300 flex items-center justify-center" style="height: 260px;">
                                <span class="text-festival-gray">No image</span>
                            </div>
                        <?php endif; ?>
                        <!-- Content -->
                        <div class="px-8 py-6 text-center">
                            <h2 class="font-heading font-bold text-festival-black text-2xl mb-4"><?php echo esc_html($slide['year'] . ' ' . $slide['name']); ?> Phoebe's Film Festival</h2>
                            <?php if ($slide['description']) : ?>
                                <p class="text-festival-black text-base leading-relaxed mb-6"><?php echo esc_html($slide['description']); ?></p>
                            <?php endif; ?>
                            <div class="flex items-center justify-between">
                                <button class="edition-prev-mobile w-10 h-10 rounded-full bg-white text-black flex items-center justify-center bg-transparent border-0 p-0 cursor-pointer" aria-label="Previous" style="background: #ffffff;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <a href="<?php echo esc_url($slide['link']); ?>"
                                   class="inline-block px-8 py-3 bg-white text-black text-base font-bold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                    See Films
                                </a>
                                <button class="edition-next-mobile w-10 h-10 rounded-full bg-white text-black flex items-center justify-center bg-transparent border-0 p-0 cursor-pointer" aria-label="Next" style="background: #ffffff;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Desktop slider (side by side with peek) -->
        <div class="hidden md:block w-full overflow-hidden" style="background: #ffffff;">
            <div id="edition-track" class="flex transition-transform duration-500 ease-in-out" style="gap: 16px; padding: 20px 0;">
                <?php foreach ($edition_slides as $i => $slide) : ?>
                    <div class="edition-slide shrink-0 flex flex-row" style="width: calc(100vw - 80px); gap: 16px; padding: 0 4px;">
                        <!-- Image (2/3) -->
                        <div class="w-2/3 shrink-0">
                            <?php if ($slide['image']) : ?>
                                <img src="<?php echo esc_url($slide['image']); ?>"
                                     alt="<?php echo esc_attr($slide['name']); ?>"
                                     class="w-full rounded-xl object-cover" style="height: 520px;">
                            <?php else : ?>
                                <div class="w-full rounded-xl bg-gray-600 flex items-center justify-center" style="height: 520px;">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Content (1/3) -->
                        <div class="w-1/3">
                            <div class="bg-festival-light rounded-xl p-8 flex flex-col justify-center" style="height: 520px;">
                                <h2 class="font-heading font-bold text-festival-black text-3xl mb-4"><?php echo esc_html($slide['year'] . ' ' . $slide['name']); ?> Phoebe's Film Festival</h2>
                                <?php if ($slide['description']) : ?>
                                    <p class="text-festival-black text-base leading-relaxed mb-6"><?php echo esc_html($slide['description']); ?></p>
                                <?php endif; ?>
                                <div>
                                    <a href="<?php echo esc_url($slide['link']); ?>"
                                       class="inline-block px-8 py-3 bg-white text-black text-base font-bold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                        See Films
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div><!-- close gray band -->

            <?php if ($edition_count > 1) : ?>
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center justify-center gap-4 py-6">
                    <button id="edition-prev" class="w-10 h-10 rounded-full bg-white text-black flex items-center justify-center hover:bg-gray-800 transition-colors duration-200" aria-label="Previous">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="flex gap-3" id="edition-dots">
                        <?php foreach ($edition_slides as $i => $slide) : ?>
                            <button class="edition-dot w-3 h-3 rounded-full transition-colors duration-200 <?php echo $i === 0 ? 'bg-festival-black' : 'bg-gray-300'; ?>"
                                    data-slide="<?php echo $i; ?>"
                                    aria-label="<?php echo esc_attr($slide['name']); ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <button id="edition-next" class="w-10 h-10 rounded-full bg-white text-black flex items-center justify-center hover:bg-gray-800 transition-colors duration-200" aria-label="Next">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <script>
                (function() {
                    var totalSlides = <?php echo $edition_count; ?>;

                    // Desktop slider
                    var currentSlide = 0;
                    var track = document.getElementById('edition-track');
                    var slides = track.querySelectorAll('.edition-slide');
                    var dots = document.querySelectorAll('#edition-dots .edition-dot');
                    var prevBtn = document.getElementById('edition-prev');
                    var nextBtn = document.getElementById('edition-next');
                    var gap = 16;

                    function positionSlide(index) {
                        var slideWidth = slides[0].offsetWidth;
                        var vw = window.innerWidth;
                        var offset = (vw - slideWidth) / 2;
                        var tx = -(index * (slideWidth + gap)) + offset;
                        track.style.transform = 'translateX(' + tx + 'px)';
                    }

                    function goToSlide(target) {
                        if (target < 0 || target >= totalSlides || target === currentSlide) return;
                        dots[currentSlide].classList.remove('bg-festival-black');
                        dots[currentSlide].classList.add('bg-gray-300');
                        dots[target].classList.remove('bg-gray-300');
                        dots[target].classList.add('bg-festival-black');
                        currentSlide = target;
                        positionSlide(currentSlide);
                    }

                    positionSlide(0);
                    window.addEventListener('resize', function() { positionSlide(currentSlide); });

                    dots.forEach(function(dot) {
                        dot.addEventListener('click', function() {
                            goToSlide(parseInt(this.dataset.slide));
                        });
                    });
                    prevBtn.addEventListener('click', function() { goToSlide(currentSlide - 1); });
                    nextBtn.addEventListener('click', function() { goToSlide(currentSlide + 1); });

                    // Mobile slider
                    var mobileSlide = 0;
                    var mobileTrack = document.getElementById('edition-track-mobile');
                    var mobilePrevBtns = document.querySelectorAll('.edition-prev-mobile');
                    var mobileNextBtns = document.querySelectorAll('.edition-next-mobile');

                    function goToMobileSlide(target) {
                        if (target < 0 || target >= totalSlides || target === mobileSlide) return;
                        mobileSlide = target;
                        mobileTrack.style.transform = 'translateX(-' + (mobileSlide * 100) + '%)';
                    }

                    mobilePrevBtns.forEach(function(btn) {
                        btn.addEventListener('click', function() { goToMobileSlide(mobileSlide - 1); });
                    });
                    mobileNextBtns.forEach(function(btn) {
                        btn.addEventListener('click', function() { goToMobileSlide(mobileSlide + 1); });
                    });
                })();
                </script>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Explore All Films -->
        <div class="max-w-[1400px] mx-auto px-8 md:px-16 pb-16 mt-20">
            <h1 class="font-heading font-bold text-festival-black leading-none mb-4">Explore all the Phoebe's films</h1>
            <p class="text-festival-gray text-lg max-w-3xl mb-6">
                Since 2024, the Corner presents the Phoebe's&ndash;the phirst critically acclaimed philm phestival Petaluma has never seen. Join us as we gather in celebration of independent storytelling, obscure creativity, and general silliness gracing the greater North Bay Area (and beyond).
            </p>
            <?php
            $all_films_page = get_pages(['meta_key' => '_wp_page_template', 'meta_value' => 'all-posts.php']);
            $all_films_url = !empty($all_films_page) ? get_permalink($all_films_page[0]->ID) : home_url('/');
            ?>
            <a href="<?php echo esc_url($all_films_url); ?>"
               class="inline-block px-8 py-3 bg-white text-black text-base font-bold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                See All Films
            </a>
        </div>

        <?php get_template_part('template-parts/sponsors-slider'); ?>
	</main><!-- #main -->

<?php
get_footer();
