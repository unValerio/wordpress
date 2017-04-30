<?php get_header( miaittalonni_template_base() ); ?>

	<?php do_action( 'miaittalonni_render_widget_area', 'full-width-header-area' ); ?>

	<?php miaittalonni_site_breadcrumbs(); ?>

	<div <?php echo miaittalonni_get_container_classes( array( 'site-content_wrap' ), 'content' ); ?>>

		<div class="row">

			<div id="primary" <?php miaittalonni_primary_content_class(); ?>>

				<main id="main" class="site-main" role="main">

					<?php include miaittalonni_template_path(); ?>

				</main><!-- #main -->

			</div><!-- #primary -->

			<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template.  ?>

		</div><!-- .row -->

	</div><!-- .container -->

	<?php do_action( 'miaittalonni_render_widget_area', 'after-content-full-width-area' ); ?>

<?php get_footer( miaittalonni_template_base() ); ?>
