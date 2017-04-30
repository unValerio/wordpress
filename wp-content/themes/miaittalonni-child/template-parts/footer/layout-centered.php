<?php
/**
 * The template for displaying the default footer layout.
 *
 * @package Miaittalonni
 */

?>

<div class="footer-top invert">
	<div <?php echo miaittalonni_get_container_classes( array('footer-top__wrap'), 'footer' ); ?>>
		<?php
		miaittalonni_footer_logo();
		miaittalonni_footer_menu();
		?>
	</div>
</div>
<div class="footer-container invert">
	<div <?php echo miaittalonni_get_container_classes( array( 'site-info' ), 'footer' ); ?>>
		<div class="site-info__flex">
			<?php
			miaittalonni_footer_copyright();
			miaittalonni_social_list( 'footer' );
			?>
		</div>
	</div><!-- .site-info -->
</div><!-- .container -->
