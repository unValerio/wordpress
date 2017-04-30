<?php
/**
 * Template part to display Custom posts widget.
 *
 * @package Miaittalonni
 * @subpackage widgets
 */
?>
<div class="custom-posts__item post <?php echo $grid_class; ?>">
	<div class="post-inner">

		<?php echo $image; ?>

		<div class="entry-header">
			<?php echo $category; ?>
			<?php echo $title; ?>
		</div>
		<div class="entry-content">
			<?php echo $excerpt; ?>
			<div class="entry-meta">
				<?php echo $author; ?>
				<?php echo $date; ?>
				<?php echo $count; ?>
			</div>
		</div>
		<div class="entry-footer">
			<?php echo $tag; ?>
			<?php echo $button; ?>
		</div>
	</div>
</div>
