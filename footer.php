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

	<footer id="colophon" class="site-footer">
		<div class="site-info !m-auto bg-black flex justify-center py-8">
			<div class="w-24 text-center">
                <h5>Contact</h5>
                <a href="<?php echo esc_url(home_url('/2024/phoebe/')); ?>">Phoebe</a>
            </div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
