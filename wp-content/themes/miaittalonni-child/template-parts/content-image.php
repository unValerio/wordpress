<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Miaittalonni
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item card' ); ?>>

	<?php
	$utility = miaittalonni_utility()->utility;
	$blog_layout = get_theme_mod( 'blog_layout_type', miaittalonni_theme()->customizer->get_default( 'blog_layout_type' ) );
	?>

	<div class="post-list__item-content">
		<div class="post-thumbnail">
			<?php $size = miaittalonni_post_thumbnail_size(); ?>

			<?php do_action( 'cherry_post_format_image', array( 'size' => $size['size'] ) ); ?>

			<?php miaittalonni_sticky_label(); ?>
		</div><!-- .post-thumbnail -->

		<header class="entry-header">
			<?php
			$cats_visible = miaittalonni_is_meta_visible( 'blog_post_categories', 'loop' ) ? 'true' : 'false';

			$utility->meta_data->get_terms( array(
				'visible'   => $cats_visible,
				'type'      => 'category',
				'icon'      => '',
				'before'    => '<div class="post__cats">',
				'after'     => '</div>',
				'echo'      => true,
			) );

			$title_html = ( is_single() ) ? '<h2 %1$s>%4$s</h2>' : '<h2 %1$s><a href="%2$s" rel="bookmark">%4$s</a></h2>';

			$utility->attributes->get_title( array(
				'class' => 'entry-title',
				'html'  => $title_html,
				'echo'  => true,
			) );
			?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php $blog_content = get_theme_mod( 'blog_posts_content', miaittalonni_theme()->customizer->get_default( 'blog_posts_content' ) );
			$length             = ( 'full' === $blog_content ) ? 0 : 55;

			$utility->attributes->get_content( array(
				'length'       => $length,
				'content_type' => 'post_excerpt',
				'echo'         => true,
			) );
			?>
		</div><!-- .entry-content -->

		<?php if ( 'default' !== $blog_layout ) :
			$utility->attributes->get_button( array(
				'class' => 'btn-link',
				'text'  => get_theme_mod( 'blog_read_more_text', miaittalonni_theme()->customizer->get_default( 'blog_read_more_text' ) ),
				'html'  => '<a href="%1$s" %3$s><span class="btn__text">%4$s</span>%5$s</a>',
				'echo'  => true,
			) );
		endif; ?>

	</div><!-- .post-list__item-content -->

	<footer class="entry-footer">

		<?php if ( 'default' === $blog_layout ) :
			$utility->attributes->get_button( array(
				'class' => 'btn-link',
				'text'  => get_theme_mod( 'blog_read_more_text', miaittalonni_theme()->customizer->get_default( 'blog_read_more_text' ) ),
				'html'  => '<a href="%1$s" %3$s><span class="btn__text">%4$s</span>%5$s</a>',
				'echo'  => true,
			) );
		endif; ?>

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
					'icon'    => ( 'default' !== $blog_layout ) ? '<i class="fa fa-comment-o"></i>' : '',
					'sufix'   => ( 'default' !== $blog_layout ) ? '%s' : _n_noop( '%s Comment', '%s Comments', 'miaittalonni' ),
					'html'    => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
					'echo'    => true,
				) );
				?>

			</div><!-- .entry-meta -->

		<?php endif; ?>

		<?php miaittalonni_share_buttons( 'loop', array(), array(
			'before_text' => '<span class="share-btns__before-text">' . esc_html__( 'Share this post for your friends:', 'miaittalonni' ) . '</span>',
		) );
		?>

		<?php if ( 'post' === get_post_type() ) : ?>

			<?php $tags_visible = miaittalonni_is_meta_visible( 'blog_post_tags', 'loop' ) ? 'true' : 'false';

			$utility->meta_data->get_terms( array(
				'visible'   => $tags_visible,
				'type'      => 'post_tag',
				'delimiter' => ' ',
				'before'    => '<div class="post__tags">',
				'after'     => '</div>',
				'echo'      => true,
			) );
			?>

		<?php endif; ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
