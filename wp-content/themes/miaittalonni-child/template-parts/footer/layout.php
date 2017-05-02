<?php
/**
 * The template for displaying the default footer layout.
 *
 * @package Miaittalonni
 */
?>

<div class="footer-area-wrap">
	<div <?php echo miaittalonni_get_container_classes( array( 'footer-area-container', 'invert' ), 'footer' ); ?>>
		<?php do_action( 'miaittalonni_render_widget_area', 'footer-area' ); ?>
	</div>
</div>
<div class="footer-container invert">
	<div <?php echo miaittalonni_get_container_classes( array( 'site-info' ), 'footer' ); ?>>
		<div class="text-center">
			<?php
			miaittalonni_footer_copyright();
			?>
		</div>
	</div><!-- .site-info -->
</div><!-- .container -->
