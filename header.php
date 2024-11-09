<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package phoebes
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text sr-only" href="#primary"><?php esc_html_e( 'Skip to content', 'phoebes' ); ?></a>

    <header id="masthead" class="site-header bg-black shadow-md md:hidden h-24">
        <div class="site-branding absolute inset-0 h-24 flex justify-center items-center">
            <a href="/" class="z-50"><img class="w-36 h-10" src="https://thephoebes.com/wp-content/uploads/2023/12/the_phoebes_01.png" alt=""></a>
        </div>
        <nav id="site-navigation" class="main-navigation absolute flex items-center mobile">
            <button class="menu-toggle bg-transparent border-none p-0 absolute left-6 top-11" aria-controls="primary-menu" aria-expanded="false">
            <span class="hamburger-line block w-6 h-0.5 bg-white my-1"></span>
            <span class="hamburger-line block w-6 h-0.5 bg-white my-1"></span> <!-- New line added -->
        </button>

            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'menu-2',
                    'menu_id'        => 'secondary-menu',
                    'menu_class'     => 'flex',
                )
            );
            ?>
        </nav>



    </header>

    <header id="masthead" class="site-header bg-black shadow-md hidden md:block">
        <nav id="site-navigation" class="main-navigation h-32 bg-black flex items-center justify-center">
            <button class="menu-toggle text-gray-800 hover:text-gray-600 focus:outline-none focus:shadow-outline px-4 py-2" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'phoebes' ); ?></button>
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'menu-1',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'flex space-x-10 items-center',
                )
            );
            ?>
        </nav><!-- #site-navigation -->
    </header><!-- #masthead -->
</div>
