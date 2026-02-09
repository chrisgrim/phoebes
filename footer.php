<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package phoebes
 */

?>

	<footer id="colophon" class="site-footer border-t border-festival-border">
		<div class="site-info max-w-[1400px] mx-auto py-12 px-8 md:px-16 flex flex-col md:flex-row items-center justify-between gap-4">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo inline-flex flex-col items-start bg-white text-black rounded-xl leading-none" style="padding: .5rem 1.5rem .8rem .5rem;">
				<span class="text-base font-bold font-heading leading-none">Phoebe's</span>
				<span class="text-base font-bold font-heading leading-none">Film Festival</span>
				<span class="text-base font-bold font-heading leading-none"><?php echo date('Y'); ?></span>
			</a>
			<a href="<?php echo esc_url(home_url('/phoebe-for-you/')); ?>" class="text-festival-gray text-sm mb-0 hover:text-festival-gold transition-colors">&copy; <?php echo date('Y'); ?> The Phoebe's Film Festival. Contact Us.</a>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
