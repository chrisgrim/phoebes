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
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" as="style">

    <!-- Asynchronously load the font CSS with minimal render blocking -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" media="print" onload="this.media='all'">

    <!-- Fallback for browsers that don't support onload -->
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap">
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
</style>

</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site bg-black">
    <a class="skip-link screen-reader-text sr-only" href="#primary"><?php esc_html_e( 'Skip to content', 'phoebes' ); ?></a>
    <header id="mobile-masthead" class="site-header bg-black border-b border-festival-border md:hidden">
        <div class="flex items-center justify-between h-16 px-5">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo inline-flex flex-col bg-white text-black rounded-lg leading-none" style="padding: .5rem 1rem .85rem .5rem; z-index: 101; position: relative;">
                <span class="text-[0.65rem] font-bold font-heading leading-none">Phoebe's</span>
                <span class="text-[0.65rem] font-bold font-heading leading-none">Film Festival</span>
                <span class="text-[0.65rem] font-bold font-heading leading-none"><?php echo date('Y'); ?></span>
            </a>
            <button class="mobile-menu-toggle bg-transparent border-none p-0" style="z-index: 101; position: relative;" aria-controls="secondary-menu" aria-expanded="false">
                <span class="hamburger-line block w-7 h-0.5 bg-white my-1.5"></span>
                <span class="hamburger-line block w-7 h-0.5 bg-white my-1.5"></span>
            </button>
        </div>
        <nav id="mobile-navigation" class="main-navigation mobile">
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
    <header id="desktop-masthead" class="site-header bg-black border-b border-festival-border hidden md:block">
        <div class="flex items-center h-24 max-w-[1400px] mx-auto px-16">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo inline-flex flex-col bg-white text-black rounded-xl leading-none mr-12 shrink-0" style="padding: .6rem 1.5rem 1rem .6rem;">
                <span class="text-base font-bold font-heading leading-none">Phoebe's</span>
                <span class="text-base font-bold font-heading leading-none">Film Festival</span>
                <span class="text-base font-bold font-heading leading-none"><?php echo date('Y'); ?></span>
            </a>
            <nav id="desktop-navigation" class="main-navigation flex-1 flex items-center justify-end">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'flex space-x-12 items-center',
                    )
                );
                ?>
            </nav>
        </div>
    </header>