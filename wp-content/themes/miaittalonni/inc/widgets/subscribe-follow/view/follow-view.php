<?php
/**
 * Template part to display follow list in Subscribe and Follow widget.
 *
 * @package Miaittalonni
 * @subpackage widgets
 */
?>
<div class="follow-block">
	<div class="container follow-block__wrap">
		<div class="follow-block__description">

		<?php
		echo $this->get_block_title( 'follow' );
		echo $this->get_block_message( 'follow' );
		?>

		</div>

	<?php echo $this->get_social_nav();?>

	</div>
</div>
