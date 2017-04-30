<?php
/**
 * The template part for displaying results in search pages.
 *
 * @package Miaittalonni
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item card hentry' ); ?>>

	<?php $utility = miaittalonni_utility()->utility; ?>

	<div class="post-list__item-content">

		<header class="entry-header">
			<?php

			$title_html = ( is_single() ) ? '<h2 %1$s>%4$s</h2>' : '<h2 %1$s><a href="%2$s" rel="bookmark">%4$s</a></h2>';

			$utility->attributes->get_title( array(
				'class' => 'entry-title',
				'html'  => $title_html,
				'echo'  => true,
			) );
			?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->

	</div><!-- .post-list__item-content -->

	<footer class="entry-footer">

		<?php $utility->attributes->get_button( array(
				'class' => 'btn-link',
				'text'  => get_theme_mod( 'blog_read_more_text', miaittalonni_theme()->customizer->get_default( 'blog_read_more_text' ) ),
				'html'  => '<a href="%1$s" %3$s><span class="btn__text">%4$s</span>%5$s</a>',
				'echo'  => true,
			) );
		?>

		<?php if ( 'post' === get_post_type() ) : ?>

			<div class="entry-meta">

				<?php $author_visible = miaittalonni_is_meta_visible( 'blog_post_author', 'loop' ) ? 'true' : 'false'; ?>

				<?php $utility->meta_data->get_author( array(
					'visible' => $author_visible,
					'class'   => 'posted-by__author',
					'prefix'  => esc_html__( 'By ', 'miaittalonni' ),
					'html'    => '<span class="posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
					'echo'    => true,
				) );
				?>

				<?php $date_visible = miaittalonni_is_meta_visible( 'blog_post_publish_date', 'loop' ) ? 'true' : 'false';

				$utility->meta_data->get_date( array(
					'visible' => $date_visible,
					'class'   => 'post__date-link',
					'html'    => '<span class="post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s">%6$s%7$s</time></a></span>',
					'echo'    => true,
				) );
				?>

				<?php $comment_visible = miaittalonni_is_meta_visible( 'blog_post_comments', 'loop' ) ? 'true' : 'false';

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

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
