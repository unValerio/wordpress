<?php
/**
 * Template part to display subscribe form.
 *
 * @package Miaittalonni
 * @subpackage widgets
 */
?>
<div class="subscribe-block">
	<?php if ( in_array( $this->args['id'], array(
		'after-content-full-width-area',
		'full-width-header-area'
	), true ) ) :
		$grid_class_1 = 'col-xs-12 col-lg-6 col-xl-7';
		$grid_class_2 = 'col-xs-12 col-lg-6 col-xl-5';
	else:
		$grid_class_1 = 'col-xs-12';
		$grid_class_2 = 'col-xs-12';
	endif; ?>

	<div class="container subscribe-block__wrap">
		<div class="row">

			<div class="<?php echo $grid_class_1; ?>">
				<?php echo $this->get_block_title( 'subscribe' ); ?>
				<?php echo $this->get_block_message( 'subscribe' ); ?>
			</div>

			<div class="<?php echo $grid_class_2; ?>">
				<form method="POST" action="#" class="subscribe-block__form"><?php
					wp_nonce_field( 'miaittalonni_subscribe', 'miaittalonni_subscribe' );
					?>
					<div class="subscribe-block__input-group"><?php
						echo $this->get_subscribe_input();
						$btn = 'btn btn-primary';
						echo $this->get_subscribe_submit( $btn );
						?></div><?php
					echo $this->get_subscribe_messages();
					?></form>
			</div>

		</div>
	</div>
</div>
