<?php
/**
 * Thumbnails configuration.
 *
 * @package Miaittalonni
 */

add_action( 'after_setup_theme', 'miaittalonni_register_image_sizes', 5 );
function miaittalonni_register_image_sizes() {
	set_post_thumbnail_size( 350, 348, true );

	// Registers a new image sizes.
	add_image_size( 'miaittalonni-thumb-s', 150, 150, true );
	add_image_size( 'miaittalonni-thumb-m', 400, 400, true );
	add_image_size( 'miaittalonni-thumb-l', 1170, 520, true );
	add_image_size( 'miaittalonni-thumb-xl', 1920, 1080, true );

	add_image_size( 'miaittalonni-thumb-320-252', 320, 252, true );   // mp-rm related
	add_image_size( 'miaittalonni-thumb-370-385', 370, 385, true );   // banner
	add_image_size( 'miaittalonni-thumb-390-311', 390, 311, true );   // page typography
	add_image_size( 'miaittalonni-thumb-480-380', 480, 380, true );   // mp-rm grid item
	add_image_size( 'miaittalonni-thumb-682-351', 682, 351, true );   // page typography
	add_image_size( 'miaittalonni-thumb-1170-679', 1170, 679, true ); // single menu-item
	add_image_size( 'miaittalonni-thumb-1170-781', 1170, 781, true ); // single post
}
