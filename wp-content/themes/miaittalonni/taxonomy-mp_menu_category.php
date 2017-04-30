<?php mprm_get_taxonomy(); ?>

<div <?php post_class( apply_filters( 'mprm-main-wrapper-class', 'mprm-main-wrapper mp_menu_item' ) ) ?>>
	<div
		class="<?php echo apply_filters( 'mprm-wrapper-' . get_mprm_taxonomy_view() . '-category-class', 'mprm-taxonomy-items-' . get_mprm_taxonomy_view() . ' mprm-container mprm-category' ) ?> ">
		<?php
		/**
		 * mprm_before_category_header hook
		 *
		 * @hooked mprm_before_category_header - 10
		 */
		do_action( 'mprm_before_category_header' );
		/**
		 * mprm_category_header hook
		 *
		 * @hooked mprm_category_header - 5
		 */
		do_action( 'mprm_category_header' );
		/**
		 * mprm_after_category_header hook
		 *
		 * @hooked mprm_after_category_header - 10
		 */
		do_action( 'mprm_after_category_header' );

		?>

		<?php if ( is_mprm_taxonomy_grid() ): ?>
			<div class="grid-items-listing">
				<?php foreach ( mprm_get_menu_items_by_term() as $term => $data ) {
					$last_key = array_search( end( $data['posts'] ), $data['posts'] );
					foreach ( $data['posts'] as $key => $post ):

						setup_postdata( $post );

						do_action( 'mprm_before_taxonomy_grid' );
						do_action( 'mprm_taxonomy_grid' );
						do_action( 'mprm_after_taxonomy_grid' );

					endforeach;
				}
				?>
			</div>
		<?php else: ?>
			<div class="item-list">
				<?php foreach ( mprm_get_menu_items_by_term() as $term => $data ) {
					foreach ( $data['posts'] as $key => $post ):?>
						<?php setup_postdata( $post ); ?>

						<div <?php post_class( 'mprm-row' ) ?>>
							<?php

							do_action( 'mprm_before_taxonomy_list' );
							do_action( 'mprm_taxonomy_list' );
							do_action( 'mprm_after_taxonomy_list' );

							?>
							<?php
							/**
							 * mprm_after_category_list hook
							 *
							 * @hooked mprm_after_category_list - 10
							 */
							do_action( 'mprm_after_category_list' ); ?>
						</div>

					<?php endforeach;
				} ?>
			</div>
		<?php endif; ?>
	</div>
</div>
