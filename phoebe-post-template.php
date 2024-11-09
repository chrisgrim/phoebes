<?php

/*
Template Name: Phoebes Post Template
Template Post Type: post
*/

get_header();
?>

    <main id="primary" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();
            
            // Check if there's a featured image and a video URL
            if (has_post_thumbnail() && ($video_url = get_post_meta(get_the_ID(), '_video_url', true))) {
                // Get the featured image
                $thumbnail = get_the_post_thumbnail_url();

                ?>
                <div id="initial-content" class="info-section">
                    <div class="info-section absolute z-10 w-full bg-gradient-to-b from-black to-transparent opacity-90 pb-24 h-[calc(100vh-12rem)] md:h-[calc(100vh-8rem)]">
                        <div class="text-center m-auto w-full md:w-3/5">
                            <p class="pt-20 uppercase">2024 Phoebes</p>
                            <h2 class="text-white text-5xl font-bold leading-none tracking-[.2rem] mt-8"> <!-- Adjusted line-height -->
                                <?php the_title(); ?>
                            </h2>
                            <div class="mt-8 px-4 md:px-0">
                                <?php the_content(); ?>
                            </div>
                            <div class="flex flex-col md:flex-row gap-8 md:gap-0 justify-between mt-12">
                                <div class="flex-1 px-12 md:border-r-2 border-[rgb(186,158,94)] md:text-right">
                                    <?php
                                        // Get the director user IDs stored as post meta
                                        $director_user_ids = get_post_meta(get_the_ID(), 'director_user', true);

                                        // Check if there are any director IDs assigned and it's not empty
                                        if (!empty($director_user_ids)) {
                                            // Determine the correct heading based on the number of directors
                                            $director_heading = count($director_user_ids) > 1 ? 'Directors' : 'Director';
                                            echo '<p class="mb-0">' . esc_html($director_heading) . '</p>';
                                            echo '<ul class="list-none mb-0 text-[#BA9E5E]">'; // Use Tailwind classes for styling

                                            // Loop through each director ID and output their display name
                                            foreach ($director_user_ids as $director_user_id) {
                                                $user_info = get_userdata($director_user_id);
                                                if ($user_info) { // Check if user exists
                                                    echo '<li>' . esc_html($user_info->display_name) . '</li>';
                                                }
                                            }

                                            echo '</ul>';
                                        } else {
                                            // Fallback content if no directors are found
                                            echo '<p class="mb-0 text-[#BA9E5E]">No Director Assigned</p>';
                                        }
                                        ?>
                                </div>
                                <?php
                                // Get the actor user IDs stored as post meta
                                $actor_user_ids = get_post_meta(get_the_ID(), 'actor_user', true);

                                // Check if there are any actor IDs assigned and it's not empty
                                if (!empty($actor_user_ids)) :
                                ?>
                                    <div class="block md:border-r-2 px-12 border-[rgb(186,158,94)] text-center">
                                        <?php
                                        // Determine the correct heading based on the number of actors
                                        $actor_heading = count($actor_user_ids) > 1 ? 'Actors' : 'Actor';
                                        echo '<p class="mb-0">' . esc_html($actor_heading) . '</p>';
                                        echo '<ul class="list-none mb-0 text-[#BA9E5E]">'; // Use Tailwind classes for styling

                                        // Loop through each actor ID and output their display name
                                        foreach ($actor_user_ids as $actor_user_id) {
                                            $user_info = get_userdata($actor_user_id);
                                            if ($user_info) { // Check if user exists
                                                echo '<li>' . esc_html($user_info->display_name) . '</li>';
                                            }
                                        }

                                        echo '</ul>';
                                        ?>
                                    </div>
                                <?php
                                endif;
                                ?>
                                <div class="flex-1 px-12 md:text-left category-link">
                                    <p class="mb-0">
                                        Category
                                    </p>
                                    <!-- PHP code for categories -->
                                    <?php $categories = get_the_category(); ?>
                                    <?php if ( ! empty( $categories ) ) : ?>
                                        <?php 
                                            // Get the first category's link
                                            $category_link = get_category_link( $categories[0]->term_id ); 
                                        ?>
                                        <!-- Display the category name wrapped in a link -->
                                        <a href="<?php echo esc_url( $category_link ); ?>" class="mb-0 text-[#BA9E5E]"><?php echo esc_html( $categories[0]->name ); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative w-full h-[calc(100vh-12rem)] md:h-[calc(100vh-8rem)] overflow-hidden bg-no-repeat bg-center bg-cover" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
                        <div class="playbutton play-video-button">
                            <div class="circle pulse"></div>
                            <div class="circle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                                    <polygon points="40,30 65,50 40,70"></polygon>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="played-content" class="content-container mx-auto relative w-4/5 max-w-4/5 hidden">
                    <div id="top-info" class="info-section invisible md:visible w-full pb-8 pt-4 z-10 bg-[#02020257] absolute top-0">
                        <div class="w-full flex justify-between gap-12 px-8">
                            <div class="w-2/5">
                                <h2 class="text-white text-3xl font-bold leading-none m-0 mb-4">
                                    <?php the_title(); ?>
                                </h2>
                                <p class="m-0">Directed By</p>
                                <?php
                                // Get the director user IDs stored as post meta
                                $director_user_ids = get_post_meta(get_the_ID(), 'director_user', true);

                                // Check if there are any director IDs assigned and it's not empty
                                if (!empty($director_user_ids)) {
                                    echo '<ul class="list-none mb-0 text-[rgb(186,158,94)]">'; // Use Tailwind classes for styling

                                    // Ensure $director_user_ids is an array (for single or multiple directors)
                                    if (!is_array($director_user_ids)) {
                                        $director_user_ids = [$director_user_ids];
                                    }

                                    // Loop through each director ID and output their display name
                                    foreach ($director_user_ids as $director_user_id) {
                                        $user_info = get_userdata($director_user_id);
                                        if ($user_info) { // Check if user exists
                                            echo '<li>' . esc_html($user_info->display_name) . '</li>';
                                        }
                                    }

                                    echo '</ul>';
                                } else {
                                    // Fallback content if no directors are found
                                    echo '<p class="mb-0 text-[rgb(186,158,94)]">No Director Assigned</p>';
                                }
                                ?>
                            </div>
                            <div class="text-right w-3/5">
                                <p><?php the_content(); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="video-container relative w-full h-[calc(100vh-8rem)] overflow-hidden bg-no-repeat bg-center bg-cover" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
                        
                    </div>
                </div>
                <div id="played-bottom-content" class="hidden pb-12">
                    <div class="text-center m-auto w-full md:w-3/5">
                        <h2 class="text-white text-4xl font-bold leading-none tracking-[.2rem] mt-8"> <!-- Adjusted line-height -->
                            <?php the_title(); ?>
                        </h2>
                        <div class="mt-8 px-4 md:px-0">
                            <?php the_content(); ?>
                        </div>
                        <div class="flex flex-col md:flex-row gap-8 md:gap-0 justify-between mt-12">
                            <div class="flex-1 px-12 md:border-r-2 border-[rgb(186,158,94)] md:text-right">
                                <?php
                                    // Get the director user IDs stored as post meta
                                    $director_user_ids = get_post_meta(get_the_ID(), 'director_user', true);

                                    // Check if there are any director IDs assigned and it's not empty
                                    if (!empty($director_user_ids)) {
                                        // Determine the correct heading based on the number of directors
                                        $director_heading = count($director_user_ids) > 1 ? 'Directors' : 'Director';
                                        echo '<p class="mb-0">' . esc_html($director_heading) . '</p>';
                                        echo '<ul class="list-none mb-0 text-[#BA9E5E]">'; // Use Tailwind classes for styling

                                        // Loop through each director ID and output their display name
                                        foreach ($director_user_ids as $director_user_id) {
                                            $user_info = get_userdata($director_user_id);
                                            if ($user_info) { // Check if user exists
                                                echo '<li>' . esc_html($user_info->display_name) . '</li>';
                                            }
                                        }

                                        echo '</ul>';
                                    } else {
                                        // Fallback content if no directors are found
                                        echo '<p class="mb-0 text-[#BA9E5E]">No Director Assigned</p>';
                                    }
                                    ?>
                            </div>
                            <?php
                            // Get the actor user IDs stored as post meta
                            $actor_user_ids = get_post_meta(get_the_ID(), 'actor_user', true);

                            // Check if there are any actor IDs assigned and it's not empty
                            if (!empty($actor_user_ids)) :
                            ?>
                                <div class="block md:border-r-2 px-12 border-[rgb(186,158,94)] text-center">
                                    <?php
                                    // Determine the correct heading based on the number of actors
                                    $actor_heading = count($actor_user_ids) > 1 ? 'Actors' : 'Actor';
                                    echo '<p class="mb-0">' . esc_html($actor_heading) . '</p>';
                                    echo '<ul class="list-none mb-0 text-[#BA9E5E]">'; // Use Tailwind classes for styling

                                    // Loop through each actor ID and output their display name
                                    foreach ($actor_user_ids as $actor_user_id) {
                                        $user_info = get_userdata($actor_user_id);
                                        if ($user_info) { // Check if user exists
                                            echo '<li>' . esc_html($user_info->display_name) . '</li>';
                                        }
                                    }

                                    echo '</ul>';
                                    ?>
                                </div>
                            <?php
                            endif;
                            ?>
                            <div class="flex-1 px-12 md:text-left category-link">
                                <p class="mb-0">
                                    Category
                                </p>
                                <!-- PHP code for categories -->
                                <?php $categories = get_the_category(); ?>
                                <?php if ( ! empty( $categories ) ) : ?>
                                    <?php 
                                        // Get the first category's link
                                        $category_link = get_category_link( $categories[0]->term_id ); 
                                    ?>
                                    <!-- Display the category name wrapped in a link -->
                                    <a href="<?php echo esc_url( $category_link ); ?>" class="mb-0 text-[#BA9E5E]"><?php echo esc_html( $categories[0]->name ); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.querySelector('.play-video-button').addEventListener('click', function() {
                        var initialContent = document.getElementById('initial-content');
                        var playedContent = document.getElementById('played-content');
                        var playedBottomContent = document.getElementById('played-bottom-content');
                        var topInfo = document.getElementById('top-info'); // Get the top-info element
                        var videoContainer = playedContent.querySelector('.video-container');

                        // Hide the initial content and show the played content
                        initialContent.style.display = 'none';
                        playedContent.style.display = 'block';
                        if (window.innerWidth <= 768) {
                            playedBottomContent.style.display = 'block';
                        } else {
                            playedBottomContent.style.display = 'hidden'; 
                        }

                        // Assuming $video_url is the direct URL to the video file hosted on your WordPress site
                        var videoUrl = '<?php echo esc_url($video_url); ?>';

                        // Embed the video using a video tag instead of an iframe
                        var videoHtml = '<video style="width: 100%; height: 100%;" controls autoplay>' +
                                        '<source src="' + videoUrl + '" type="video/mp4">' +
                                        'Your browser does not support the video tag.' +
                                        '</video>';
                        videoContainer.innerHTML = videoHtml;

                        var video = videoContainer.querySelector('video');

                        // Listen for play event to hide top-info
                        video.addEventListener('play', function() {
                            topInfo.style.display = 'none';
                        });

                        // Listen for pause event to show top-info
                        video.addEventListener('pause', function() {
                            topInfo.style.display = 'block';
                        });

                        // Optional: Handle video end event to show top-info
                        video.addEventListener('ended', function() {
                            topInfo.style.display = 'block';
                        });

                        // Optional: If you still want to attempt to go fullscreen when playing the video
                        if (video.requestFullscreen) {
                            video.requestFullscreen();
                        } else if (video.mozRequestFullScreen) { // Firefox
                            video.mozRequestFullScreen();
                        } else if (video.webkitRequestFullscreen) { // Chrome, Safari and Opera
                            video.webkitRequestFullscreen();
                        } else if (video.msRequestFullscreen) { // IE/Edge
                            video.msRequestFullscreen();
                        }
                    });

                </script>
                <?php
            }

        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->

<?php