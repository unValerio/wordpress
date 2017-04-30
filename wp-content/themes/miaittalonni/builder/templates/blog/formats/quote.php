<?php
/**
 * Template for displaying audio post format item content
 */

tm_divi_post_format_content(); ?>
<h2 class="entry-title"><a href="<?php esc_url( the_permalink() ); ?>"><?php the_title(); ?></a></h2>
<?php echo $this->get_template_part( 'blog/meta.php' ); ?>
<?php echo $this->get_post_content(); ?>
<?php echo $this->get_more_button(); ?>
