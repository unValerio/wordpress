<?php
/**
 * Menus configuration.
 *
 * @package Miaittalonni
 */

add_action( 'after_setup_theme', 'miaittalonni_register_menus', 5 );
function miaittalonni_register_menus() {

	// This theme uses wp_nav_menu() in four locations.
	register_nav_menus( array(
		'main'   => esc_html__( 'Main', 'miaittalonni' ),
		'footer' => esc_html__( 'Footer', 'miaittalonni' ),
		'social' => esc_html__( 'Social', 'miaittalonni' ),
	) );
}
