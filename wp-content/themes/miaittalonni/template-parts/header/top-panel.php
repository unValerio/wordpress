<?php
/**
 * Template part for top panel in header.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miaittalonni
 */

// Don't show top panel if all elements are disabled.
if ( ! miaittalonni_is_top_panel_visible() ) {
	return;
} ?>

<div class="top-panel invert">
	<div <?php echo miaittalonni_get_container_classes( array( 'top-panel__wrap' ), 'header' ); ?>>
		<div class="row">
			<?php
			miaittalonni_top_message( '<div class="top-panel__message">%s</div>' );
			miaittalonni_top_search( '<div class="top-panel__search">%s</div>' );
			miaittalonni_social_list( 'header' );
			?>
		</div>
	</div>
</div><!-- .top-panel -->
