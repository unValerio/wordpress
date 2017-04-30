<?php mprm_get_taxonomy(); ?>

<div <?php post_class( apply_filters( 'mprm-main-wrapper-class', 'mprm-main-wrapper mp_menu_item' ) ) ?>>
	<div
		class="<?php echo apply_filters( 'mprm-wrapper-' . get_mprm_taxonomy_view() . '-tag-class', 'mprm-taxonomy-items-' . get_mprm_taxonomy_view() . ' mprm-container mprm-tag' ) ?> ">

		<?php
		/**
		 * mprm_before_tag_header hook
		 *
		 * @hooked mprm_before_tag_header - 10
		 */
		do_action( 'mprm_before_tag_header' );
		/**
		 * mprm_tag_header hook
		 *
		 * @hooked mprm_tag_header - 5
		 */
		do_action( 'mprm_tag_header' );
		/**
		 * mprm_after_tag_header hook
		 *
		 * @hooked mprm_after_tag_header - 10
		 */
		do_action( 'mprm_after_tag_header' );

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
							/**
							 * mprm_after_tag_list hook
							 *
							 * @hooked mprm_after_tag_list - 10
							 */
							do_action( 'mprm_after_tag_list' ); ?>

						</div>
						<?php
					endforeach;
				} ?>
			</div>
		<?php endif; ?>
	</div>
</div>
