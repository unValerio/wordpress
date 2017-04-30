<?php
/**
 * Template part for displaying posts pagination.
 */

the_posts_pagination(
	array(
		'prev_text' => sprintf( '%1$s%2$s', '<i class="fa fa-angle-left"></i>', '<span>' . esc_html__( 'Previous page', 'miaittalonni' ) . '</span>' ),
		'next_text' => sprintf( '%2$s%1$s', '<i class="fa fa-angle-right"></i>', '<span>' . esc_html__( 'Next page', 'miaittalonni' ) . '</span>' ),
	)
);
