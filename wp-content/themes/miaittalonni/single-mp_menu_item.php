<?php

do_action( 'mprm_single_before_wrapper' );

while ( have_posts() ) : the_post(); ?>

	<div <?php post_class( apply_filters( 'mprm-main-wrapper-class', 'mprm-main-wrapper' ) ) ?>>

		<?php $utility = miaittalonni_utility()->utility; ?>

		<?php $utility->media->get_image( array(
			'size'        => 'miaittalonni-thumb-1170-679',
			'mobile_size' => 'miaittalonni-thumb-1170-679',
			'html'        => '<figure class="mprm-thumbnail"><img class="mprm-thumbnail__img" src="%3$s" alt="%4$s"></figure>',
			'placeholder' => false,
			'echo'        => true,
		) );
		?>

		<div class="<?php echo apply_filters( 'mprm-content-wrapper-class', 'mprm-container content-wrapper' ) ?>">

			<div class="mprm-row">

				<div class="<?php echo apply_filters( 'mprm-menu-content-class', 'mprm-content mprm-columns' ) ?>">
					<?php do_action( 'mprm_menu_item_header' ); ?>
					<?php do_action( 'mprm_menu_item_content' ); ?>
					<?php do_action('mprm_menu_item_gallery'); ?>
					<?php mprm_menu_item_content_comments(); ?>
				</div>

				<div class="<?php echo apply_filters( 'mprm-menu-sidebar-class', 'mprm-sidebar mprm-columns' ) ?>">
					<?php do_action( 'mprm_menu_item_slidebar' ); ?>
				</div>
				<div class="mprm-clear"></div>
			</div>
		</div>

	</div>

	<?php
endwhile;

do_action( 'mprm_single_after_wrapper' );

?>
