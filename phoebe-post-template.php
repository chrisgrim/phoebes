<?php

/*
Template Name: Phoebes Post Template
Template Post Type: post
*/

// Force navigation script to load last
add_action('wp_footer', function() {
    wp_dequeue_script('phoebes-navigation');
    wp_enqueue_script('phoebes-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);
}, 100);

get_header();
?>

    <main id="primary" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();

            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $video_url = get_post_meta(get_the_ID(), '_video_url', true);
            $post_year = get_the_date('Y');
            $categories = get_the_category();
            $director_user_ids = get_post_meta(get_the_ID(), 'director_user', true);
            $actor_user_ids = get_post_meta(get_the_ID(), 'actor_user', true);
        ?>

            <div class="max-w-[1400px] mx-auto px-8 md:px-16">

                <!-- Category Label -->
                <?php if (!empty($categories)) : ?>
                    <p class="text-festival-black text-md pt-6 mb-3">
                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="hover:text-festival-gold transition-colors"><?php echo esc_html($categories[0]->name); ?></a>
                    </p>
                <?php endif; ?>

                <!-- Film Title -->
                <h1 class="font-heading font-bold text-festival-black mb-4" style="font-size: 4rem; line-height: 4rem;"><?php the_title(); ?></h1>

                <!-- Metadata Line -->
                <p class="text-festival-black text-md mb-4">
                    <?php if (!empty($categories)) : ?>
                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="hover:text-festival-gold transition-colors"><?php echo esc_html($post_year); ?></a>
                        <span class="mx-2">&bull;</span>
                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="hover:text-festival-gold transition-colors"><?php echo esc_html($categories[0]->name); ?></a>
                    <?php else : ?>
                        <?php echo esc_html($post_year); ?>
                    <?php endif; ?>
                </p>

                <!-- Film Still Image / Video Player -->
                <?php if ($thumbnail) : ?>
                    <div id="film-media" class="max-w-4xl overflow-hidden rounded-lg mb-10 <?php echo $video_url ? 'cursor-pointer' : ''; ?>">
                        <div id="film-poster" class="relative">
                            <img class="w-full aspect-video object-cover" src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php if ($video_url) : ?>
                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 hover:bg-opacity-40 transition-colors">
                                    <div class="w-20 h-20 rounded-full bg-white bg-opacity-90 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-festival-black ml-1" fill="currentColor" viewBox="0 0 24 24">
                                            <polygon points="5,3 19,12 5,21"></polygon>
                                        </svg>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div id="film-video-container" class="hidden aspect-video"></div>
                    </div>
                    <?php if ($video_url) : ?>
                        <script>
                            document.getElementById('film-poster').addEventListener('click', function() {
                                var poster = document.getElementById('film-poster');
                                var container = document.getElementById('film-video-container');
                                var videoUrl = '<?php echo esc_url($video_url); ?>';
                                var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                                poster.style.display = 'none';
                                container.classList.remove('hidden');

                                container.innerHTML = '<video id="filmVideoPlayer" class="w-full h-full" style="object-fit: contain; background: #000;" controls playsinline webkit-playsinline x5-playsinline preload="auto"><source src="' + videoUrl + '" type="video/mp4">Your browser does not support the video tag.</video>';

                                var video = document.getElementById('filmVideoPlayer');

                                if (isMobile) {
                                    function enterFullScreen(element) {
                                        if (element.requestFullscreen) {
                                            element.requestFullscreen();
                                        } else if (element.webkitRequestFullscreen) {
                                            element.webkitRequestFullscreen();
                                        } else if (element.webkitEnterFullscreen) {
                                            element.webkitEnterFullscreen();
                                        }
                                    }

                                    var playPromise = video.play();
                                    if (playPromise !== undefined) {
                                        playPromise.then(function() {
                                            setTimeout(function() {
                                                enterFullScreen(video);
                                            }, 100);
                                        }).catch(function() {
                                            video.addEventListener('play', function onFirstPlay() {
                                                enterFullScreen(video);
                                                video.removeEventListener('play', onFirstPlay);
                                            });
                                        });
                                    }
                                } else {
                                    video.play();
                                }

                                video.addEventListener('error', function() {
                                    alert('Error loading video. Please try again.');
                                });
                            });
                        </script>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Synopsis -->
                <div class="max-w-3xl mb-10">
                    <div class="prose text-festival-black text-xl leading-relaxed [&_p]:text-xl [&_p]:leading-relaxed">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Category Pills -->
                <?php if (!empty($categories)) : ?>
                    <div class="flex flex-wrap gap-2 mb-12">
                        <?php foreach ($categories as $cat) : ?>
                            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="inline-block px-4 py-1.5 rounded-lg border border-festival-border text-festival-black text-sm font-bold hover:bg-festival-light transition-colors">
                                <?php echo esc_html($cat->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Credits Section - border spans full width -->
            <?php if (!empty($director_user_ids) || !empty($actor_user_ids)) : ?>
                <div class="border-t border-festival-border">
                    <div class="max-w-[1400px] mx-auto px-8 md:px-16 pt-10 mb-12">
                        <h2 class="font-heading font-bold text-festival-black mb-8 text-3xl">Credits</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                            <?php if (!empty($director_user_ids)) : ?>
                                <div>
                                    <p class="text-md uppercase tracking-widest text-festival-black mb-2 font-bold">
                                        <?php echo count($director_user_ids) > 1 ? 'DIRECTOR(S)' : 'DIRECTOR'; ?>
                                    </p>
                                    <?php foreach ($director_user_ids as $director_id) :
                                        $user_info = get_userdata($director_id);
                                        if ($user_info) : ?>
                                            <p class="text-festival-black text-md mb-1"><a href="<?php echo esc_url(get_author_posts_url($director_id)); ?>" class="hover:text-festival-gold transition-colors"><?php echo esc_html(phoebes_short_name($user_info->display_name)); ?></a></p>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($actor_user_ids)) : ?>
                                <div>
                                    <p class="text-md uppercase tracking-widest text-festival-black mb-2 font-bold">
                                        <?php echo count($actor_user_ids) > 1 ? 'ACTORS' : 'ACTOR'; ?>
                                    </p>
                                    <?php foreach ($actor_user_ids as $actor_id) :
                                        $user_info = get_userdata($actor_id);
                                        if ($user_info) : ?>
                                            <p class="text-festival-black text-md mb-1"><a href="<?php echo esc_url(get_author_posts_url($actor_id)); ?>" class="hover:text-festival-gold transition-colors"><?php echo esc_html(phoebes_short_name($user_info->display_name)); ?></a></p>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Meet the Artist Section -->
            <?php if (!empty($director_user_ids)) : ?>
                <div class="border-t border-festival-border">
                    <div class="max-w-[1400px] mx-auto px-8 md:px-16 pt-10 mb-12">
                        <h2 class="font-heading font-bold text-festival-black mb-8 text-3xl">Meet the Director</h2>
                        <div class="space-y-12">
                            <?php 
                            $director_count = count($director_user_ids);
                            $i = 0;
                            foreach ($director_user_ids as $director_id) :
                                $user_info = get_userdata($director_id);
                                if ($user_info) : 
                                    $bio = get_the_author_meta('description', $director_id);
                                    $avatar_url = get_avatar_url($director_id, array('size' => 400));
                                    $has_gravatar = phoebes_has_gravatar($director_id);
                                    $i++;
                            ?>
                                <div class="flex flex-col md:flex-row gap-8">
                                    <div class="shrink-0 w-full md:w-64">
                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($user_info->display_name); ?>" class="w-full aspect-[3/4] object-cover rounded-lg bg-black border border-white" <?php if (!$has_gravatar) echo 'style="filter: invert(1);"'; ?>>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-2xl font-heading font-bold text-festival-black mb-4 flex items-center gap-3">
                                            <a href="<?php echo esc_url(get_author_posts_url($director_id)); ?>" class="hover:text-festival-gold transition-colors"><?php echo esc_html(phoebes_short_name($user_info->display_name)); ?></a>
                                        </h3>
                                        <div class="prose text-festival-black text-base leading-relaxed max-w-2xl">
                                            <p><?php echo wp_kses_post($bio); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($i < $director_count) : ?>
                                    <div class="border-t border-festival-border my-8"></div>
                                <?php endif; ?>
                            <?php endif;
                            endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- You May Also Like - border spans full width -->
            <?php
            if (!empty($categories)) :
                $related_query = new WP_Query(array(
                    'category__in'   => array($categories[0]->term_id),
                    'post__not_in'   => array(get_the_ID()),
                    'posts_per_page' => 4,
                    'meta_key'       => 'ranking',
                    'orderby'        => array('meta_value_num' => 'DESC', 'date' => 'DESC'),
                ));

                if ($related_query->have_posts()) :
            ?>
                <div class="border-t border-festival-border">
                    <div class="max-w-[1400px] mx-auto px-8 md:px-16 pt-10 pb-16">
                        <h2 class="font-heading font-bold text-festival-black mb-8 text-2xl">You May Also Like</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                            <?php while ($related_query->have_posts()) : $related_query->the_post();
                                $related_categories = get_the_category();
                                $related_cat_name = !empty($related_categories) ? $related_categories[0]->name : '';
                            ?>
                                <a href="<?php the_permalink(); ?>" class="group block">
                                    <?php if ($related_cat_name) : ?>
                                        <p class="text-festival-gray mb-2" style="font-size: 13px; line-height: 17px; letter-spacing: .1px;"><?php echo esc_html($related_cat_name); ?></p>
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
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            <?php
                endif;
                wp_reset_postdata();
            endif;
            ?>

            <?php get_template_part('template-parts/sponsors-slider'); ?>

        <?php
        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileNav = document.getElementById('mobile-navigation');
            const button = document.querySelector('.mobile-menu-toggle');

            if (!mobileNav || !button) return;

            button.addEventListener('click', function(e) {
                e.preventDefault();
                mobileNav.classList.toggle('toggled');
                button.classList.toggle('toggled');
                const isExpanded = mobileNav.classList.contains('toggled');
                button.setAttribute('aria-expanded', isExpanded);
            });
        });
    </script>

<?php
get_footer();
