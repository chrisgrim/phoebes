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
    
    <!-- Optimized Google Fonts loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Preload Google Fonts CSS -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" as="style">
    
    <!-- Asynchronously load the font CSS with minimal render blocking -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" media="print" onload="this.media='all'">
    
    <!-- Fallback for browsers that don't support onload -->
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap">
    </noscript>
    
    <!-- Optional: Preload most critical font subset -->
    <link rel="preload" href="https://fonts.gstatic.com/s/lato/v24/S6u9w4BMUTPHh7USSwiPGQ.woff2" as="font" type="font/woff2" crossorigin>
    
	<?php wp_head(); ?>

    <!-- Optimize rendering -->
<script>
  // Force browser to start display immediately
  document.documentElement.style.visibility = 'visible';
</script>
<style>
  /* Ensure content appears immediately */
  body { visibility: visible !important; }
  
  /* Pre-style key elements to prevent layout shifts */
  .site-header { height: auto; min-height: 24px; }
  .site-branding img { width: 140px; height: 40px; }
</style>

</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text sr-only" href="#primary"><?php esc_html_e( 'Skip to content', 'phoebes' ); ?></a>
    <header id="mobile-masthead" class="site-header bg-black shadow-md md:hidden h-24">
        <div class="site-branding absolute inset-0 z-50 h-24 flex justify-center items-center bg-black">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="z-50"><img class="w-36 h-10" src="<?php echo esc_url(site_url('/wp-content/uploads/2023/12/the_phoebes_01.png')); ?>" alt="<?php bloginfo('name'); ?> - The Phoebe's Film Festival"></a>
        </div>
        <nav id="mobile-navigation" class="main-navigation absolute flex items-center mobile">
            <button class="menu-toggle bg-transparent border-none p-0 absolute left-6 top-11 z-50" aria-controls="secondary-menu" aria-expanded="false">
            <span class="hamburger-line block w-6 h-0.5 bg-white my-1"></span>
            <span class="hamburger-line block w-6 h-0.5 bg-white my-1"></span>
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
    <header id="desktop-masthead" class="site-header bg-black shadow-md hidden md:block">
        <nav id="desktop-navigation" class="main-navigation h-32 bg-black flex items-center justify-center">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'menu-1',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'flex space-x-10 items-center',
                )
            );
            ?>
        </nav>
    </header>