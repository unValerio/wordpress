<?php
/**
 * Template part for displaying comments.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miaittalonni
 */
?>
<footer class="comment-meta">
	<div class="comment-author vcard">
		<?php echo miaittalonni_comment_author_avatar(); ?>
	</div>
	<div class="comment-metadata">
		<?php printf( 
			'<span class="posted-by">%s</span> %s',
			esc_html__( 'Posted by', 'miaittalonni' ), 
			miaittalonni_get_comment_author_link() ); 
		?>
		<?php echo miaittalonni_get_comment_date( array( 'format' => 'M d, Y' ) ); ?>
	</div>
</footer>
<div class="comment-content">
	<?php echo miaittalonni_get_comment_text(); ?>
</div>
<div class="reply">
	<?php echo miaittalonni_get_comment_reply_link( array( 'reply_text' => esc_html__( 'Reply', 'miaittalonni' ) ) ); ?>
</div>
