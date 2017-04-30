<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miaittalonni
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php $utility = miaittalonni_utility()->utility; ?>

	<div class="post-thumbnail">

		<?php do_action( 'cherry_post_format_image', array( 'size' => 'miaittalonni-thumb-1170-781' ) ); ?>

	</div><!-- .post-thumbnail -->

	<?php miaittalonni_ads_post_before_content() ?>

	<header class="entry-header">
		<?php $cats_visible = miaittalonni_is_meta_visible( 'single_post_categories', 'single' ) ? 'true' : 'false';

		$utility->meta_data->get_terms( array(
			'visible' => $cats_visible,
			'type'    => 'category',
			'icon'    => '',
			'before'  => '<div class="post__cats">',
			'after'   => '</div>',
			'echo'    => true,
		) );
		?>

		<?php $utility->attributes->get_title( array(
			'class' => 'entry-title',
			'html'  => '<h2 %1$s>%4$s</h2>',
			'echo'  => true,
		) );
		?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links__title">' . esc_html__( 'Pages:', 'miaittalonni' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span class="page-links__item">',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'miaittalonni' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php if ( 'post' === get_post_type() ) : ?>

			<div class="entry-meta">

				<?php $author_visible = miaittalonni_is_meta_visible( 'single_post_author', 'single' ) ? 'true' : 'false'; ?>

				<?php $utility->meta_data->get_author( array(
					'visible' => $author_visible,
					'class'   => 'posted-by__author',
					'prefix'  => esc_html__( 'By ', 'miaittalonni' ),
					'html'    => '<span class="posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
					'echo'    => true,
				) );
				?>

				<?php $date_visible = miaittalonni_is_meta_visible( 'single_post_publish_date', 'single' ) ? 'true' : 'false';

				$utility->meta_data->get_date( array(
					'visible' => $date_visible,
					'class'   => 'post__date-link',
					'html'    => '<span class="post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s">%6$s%7$s</time></a></span>',
					'echo'    => true,
				) );
				?>

				<?php $comment_visible = miaittalonni_is_meta_visible( 'single_post_comments', 'single' ) ? 'true' : 'false';

				$utility->meta_data->get_comment_count( array(
					'visible' => $comment_visible,
					'class'   => 'post__comments-link',
					'sufix'   => _n_noop( '%s Comment', '%s Comments', 'miaittalonni' ),
					'html'    => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
					'echo'    => true,
				) );
				?>

			</div><!-- .entry-meta -->

		<?php endif; ?>

		<?php $tags_visible = miaittalonni_is_meta_visible( 'single_post_tags', 'single' ) ? 'true' : 'false'; ?>

		<?php $utility->meta_data->get_terms( array(
			'visible'   => $tags_visible,
			'type'      => 'post_tag',
			'delimiter' => ' ',
			'before'    => '<div class="post__tags">',
			'after'     => '</div>',
			'echo'      => true,
		) );
		?>

		<?php miaittalonni_share_buttons( 'single', array(), array(
			'before_text' => '<span class="share-btns__before-text">' . esc_html__( 'Share this post for your friends:', 'miaittalonni' ) . '</span>',
		) );
		?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
