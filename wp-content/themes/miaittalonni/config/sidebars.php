<?php
/**
 * Sidebars configuration.
 *
 * @package Miaittalonni
 */

add_action( 'after_setup_theme', 'miaittalonni_register_sidebars', 5 );
function miaittalonni_register_sidebars() {

	miaittalonni_widget_area()->widgets_settings = apply_filters( 'tm_widget_area_default_settings', array(
		'sidebar-primary' => array(
			'name'           => esc_html__( 'Sidebar Primary', 'miaittalonni' ),
			'description'    => '',
			'before_widget'  => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'   => '</aside>',
			'before_title'   => '<h6 class="widget-title">',
			'after_title'    => '</h6>',
			'before_wrapper' => '<div id="%1$s" %2$s role="complementary">',
			'after_wrapper'  => '</div>',
			'is_global'      => true,
		),
		'full-width-header-area' => array(
			'name'           => esc_html__( 'Header Fullwidth Area', 'miaittalonni' ),
			'description'    => '',
			'before_widget'  => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'   => '</aside>',
			'before_title'   => '<h2 class="widget-title">',
			'after_title'    => '</h2>',
			'before_wrapper' => '<section id="%1$s" %2$s>',
			'after_wrapper'  => '</section>',
			'is_global'      => false,
			'conditional'    => array( 'is_front_page' ),
		),
		'after-content-full-width-area' => array(
			'name'           => esc_html__( 'After Content Fullwidth Area', 'miaittalonni' ),
			'description'    => '',
			'before_widget'  => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'   => '</aside>',
			'before_title'   => '<h2 class="widget-title">',
			'after_title'    => '</h2>',
			'before_wrapper' => '<section id="%1$s" %2$s>',
			'after_wrapper'  => '</section>',
			'is_global'      => false,
			'conditional'    => array( 'is_front_page' ),
		),
		'footer-area' => array(
			'name'           => esc_html__( 'Footer Area', 'miaittalonni' ),
			'description'    => '',
			'before_widget'  => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'   => '</aside>',
			'before_title'   => '<h2 class="widget-title">',
			'after_title'    => '</h2>',
			'before_wrapper' => '<section id="%1$s" %2$s>',
			'after_wrapper'  => '</section>',
			'is_global'      => true,
		),
	) );
}
