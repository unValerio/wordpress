<?php
/**
 * Theme Customizer.
 *
 * @package Miaittalonni
 */

/**
 * Retrieve a holder for Customizer options.
 *
 * @since  1.0.0
 * @return array
 */
function miaittalonni_get_customizer_options() {
	/**
	 * Filter a holder for Customizer options (for theme/plugin developer customization).
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'miaittalonni_get_customizer_options' , array(
		'prefix'     => 'miaittalonni',
		'capability' => 'edit_theme_options',
		'type'       => 'theme_mod',
		'options'    => array(

			/** `Site Indentity` section */
			'show_tagline' => array(
				'title'    => esc_html__( 'Show tagline after logo', 'miaittalonni' ),
				'section'  => 'title_tagline',
				'priority' => 60,
				'default'  => false,
				'field'    => 'checkbox',
				'type'     => 'control',
			),
			'totop_visibility' => array(
				'title'   => esc_html__( 'Show ToTop button', 'miaittalonni' ),
				'section' => 'title_tagline',
				'priority' => 61,
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'page_preloader' => array(
				'title'    => esc_html__( 'Show page preloader', 'miaittalonni' ),
				'section'  => 'title_tagline',
				'priority' => 62,
				'default'  => true,
				'field'    => 'checkbox',
				'type'     => 'control',
			),
			'general_settings' => array(
				'title'       => esc_html__( 'General Site settings', 'miaittalonni' ),
				'priority'    => 40,
				'type'        => 'panel',
			),

			/** `Logo & Favicon` section */
			'logo_favicon' => array(
				'title'       => esc_html__( 'Logo &amp; Favicon', 'miaittalonni' ),
				'priority'    => 25,
				'panel'       => 'general_settings',
				'type'        => 'section',
			),
			'header_logo_type' => array(
				'title'   => esc_html__( 'Logo Type', 'miaittalonni' ),
				'section' => 'logo_favicon',
				'default' => 'image',
				'field'   => 'radio',
				'choices' => array(
					'image' => esc_html__( 'Image', 'miaittalonni' ),
					'text'  => esc_html__( 'Text', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'header_logo_url' => array(
				'title'           => esc_html__( 'Logo Upload', 'miaittalonni' ),
				'description'     => esc_html__( 'Upload logo image', 'miaittalonni' ),
				'section'         => 'logo_favicon',
				'default'         => '%s/assets/images/logo.png',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'miaittalonni_is_header_logo_image',
			),
			'retina_header_logo_url' => array(
				'title'           => esc_html__( 'Retina Logo Upload', 'miaittalonni' ),
				'description'     => esc_html__( 'Upload logo for retina-ready devices', 'miaittalonni' ),
				'section'         => 'logo_favicon',
				'field'           => 'image',
				'type'            => 'control',
				'active_callback' => 'miaittalonni_is_header_logo_image',
			),
			'header_logo_font_family' => array(
				'title'           => esc_html__( 'Font Family', 'miaittalonni' ),
				'section'         => 'logo_favicon',
				'default'         => 'Montserrat, sans-serif',
				'field'           => 'fonts',
				'type'            => 'control',
				'active_callback' => 'miaittalonni_is_header_logo_text',
			),
			'header_logo_font_style' => array(
				'title'           => esc_html__( 'Font Style', 'miaittalonni' ),
				'section'         => 'logo_favicon',
				'default'         => 'normal',
				'field'           => 'select',
				'choices'         => miaittalonni_get_font_styles(),
				'type'            => 'control',
				'active_callback' => 'miaittalonni_is_header_logo_text',
			),
			'header_logo_font_weight' => array(
				'title'           => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section'         => 'logo_favicon',
				'default'         => '400',
				'field'           => 'select',
				'choices'         => miaittalonni_get_font_weight(),
				'type'            => 'control',
				'active_callback' => 'miaittalonni_is_header_logo_text',
			),
			'header_logo_font_size' => array(
				'title'           => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'         => 'logo_favicon',
				'default'         => '22',
				'field'           => 'number',
				'input_attrs'     => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type'            => 'control',
				'active_callback' => 'miaittalonni_is_header_logo_text',
			),
			'header_logo_character_set' => array(
				'title'           => esc_html__( 'Character Set', 'miaittalonni' ),
				'section'         => 'logo_favicon',
				'default'         => 'latin',
				'field'           => 'select',
				'choices'         => miaittalonni_get_character_sets(),
				'type'            => 'control',
				'active_callback' => 'miaittalonni_is_header_logo_text',
			),

			/** `Breadcrumbs` section */
			'breadcrumbs' => array(
				'title'    => esc_html__( 'Breadcrumbs', 'miaittalonni' ),
				'priority' => 30,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'breadcrumbs_visibillity' => array(
				'title'   => esc_html__( 'Enable Breadcrumbs', 'miaittalonni' ),
				'section' => 'breadcrumbs',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_front_visibillity' => array(
				'title'   => esc_html__( 'Enable Breadcrumbs on front page', 'miaittalonni' ),
				'section' => 'breadcrumbs',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_page_title' => array(
				'title'   => esc_html__( 'Enable page title in breadcrumbs area', 'miaittalonni' ),
				'section' => 'breadcrumbs',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'breadcrumbs_path_type' => array(
				'title'   => esc_html__( 'Show full/minified path', 'miaittalonni' ),
				'section' => 'breadcrumbs',
				'default' => 'minified',
				'field'   => 'select',
				'choices' => array(
					'full'     => esc_html__( 'Full', 'miaittalonni' ),
					'minified' => esc_html__( 'Minified', 'miaittalonni' ),
				),
				'type'    => 'control',
			),

			/** `Social links` section */
			'social_links' => array(
				'title'    => esc_html__( 'Social links', 'miaittalonni' ),
				'priority' => 50,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'header_social_links' => array(
				'title'   => esc_html__( 'Show social links in header', 'miaittalonni' ),
				'section' => 'social_links',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'footer_social_links' => array(
				'title'   => esc_html__( 'Show social links in footer', 'miaittalonni' ),
				'section' => 'social_links',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_share_buttons' => array(
				'title'   => esc_html__( 'Show social sharing to blog posts', 'miaittalonni' ),
				'section' => 'social_links',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_share_buttons' => array(
				'title'   => esc_html__( 'Show social sharing to single blog post', 'miaittalonni' ),
				'section' => 'social_links',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Page Layout` section */
			'page_layout' => array(
				'title'    => esc_html__( 'Page Layout', 'miaittalonni' ),
				'priority' => 55,
				'type'     => 'section',
				'panel'    => 'general_settings',
			),
			'header_container_type' => array(
				'title'   => esc_html__( 'Header type', 'miaittalonni' ),
				'section' => 'page_layout',
				'default' => 'boxed',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'miaittalonni' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'content_container_type' => array(
				'title'   => esc_html__( 'Content type', 'miaittalonni' ),
				'section' => 'page_layout',
				'default' => 'boxed',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'miaittalonni' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'footer_container_type' => array(
				'title'   => esc_html__( 'Footer type', 'miaittalonni' ),
				'section' => 'page_layout',
				'default' => 'boxed',
				'field'   => 'select',
				'choices' => array(
					'boxed'     => esc_html__( 'Boxed', 'miaittalonni' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'container_width' => array(
				'title'       => esc_html__( 'Container width (px)', 'miaittalonni' ),
				'section'     => 'page_layout',
				'default'     => 1200,
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 960,
					'max'  => 1920,
					'step' => 1,
				),
				'type' => 'control',
			),
			'sidebar_width' => array(
				'title'   => esc_html__( 'Sidebar width', 'miaittalonni' ),
				'section' => 'page_layout',
				'default' => '1/3',
				'field'   => 'select',
				'choices' => array(
					'1/3' => '1/3',
					'1/4' => '1/4',
				),
				'sanitize_callback' => 'sanitize_text_field',
				'type'              => 'control',
			),

			/** `Color Scheme` panel */
			'color_scheme' => array(
				'title'       => esc_html__( 'Color Scheme', 'miaittalonni' ),
				'description' => esc_html__( 'Configure Color Scheme', 'miaittalonni' ),
				'priority'    => 40,
				'type'        => 'panel',
			),

			/** `Regular scheme` section */
			'regular_scheme' => array(
				'title'       => esc_html__( 'Regular scheme', 'miaittalonni' ),
				'priority'    => 1,
				'panel'       => 'color_scheme',
				'type'        => 'section',
			),
			'regular_accent_color_1' => array(
				'title'   => esc_html__( 'Accent color (1)', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#d80000',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_accent_color_2' => array(
				'title'   => esc_html__( 'Accent color (2)', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_accent_color_3' => array(
				'title'   => esc_html__( 'Accent color (3)', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#f7f7f7',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_text_color' => array(
				'title'   => esc_html__( 'Text color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_link_color' => array(
				'title'   => esc_html__( 'Link color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#d80000',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_link_hover_color' => array(
				'title'   => esc_html__( 'Link hover color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h1_color' => array(
				'title'   => esc_html__( 'H1 color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h2_color' => array(
				'title'   => esc_html__( 'H2 color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h3_color' => array(
				'title'   => esc_html__( 'H3 color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h4_color' => array(
				'title'   => esc_html__( 'H4 color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h5_color' => array(
				'title'   => esc_html__( 'H5 color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'regular_h6_color' => array(
				'title'   => esc_html__( 'H6 color', 'miaittalonni' ),
				'section' => 'regular_scheme',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Invert scheme` section */
			'invert_scheme' => array(
				'title'       => esc_html__( 'Invert scheme', 'miaittalonni' ),
				'priority'    => 1,
				'panel'       => 'color_scheme',
				'type'        => 'section',
			),
			'invert_accent_color_1' => array(
				'title'   => esc_html__( 'Accent color (1)', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_accent_color_2' => array(
				'title'   => esc_html__( 'Accent color (2)', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_accent_color_3' => array(
				'title'   => esc_html__( 'Accent color (3)', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#bdbdbd',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_text_color' => array(
				'title'   => esc_html__( 'Text color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_link_color' => array(
				'title'   => esc_html__( 'Link color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_link_hover_color' => array(
				'title'   => esc_html__( 'Link hover color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#d80000',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h1_color' => array(
				'title'   => esc_html__( 'H1 color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h2_color' => array(
				'title'   => esc_html__( 'H2 color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h3_color' => array(
				'title'   => esc_html__( 'H3 color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h4_color' => array(
				'title'   => esc_html__( 'H4 color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h5_color' => array(
				'title'   => esc_html__( 'H5 color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'invert_h6_color' => array(
				'title'   => esc_html__( 'H6 color', 'miaittalonni' ),
				'section' => 'invert_scheme',
				'default' => '#fff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Typography Settings` panel */
			'typography' => array(
				'title'       => esc_html__( 'Typography', 'miaittalonni' ),
				'description' => esc_html__( 'Configure typography settings', 'miaittalonni' ),
				'priority'    => 45,
				'type'        => 'panel',
			),

			/** `Body text` section */
			'body_typography' => array(
				'title'       => esc_html__( 'Body text', 'miaittalonni' ),
				'priority'    => 5,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'body_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'body_typography',
				'default' => 'Roboto, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'body_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'body_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'body_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'body_typography',
				'default' => '300',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'body_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'body_typography',
				'default'     => '18',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type' => 'control',
			),
			'body_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'body_typography',
				'default'     => '1.75',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'body_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'body_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'body_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'body_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),
			'body_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'miaittalonni' ),
				'section' => 'body_typography',
				'default' => 'left',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H1 Heading` section */
			'h1_typography' => array(
				'title'       => esc_html__( 'H1 Heading', 'miaittalonni' ),
				'priority'    => 10,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'h1_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'h1_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h1_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'h1_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'h1_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'h1_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'h1_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'h1_typography',
				'default'     => '30',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h1_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'h1_typography',
				'default'     => '1.4',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h1_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'h1_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h1_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'h1_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),
			'h1_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'miaittalonni' ),
				'section' => 'h1_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H2 Heading` section */
			'h2_typography' => array(
				'title'       => esc_html__( 'H2 Heading', 'miaittalonni' ),
				'priority'    => 15,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'h2_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'h2_typography',
				'default' => 'Roboto, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h2_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'h2_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'h2_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'h2_typography',
				'default' => '300',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'h2_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'h2_typography',
				'default'     => '29',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h2_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'h2_typography',
				'default'     => '1.4137931',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h2_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'h2_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h2_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'h2_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),
			'h2_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'miaittalonni' ),
				'section' => 'h2_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H3 Heading` section */
			'h3_typography' => array(
				'title'       => esc_html__( 'H3 Heading', 'miaittalonni' ),
				'priority'    => 20,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'h3_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'h3_typography',
				'default' => 'Roboto, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h3_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'h3_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'h3_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'h3_typography',
				'default' => '300',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'h3_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'h3_typography',
				'default'     => '24',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h3_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'h3_typography',
				'default'     => '1.458333',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h3_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'h3_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h3_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'h3_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),
			'h3_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'miaittalonni' ),
				'section' => 'h3_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H4 Heading` section */
			'h4_typography' => array(
				'title'       => esc_html__( 'H4 Heading', 'miaittalonni' ),
				'priority'    => 25,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'h4_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'h4_typography',
				'default' => 'Roboto, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h4_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'h4_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'h4_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'h4_typography',
				'default' => '300',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'h4_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'h4_typography',
				'default'     => '20',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h4_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'h4_typography',
				'default'     => '1.5',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h4_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'h4_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h4_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'h4_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),
			'h4_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'miaittalonni' ),
				'section' => 'h4_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H5 Heading` section */
			'h5_typography' => array(
				'title'       => esc_html__( 'H5 Heading', 'miaittalonni' ),
				'priority'    => 30,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'h5_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'h5_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h5_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'h5_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'h5_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'h5_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'h5_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'h5_typography',
				'default'     => '18',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h5_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'h5_typography',
				'default'     => '1.55555555',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h5_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'h5_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h5_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'h5_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),
			'h5_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'miaittalonni' ),
				'section' => 'h5_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_aligns(),
				'type'    => 'control',
			),

			/** `H6 Heading` section */
			'h6_typography' => array(
				'title'       => esc_html__( 'H6 Heading', 'miaittalonni' ),
				'priority'    => 35,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'h6_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'h6_typography',
				'default' => 'Roboto, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'h6_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'h6_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'h6_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'h6_typography',
				'default' => '300',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'h6_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'h6_typography',
				'default'     => '18',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h6_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'h6_typography',
				'default'     => '1.55555555',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'h6_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'h6_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'h6_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'h6_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),
			'h6_text_align' => array(
				'title'   => esc_html__( 'Text Align', 'miaittalonni' ),
				'section' => 'h6_typography',
				'default' => 'inherit',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_aligns(),
				'type'    => 'control',
			),

			/** `Header showcase title` section */
			'showcase_title_typography' => array(
				'title'       => esc_html__( 'Header showcase title', 'miaittalonni' ),
				'priority'    => 40,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'showcase_title_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'showcase_title_typography',
				'default' => 'Satisfy, handwriting',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'showcase_title_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'showcase_title_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'showcase_title_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'showcase_title_typography',
				'default' => '400',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'showcase_title_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'showcase_title_typography',
				'default'     => '80',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'showcase_title_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'showcase_title_typography',
				'default'     => '1.2',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'showcase_title_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'showcase_title_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'showcase_title_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'showcase_title_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),

			'showcase_title_text_transform' => array(
				'title'   => esc_html__( 'Text transform', 'miaittalonni' ),
				'section' => 'showcase_title_typography',
				'default' => 'none',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_transform(),
				'type'    => 'control',
			),

			/** `Header showcase subtitle` section */
			'showcase_subtitle_typography' => array(
				'title'       => esc_html__( 'Header showcase subtitle', 'miaittalonni' ),
				'priority'    => 45,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'showcase_subtitle_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'showcase_subtitle_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'showcase_subtitle_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'showcase_subtitle_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'showcase_subtitle_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'showcase_subtitle_typography',
				'default' => '500',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'showcase_subtitle_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'showcase_subtitle_typography',
				'default'     => '36',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 10,
					'max'  => 200,
					'step' => 1,
				),
				'type' => 'control',
			),
			'showcase_subtitle_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'showcase_subtitle_typography',
				'default'     => '1.3',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'showcase_subtitle_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'showcase_subtitle_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'showcase_subtitle_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'showcase_subtitle_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),

			'showcase_subtitle_text_transform' => array(
				'title'   => esc_html__( 'Text transform', 'miaittalonni' ),
				'section' => 'showcase_subtitle_typography',
				'default' => 'none',
				'field'   => 'select',
				'choices' => miaittalonni_get_text_transform(),
				'type'    => 'control',
			),

			/** `Breadcrumbs` section */
			'breadcrumbs_typography' => array(
				'title'       => esc_html__( 'Breadcrumbs', 'miaittalonni' ),
				'priority'    => 50,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'breadcrumbs_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'Roboto, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'breadcrumbs_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'breadcrumbs_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'breadcrumbs_typography',
				'default' => '300',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'breadcrumbs_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '13',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type' => 'control',
			),
			'breadcrumbs_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '1.5',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'breadcrumbs_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'breadcrumbs_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'breadcrumbs_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'breadcrumbs_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),

			/** `Pagination` section */
			'pagination_typography' => array(
				'title'       => esc_html__( 'Pagination', 'miaittalonni' ),
				'priority'    => 55,
				'panel'       => 'typography',
				'type'        => 'section',
			),
			'pagination_font_family' => array(
				'title'   => esc_html__( 'Font Family', 'miaittalonni' ),
				'section' => 'pagination_typography',
				'default' => 'Montserrat, sans-serif',
				'field'   => 'fonts',
				'type'    => 'control',
			),
			'pagination_font_style' => array(
				'title'   => esc_html__( 'Font Style', 'miaittalonni' ),
				'section' => 'pagination_typography',
				'default' => 'normal',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_styles(),
				'type'    => 'control',
			),
			'pagination_font_weight' => array(
				'title'   => esc_html__( 'Font Weight', 'miaittalonni' ),
				'section' => 'pagination_typography',
				'default' => '700',
				'field'   => 'select',
				'choices' => miaittalonni_get_font_weight(),
				'type'    => 'control',
			),
			'pagination_font_size' => array(
				'title'       => esc_html__( 'Font Size, px', 'miaittalonni' ),
				'section'     => 'pagination_typography',
				'default'     => '16',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 6,
					'max'  => 50,
					'step' => 1,
				),
				'type' => 'control',
			),
			'pagination_line_height' => array(
				'title'       => esc_html__( 'Line Height', 'miaittalonni' ),
				'description' => esc_html__( 'Relative to the font-size of the element', 'miaittalonni' ),
				'section'     => 'pagination_typography',
				'default'     => '1.5',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => 1.0,
					'max'  => 3.0,
					'step' => 0.1,
				),
				'type' => 'control',
			),
			'pagination_letter_spacing' => array(
				'title'       => esc_html__( 'Letter Spacing, px', 'miaittalonni' ),
				'section'     => 'pagination_typography',
				'default'     => '0',
				'field'       => 'number',
				'input_attrs' => array(
					'min'  => -10,
					'max'  => 10,
					'step' => 1,
				),
				'type' => 'control',
			),
			'pagination_character_set' => array(
				'title'   => esc_html__( 'Character Set', 'miaittalonni' ),
				'section' => 'pagination_typography',
				'default' => 'latin',
				'field'   => 'select',
				'choices' => miaittalonni_get_character_sets(),
				'type'    => 'control',
			),

			/** `Header` panel */
			'header_options' => array(
				'title'       => esc_html__( 'Header', 'miaittalonni' ),
				'priority'    => 60,
				'type'        => 'panel',
			),

			/** `Header styles` section */
			'header_styles' => array(
				'title'       => esc_html__( 'Styles', 'miaittalonni' ),
				'priority'    => 5,
				'panel'       => 'header_options',
				'type'        => 'section',
			),
			'header_bg_color' => array(
				'title'   => esc_html__( 'Background Color', 'miaittalonni' ),
				'section' => 'header_styles',
				'field'   => 'hex_color',
				'default' => '#343434',
				'type'    => 'control',
			),
			'header_bg_image' => array(
				'title'   => esc_html__( 'Background Image', 'miaittalonni' ),
				'section' => 'header_styles',
				'field'   => 'image',
				'type'    => 'control',
			),
			'header_bg_repeat' => array(
				'title'   => esc_html__( 'Background Repeat', 'miaittalonni' ),
				'section' => 'header_styles',
				'default' => 'no-repeat',
				'field'   => 'select',
				'choices' => array(
					'no-repeat'  => esc_html__( 'No Repeat', 'miaittalonni' ),
					'repeat'     => esc_html__( 'Tile', 'miaittalonni' ),
					'repeat-x'   => esc_html__( 'Tile Horizontally', 'miaittalonni' ),
					'repeat-y'   => esc_html__( 'Tile Vertically', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'header_bg_position_x' => array(
				'title'   => esc_html__( 'Background Position', 'miaittalonni' ),
				'section' => 'header_styles',
				'default' => 'center',
				'field'   => 'select',
				'choices' => array(
					'left'   => esc_html__( 'Left', 'miaittalonni' ),
					'center' => esc_html__( 'Center', 'miaittalonni' ),
					'right'  => esc_html__( 'Right', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'header_bg_attachment' => array(
				'title'   => esc_html__( 'Background Attachment', 'miaittalonni' ),
				'section' => 'header_styles',
				'default' => 'scroll',
				'field'   => 'select',
				'choices' => array(
					'scroll' => esc_html__( 'Scroll', 'miaittalonni' ),
					'fixed'  => esc_html__( 'Fixed', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'header_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'miaittalonni' ),
				'section' => 'header_styles',
				'default' => 'minimal',
				'field'   => 'select',
				'choices' => array(
					'minimal'  => esc_html__( 'Style 1', 'miaittalonni' ),
					'centered' => esc_html__( 'Style 2', 'miaittalonni' ),
					'default'  => esc_html__( 'Style 3', 'miaittalonni' ),
				),
				'type' => 'control',
			),

			/** `Top Panel` section */
			'header_top_panel' => array(
				'title'       => esc_html__( 'Top Panel', 'miaittalonni' ),
				'priority'    => 10,
				'panel'       => 'header_options',
				'type'        => 'section',
			),
			'top_panel_text' => array(
				'title'       => esc_html__( 'Disclaimer Text', 'miaittalonni' ),
				'description' => esc_html__( 'HTML formatting support', 'miaittalonni' ),
				'section'     => 'header_top_panel',
				'default'     => miaittalonni_get_default_top_panel_text(),
				'field'       => 'textarea',
				'type'        => 'control',
			),
			'top_panel_search' => array(
				'title'   => esc_html__( 'Enable search', 'miaittalonni' ),
				'section' => 'header_top_panel',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'top_panel_bg' => array(
				'title'   => esc_html__( 'Background color', 'miaittalonni' ),
				'section' => 'header_top_panel',
				'default' => '#f7f7f7',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Header Showcase` section */
			'header_showcase_panel' => array(
				'title'       => esc_html__( 'Showcase Panel', 'miaittalonni' ),
				'priority'    => 15,
				'panel'       => 'header_options',
				'type'        => 'section',
			),

			'header_showcase_title' => array(
				'title'    => esc_html__( 'Header showcase title', 'miaittalonni' ),
				'section'  => 'header_showcase_panel',
				'default'  => esc_html__( 'The most praised gourmet restaurant', 'miaittalonni' ),
				'field'    => 'text',
				'type'     => 'control'
			),

			'header_showcase_subtitle' => array(
				'title'    => esc_html__( 'Header showcase subtitle', 'miaittalonni' ),
				'section'  => 'header_showcase_panel',
				'default'  => esc_html__( 'In the heart of Washington', 'miaittalonni' ),
				'field'    => 'text',
				'type'     => 'control'
			),

			'header_showcase_description' => array(
				'title'    => esc_html__( 'Header showcase description', 'miaittalonni' ),
				'section'  => 'header_showcase_panel',
				'default'  => miaittalonni_get_default_showcase_description(),
				'field'    => 'textarea',
				'type'     => 'control'
			),

			'header_showcase_btn_text' => array(
				'title'    => esc_html__( 'Header showcase button text(leave empty to hide button)', 'miaittalonni' ),
				'section'  => 'header_showcase_panel',
				'default' => esc_html__( 'Book online', 'miaittalonni' ),
				'field'    => 'text',
				'type'     => 'control'
			),

			'header_showcase_btn_url' => array(
				'title'    => esc_html__( 'Header showcase button url', 'miaittalonni' ),
				'section'  => 'header_showcase_panel',
				'default'  => '#',
				'field'    => 'text',
				'type'     => 'control'
			),

			'header_showcase_bg_image' => array(
				'title'   => esc_html__( 'Background Image', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'field'   => 'image',
				'default' => '%s/assets/images/showcase_bg.jpg',
				'type'    => 'control',
			),

			'header_showcase_bg_color' => array(
				'title'   => esc_html__( 'Background Color', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'field'   => 'hex_color',
				'default' => '#000000',
				'type'    => 'control',
			),

			'header_showcase_bg_repeat' => array(
				'title'   => esc_html__( 'Background Repeat', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'default' => 'no-repeat',
				'field'   => 'select',
				'choices' => array(
					'no-repeat'  => esc_html__( 'No Repeat', 'miaittalonni' ),
					'repeat'     => esc_html__( 'Tile', 'miaittalonni' ),
					'repeat-x'   => esc_html__( 'Tile Horizontally', 'miaittalonni' ),
					'repeat-y'   => esc_html__( 'Tile Vertically', 'miaittalonni' ),
				),
				'type' => 'control',
			),

			'header_showcase_bg_position_x' => array(
				'title'   => esc_html__( 'Background Position', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'default' => 'center',
				'field'   => 'select',
				'choices' => array(
					'left'   => esc_html__( 'Left', 'miaittalonni' ),
					'center' => esc_html__( 'Center', 'miaittalonni' ),
					'right'  => esc_html__( 'Right', 'miaittalonni' ),
				),
				'type' => 'control',
			),

			'header_showcase_bg_attachment' => array(
				'title'   => esc_html__( 'Background Attachment', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'default' => 'scroll',
				'field'   => 'select',
				'choices' => array(
					'scroll' => esc_html__( 'Scroll', 'miaittalonni' ),
					'fixed'  => esc_html__( 'Fixed', 'miaittalonni' ),
				),
				'type' => 'control',
			),

			'header_showcase_color_mask' => array(
				'title'   => esc_html__( 'Color Image Mask', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'field'   => 'hex_color',
				'default' => '#000000',
				'type'    => 'control',
			),

			'header_showcase_opacity_mask' => array(
				'title'           => esc_html__( 'Opacity Image Mask', 'miaittalonni' ),
				'section'         => 'header_showcase_panel',
				'default'         => '30',
				'field'           => 'number',
				'input_attrs'     => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'type'            => 'control',
			),

			'showcase_title_color' => array(
				'title'   => esc_html__( 'Showcase title color', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			'showcase_subtitle_color' => array(
				'title'   => esc_html__( 'Showcase subtitle color', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			'showcase_description_color' => array(
				'title'   => esc_html__( 'Showcase description color', 'miaittalonni' ),
				'section' => 'header_showcase_panel',
				'default' => '#ffffff',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Main Menu` section */
			'header_main_menu' => array(
				'title'       => esc_html__( 'Main Menu', 'miaittalonni' ),
				'priority'    => 20,
				'panel'       => 'header_options',
				'type'        => 'section',
			),
			'header_menu_sticky' => array(
				'title'   => esc_html__( 'Enable sticky menu', 'miaittalonni' ),
				'section' => 'header_main_menu',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'header_menu_attributes' => array(
				'title'   => esc_html__( 'Enable item description', 'miaittalonni' ),
				'section' => 'header_main_menu',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'hidden_menu_items_title' => array(
				'title'    => esc_html__( 'Hidden menu items title', 'miaittalonni' ),
				'section'  => 'header_main_menu',
				'default'  => esc_html__( 'More', 'miaittalonni' ),
				'field'    => 'input',
				'type'     => 'control'
			),

			/** `Sidebar` section */
			'sidebar_settings' => array(
				'title'    => esc_html__( 'Sidebar', 'miaittalonni' ),
				'priority' => 105,
				'type'     => 'section',
			),
			'sidebar_position' => array(
				'title'   => esc_html__( 'Sidebar Position', 'miaittalonni' ),
				'section' => 'sidebar_settings',
				'default' => 'fullwidth',
				'field'   => 'select',
				'choices' => array(
					'one-left-sidebar'  => esc_html__( 'Sidebar on left side', 'miaittalonni' ),
					'one-right-sidebar' => esc_html__( 'Sidebar on right side', 'miaittalonni' ),
					'fullwidth'         => esc_html__( 'No sidebars', 'miaittalonni' ),
				),
				'type' => 'control',
			),

			/** `MailChimp` section */
			'mailchimp' => array(
				'title'       => esc_html__( 'MailChimp', 'miaittalonni' ),
				'description' => esc_html__( 'Setup MailChimp settings for subscribe widget', 'miaittalonni' ),
				'priority'    => 109,
				'type'        => 'section',
			),
			'mailchimp_api_key' => array(
				'title'   => esc_html__( 'MailChimp API key', 'miaittalonni' ),
				'section' => 'mailchimp',
				'field'   => 'text',
				'type'    => 'control',
			),
			'mailchimp_list_id' => array(
				'title'   => esc_html__( 'MailChimp list ID', 'miaittalonni' ),
				'section' => 'mailchimp',
				'field'   => 'text',
				'type'    => 'control',
			),

			/** `Ads Management` panel */
			'ads_management' => array(
				'title'    => esc_html__( 'Ads Management', 'miaittalonni' ),
				'priority' => 110,
				'type'     => 'section',
			),
			'ads_header' => array(
				'title'             => esc_html__( 'Header', 'miaittalonni' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_home_before_loop' => array(
				'title'             => esc_html__( 'Front Page Before Loop', 'miaittalonni' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_post_before_content' => array(
				'title'             => esc_html__( 'Post Before Content', 'miaittalonni' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),
			'ads_post_before_comments' => array(
				'title'             => esc_html__( 'Post Before Comments', 'miaittalonni' ),
				'section'           => 'ads_management',
				'field'             => 'textarea',
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'type'              => 'control',
			),

			/** `Footer` panel */
			'footer_options' => array(
				'title'    => esc_html__( 'Footer', 'miaittalonni' ),
				'priority' => 110,
				'type'     => 'section',
			),
			'footer_logo_url' => array(
				'title'   => esc_html__( 'Logo upload', 'miaittalonni' ),
				'section' => 'footer_options',
				'field'   => 'image',
				'default' => '%s/assets/images/footer-logo.png',
				'type'    => 'control',
			),
			'footer_copyright' => array(
				'title'   => esc_html__( 'Copyright text', 'miaittalonni' ),
				'section' => 'footer_options',
				'default' => miaittalonni_get_default_footer_copyright(),
				'field'   => 'textarea',
				'type'    => 'control',
			),
			'footer_widget_columns' => array(
				'title'   => esc_html__( 'Widget Area Columns', 'miaittalonni' ),
				'section' => 'footer_options',
				'default' => '3',
				'field'   => 'select',
				'choices' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'type' => 'control'
			),
			'footer_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'miaittalonni' ),
				'section' => 'footer_options',
				'default' => 'default',
				'field'   => 'select',
				'choices' => array(
					'default'  => esc_html__( 'Style 1', 'miaittalonni' ),
					'centered' => esc_html__( 'Style 2', 'miaittalonni' ),
					'minimal'  => esc_html__( 'Style 3', 'miaittalonni' ),
				),
				'type' => 'control'
			),
			'footer_widgets_bg' => array(
				'title'   => esc_html__( 'Footer Widgets Area Background color', 'miaittalonni' ),
				'section' => 'footer_options',
				'default' => '#343434',
				'field'   => 'hex_color',
				'type'    => 'control',
			),
			'footer_bg' => array(
				'title'   => esc_html__( 'Footer Background color', 'miaittalonni' ),
				'section' => 'footer_options',
				'default' => '#3e3e3e',
				'field'   => 'hex_color',
				'type'    => 'control',
			),

			/** `Blog Settings` panel */
			'blog_settings' => array(
				'title'       => esc_html__( 'Blog Settings', 'miaittalonni' ),
				'priority'    => 115,
				'type'        => 'panel',
			),

			/** `Blog` section */
			'blog' => array(
				'title'           => esc_html__( 'Blog', 'miaittalonni' ),
				'panel'           => 'blog_settings',
				'priority'        => 10,
				'type'            => 'section',
				'active_callback' => 'is_home',
			),
			'blog_layout_type' => array(
				'title'   => esc_html__( 'Layout', 'miaittalonni' ),
				'section' => 'blog',
				'default' => 'default',
				'field'   => 'select',
				'choices' => array(
					'default'          => esc_html__( 'Listing', 'miaittalonni' ),
					'grid-2-cols'      => esc_html__( 'Grid (2 Columns)', 'miaittalonni' ),
					'grid-3-cols'      => esc_html__( 'Grid (3 Columns)', 'miaittalonni' ),
					'masonry-2-cols'   => esc_html__( 'Masonry (2 Columns)', 'miaittalonni' ),
					'masonry-3-cols'   => esc_html__( 'Masonry (3 Columns)', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'blog_sticky_label' => array(
				'title'       => esc_html__( 'Featured Post Label', 'miaittalonni' ),
				'description' => esc_html__( 'Label for sticky post', 'miaittalonni' ),
				'section'     => 'blog',
				'default'     => 'icon:fa:star',
				'field'       => 'text',
				'type'        => 'control',
			),
			'blog_posts_content' => array(
				'title'   => esc_html__( 'Post content', 'miaittalonni' ),
				'section' => 'blog',
				'default' => 'excerpt',
				'field'   => 'select',
				'choices' => array(
					'excerpt' => esc_html__( 'Only excerpt', 'miaittalonni' ),
					'full'    => esc_html__( 'Full content', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'blog_featured_image' => array(
				'title'   => esc_html__( 'Featured image', 'miaittalonni' ),
				'section' => 'blog',
				'default' => 'fullwidth',
				'field'   => 'select',
				'choices' => array(
					'small'     => esc_html__( 'Small', 'miaittalonni' ),
					'fullwidth' => esc_html__( 'Fullwidth', 'miaittalonni' ),
				),
				'type' => 'control',
				'active_callback' => 'miaittalonni_is_blog_featured_image'
			),
			'blog_read_more_text' => array(
				'title'   => esc_html__( 'Read More button text', 'miaittalonni' ),
				'section' => 'blog',
				'default' => esc_html__( 'Read more', 'miaittalonni' ),
				'field'   => 'text',
				'type'    => 'control',
			),
			'blog_post_author' => array(
				'title'   => esc_html__( 'Show post author', 'miaittalonni' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_publish_date' => array(
				'title'   => esc_html__( 'Show publish date', 'miaittalonni' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_categories' => array(
				'title'   => esc_html__( 'Show categories', 'miaittalonni' ),
				'section' => 'blog',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_tags' => array(
				'title'   => esc_html__( 'Show tags', 'miaittalonni' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'blog_post_comments' => array(
				'title'   => esc_html__( 'Show comments', 'miaittalonni' ),
				'section' => 'blog',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `Post` section */
			'blog_post' => array(
				'title'           => esc_html__( 'Post', 'miaittalonni' ),
				'panel'           => 'blog_settings',
				'priority'        => 20,
				'type'            => 'section',
				'active_callback' => 'callback_single',
			),
			'single_post_author' => array(
				'title'   => esc_html__( 'Show post author', 'miaittalonni' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_publish_date' => array(
				'title'   => esc_html__( 'Show publish date', 'miaittalonni' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_categories' => array(
				'title'   => esc_html__( 'Show categories', 'miaittalonni' ),
				'section' => 'blog_post',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_tags' => array(
				'title'   => esc_html__( 'Show tags', 'miaittalonni' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_comments' => array(
				'title'   => esc_html__( 'Show comments', 'miaittalonni' ),
				'section' => 'blog_post',
				'default' => true,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_author_block' => array(
				'title'   => esc_html__( 'Enable the author block after each post', 'miaittalonni' ),
				'section' => 'blog_post',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),
			'single_post_navigation' => array(
				'title'   => esc_html__( 'Enable post navigation', 'miaittalonni' ),
				'section' => 'blog_post',
				'default' => false,
				'field'   => 'checkbox',
				'type'    => 'control',
			),

			/** `404` panel */
			'page_404_options' => array(
				'title'    => esc_html__( '404', 'miaittalonni' ),
				'priority' => 118,
				'type'     => 'section',
			),
			'page_404_bg_color' => array(
				'title'   => esc_html__( 'Background Color', 'miaittalonni' ),
				'section' => 'page_404_options',
				'field'   => 'hex_color',
				'default' => '#000000',
				'type'    => 'control',
			),
			'page_404_bg_image' => array(
				'title'   => esc_html__( 'Background Image', 'miaittalonni' ),
				'section' => 'page_404_options',
				'field'   => 'image',
				'default' => '%s/assets/images/bg_404.jpg',
				'type'    => 'control',
			),
			'page_404_bg_repeat' => array(
				'title'   => esc_html__( 'Background Repeat', 'miaittalonni' ),
				'section' => 'page_404_options',
				'default' => 'no-repeat',
				'field'   => 'select',
				'choices' => array(
					'no-repeat'  => esc_html__( 'No Repeat', 'miaittalonni' ),
					'repeat'     => esc_html__( 'Tile', 'miaittalonni' ),
					'repeat-x'   => esc_html__( 'Tile Horizontally', 'miaittalonni' ),
					'repeat-y'   => esc_html__( 'Tile Vertically', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'page_404_bg_position_x' => array(
				'title'   => esc_html__( 'Background Position', 'miaittalonni' ),
				'section' => 'page_404_options',
				'default' => 'center',
				'field'   => 'select',
				'choices' => array(
					'left'   => esc_html__( 'Left', 'miaittalonni' ),
					'center' => esc_html__( 'Center', 'miaittalonni' ),
					'right'  => esc_html__( 'Right', 'miaittalonni' ),
				),
				'type' => 'control',
			),
			'page_404_bg_attachment' => array(
				'title'   => esc_html__( 'Background Attachment', 'miaittalonni' ),
				'section' => 'page_404_options',
				'default' => 'scroll',
				'field'   => 'select',
				'choices' => array(
					'scroll' => esc_html__( 'Scroll', 'miaittalonni' ),
					'fixed'  => esc_html__( 'Fixed', 'miaittalonni' ),
				),
				'type' => 'control',
			),
	) ) );
}

/**
 * Return true if logo in header has image type. Otherwise - return false.
 *
 * @param  object $control
 * @return bool
 */
function miaittalonni_is_header_logo_image( $control ) {

	if ( $control->manager->get_setting( 'header_logo_type' )->value() == 'image' ) {
		return true;
	}

	return false;
}


/**
 * Return true if logo in header has text type. Otherwise - return false.
 *
 * @param  object $control
 * @return bool
 */
function miaittalonni_is_header_logo_text( $control ) {

	if ( $control->manager->get_setting( 'header_logo_type' )->value() == 'text' ) {
		return true;
	}

	return false;
}

/**
 * Return blog-featured-image true if blog layout type is default. Otherwise - return false.
 *
 * @param  object $control
 *
 * @return bool
 */
function miaittalonni_is_blog_featured_image( $control ){
	if ( $control->manager->get_setting( 'blog_layout_type' )->value() == 'default' ) {
		return true;
	}

	return false;
}

// Move native `site_icon` control (based on WordPress core) in custom section.
add_action( 'customize_register', 'miaittalonni_customizer_change_core_controls', 20 );
function miaittalonni_customizer_change_core_controls( $wp_customize ) {
	$wp_customize->get_control( 'site_icon' )->section      = 'miaittalonni_logo_favicon';
	$wp_customize->get_control( 'background_color' )->label = esc_html__( 'Body Background Color', 'miaittalonni' );
}

////////////////////////////////////
// Typography utility function    //
////////////////////////////////////
function miaittalonni_get_font_styles() {
	return apply_filters( 'miaittalonni_get_font_styles', array(
		'normal'  => esc_html__( 'Normal', 'miaittalonni' ),
		'italic'  => esc_html__( 'Italic', 'miaittalonni' ),
		'oblique' => esc_html__( 'Oblique', 'miaittalonni' ),
		'inherit' => esc_html__( 'Inherit', 'miaittalonni' ),
	) );
}

function miaittalonni_get_character_sets() {
	return apply_filters( 'miaittalonni_get_character_sets', array(
		'latin'        => esc_html__( 'Latin', 'miaittalonni' ),
		'greek'        => esc_html__( 'Greek', 'miaittalonni' ),
		'greek-ext'    => esc_html__( 'Greek Extended', 'miaittalonni' ),
		'vietnamese'   => esc_html__( 'Vietnamese', 'miaittalonni' ),
		'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'miaittalonni' ),
		'latin-ext'    => esc_html__( 'Latin Extended', 'miaittalonni' ),
		'cyrillic'     => esc_html__( 'Cyrillic', 'miaittalonni' ),
	) );
}

function miaittalonni_get_text_aligns() {
	return apply_filters( 'miaittalonni_get_text_aligns', array(
		'inherit' => esc_html__( 'Inherit', 'miaittalonni' ),
		'center'  => esc_html__( 'Center', 'miaittalonni' ),
		'justify' => esc_html__( 'Justify', 'miaittalonni' ),
		'left'    => esc_html__( 'Left', 'miaittalonni' ),
		'right'   => esc_html__( 'Right', 'miaittalonni' ),
	) );
}

function miaittalonni_get_font_weight() {
	return apply_filters( 'miaittalonni_get_font_weight', array(
		'100' => '100',
		'200' => '200',
		'300' => '300',
		'400' => '400',
		'500' => '500',
		'600' => '600',
		'700' => '700',
		'800' => '800',
		'900' => '900',
	) );
}

function miaittalonni_get_text_transform() {
	return apply_filters( 'miaittalonni_get_text_transform', array(
		'none'       => esc_html__( 'None ', 'miaittalonni' ),
		'uppercase'  => esc_html__( 'Uppercase ', 'miaittalonni' ),
		'lowercase'  => esc_html__( 'Lowercase', 'miaittalonni' ),
		'capitalize' => esc_html__( 'Capitalize', 'miaittalonni' ),
	) );
}

/**
 * Return array of arguments for dynamic CSS module
 *
 * @return array
 */
function miaittalonni_get_dynamic_css_options() {
	return apply_filters( 'miaittalonni_get_dynamic_css_options', array(
		'prefix'    => 'miaittalonni',
		'type'      => 'theme_mod',
		'single'    => true,
		'css_files' => array(
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/elements.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/header.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/forms.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/social.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/menus.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/post.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/navigation.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/footer.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/misc.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/site/buttons.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/plugins/builder.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/plugins/restaurant-menu.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/plugins/booked.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/widgets/widget-default.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/widgets/custom-post.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/widgets/instagram.css',
			MIAITTALONNI_THEME_DIR . '/assets/css/dynamic/widgets/subscribe.css',
		),
		'options' => array(
			'header_logo_font_style',
			'header_logo_font_weight',
			'header_logo_font_size',
			'header_logo_font_family',

			'body_font_style',
			'body_font_weight',
			'body_font_size',
			'body_line_height',
			'body_font_family',
			'body_letter_spacing',
			'body_text_align',

			'h1_font_style',
			'h1_font_weight',
			'h1_font_size',
			'h1_line_height',
			'h1_font_family',
			'h1_letter_spacing',
			'h1_text_align',

			'h2_font_style',
			'h2_font_weight',
			'h2_font_size',
			'h2_line_height',
			'h2_font_family',
			'h2_letter_spacing',
			'h2_text_align',

			'h3_font_style',
			'h3_font_weight',
			'h3_font_size',
			'h3_line_height',
			'h3_font_family',
			'h3_letter_spacing',
			'h3_text_align',

			'h4_font_style',
			'h4_font_weight',
			'h4_font_size',
			'h4_line_height',
			'h4_font_family',
			'h4_letter_spacing',
			'h4_text_align',

			'h5_font_style',
			'h5_font_weight',
			'h5_font_size',
			'h5_line_height',
			'h5_font_family',
			'h5_letter_spacing',
			'h5_text_align',

			'h6_font_style',
			'h6_font_weight',
			'h6_font_size',
			'h6_line_height',
			'h6_font_family',
			'h6_letter_spacing',
			'h6_text_align',

			'showcase_title_font_style',
			'showcase_title_font_weight',
			'showcase_title_font_size',
			'showcase_title_line_height',
			'showcase_title_font_family',
			'showcase_title_letter_spacing',
			'showcase_title_text_transform',

			'showcase_subtitle_font_style',
			'showcase_subtitle_font_weight',
			'showcase_subtitle_font_size',
			'showcase_subtitle_line_height',
			'showcase_subtitle_font_family',
			'showcase_subtitle_letter_spacing',
			'showcase_subtitle_text_transform',

			'header_showcase_bg_color',
			'header_showcase_bg_repeat',
			'header_showcase_bg_position_x',
			'header_showcase_bg_attachment',
			'header_showcase_color_mask',
			'header_showcase_opacity_mask',
			'showcase_title_color',
			'showcase_subtitle_color',
			'showcase_description_color',

			'breadcrumbs_font_style',
			'breadcrumbs_font_weight',
			'breadcrumbs_font_size',
			'breadcrumbs_line_height',
			'breadcrumbs_font_family',
			'breadcrumbs_letter_spacing',

			'pagination_font_style',
			'pagination_font_weight',
			'pagination_font_size',
			'pagination_line_height',
			'pagination_font_family',
			'pagination_letter_spacing',

			'regular_accent_color_1',
			'regular_accent_color_2',
			'regular_accent_color_3',
			'regular_text_color',
			'regular_link_color',
			'regular_link_hover_color',
			'regular_h1_color',
			'regular_h2_color',
			'regular_h3_color',
			'regular_h4_color',
			'regular_h5_color',
			'regular_h6_color',

			'invert_accent_color_1',
			'invert_accent_color_2',
			'invert_accent_color_3',
			'invert_text_color',
			'invert_link_color',
			'invert_link_hover_color',
			'invert_h1_color',
			'invert_h2_color',
			'invert_h3_color',
			'invert_h4_color',
			'invert_h5_color',
			'invert_h6_color',

			'header_bg_color',
			'header_bg_image',
			'header_bg_repeat',
			'header_bg_position_x',
			'header_bg_attachment',

			'page_404_bg_color',
			'page_404_bg_repeat',
			'page_404_bg_position_x',
			'page_404_bg_attachment',

			'top_panel_bg',

			'container_width',

			'footer_widgets_bg',
			'footer_bg',
		),
	) );
}

/**
 * Return array of arguments for Google Font loader module.
 *
 * @since  1.0.0
 * @return array
 */
function miaittalonni_get_fonts_options() {
	return apply_filters( 'miaittalonni_get_fonts_options', array(
		'prefix'  => 'miaittalonni',
		'type'    => 'theme_mod',
		'single'  => true,
		'options' => array(
			'body' => array(
				'family'  => 'body_font_family',
				'style'   => 'body_font_style',
				'weight'  => 'body_font_weight',
				'charset' => 'body_character_set',
			),
			'h1' => array(
				'family'  => 'h1_font_family',
				'style'   => 'h1_font_style',
				'weight'  => 'h1_font_weight',
				'charset' => 'h1_character_set',
			),
			'h2' => array(
				'family'  => 'h2_font_family',
				'style'   => 'h2_font_style',
				'weight'  => 'h2_font_weight',
				'charset' => 'h2_character_set',
			),
			'h3' => array(
				'family'  => 'h3_font_family',
				'style'   => 'h3_font_style',
				'weight'  => 'h3_font_weight',
				'charset' => 'h3_character_set',
			),
			'h4' => array(
				'family'  => 'h4_font_family',
				'style'   => 'h4_font_style',
				'weight'  => 'h4_font_weight',
				'charset' => 'h4_character_set',
			),
			'h5' => array(
				'family'  => 'h5_font_family',
				'style'   => 'h5_font_style',
				'weight'  => 'h5_font_weight',
				'charset' => 'h5_character_set',
			),
			'h6' => array(
				'family'  => 'h6_font_family',
				'style'   => 'h6_font_style',
				'weight'  => 'h6_font_weight',
				'charset' => 'h6_character_set',
			),
			'showcase_title' => array(
				'family'  => 'showcase_title_font_family',
				'style'   => 'showcase_title_font_style',
				'weight'  => 'showcase_title_font_weight',
				'charset' => 'showcase_title_character_set',
			),
			'showcase_subtitle' => array(
				'family'  => 'showcase_subtitle_font_family',
				'style'   => 'showcase_subtitle_font_style',
				'weight'  => 'showcase_subtitle_font_weight',
				'charset' => 'showcase_subtitle_character_set',
			),
			'header_logo' => array(
				'family'  => 'header_logo_font_family',
				'style'   => 'header_logo_font_style',
				'weight'  => 'header_logo_font_weight',
				'charset' => 'header_logo_character_set',
			),
			'breadcrumbs' => array(
				'family'  => 'breadcrumbs_font_family',
				'style'   => 'breadcrumbs_font_style',
				'weight'  => 'breadcrumbs_font_weight',
				'charset' => 'breadcrumbs_character_set',
			),
			'pagination' => array(
				'family'  => 'pagination_font_family',
				'style'   => 'pagination_font_style',
				'weight'  => 'pagination_font_weight',
				'charset' => 'pagination_character_set',
			),
		)
	) );
}

/**
 * Get default top panel text.
 *
 * @since  1.0.0
 * @return string
 */
function miaittalonni_get_default_top_panel_text() {
	return sprintf(
		esc_html__( '<div class="info-block">%s 6087 Richmond hwy, Alexandria, VA</div><div class="info-block">%s <a href="tel:#">703 329 0632</a></div><div class="info-block">%s Mo-Fr 11:00-00:00, Sa-Sa 15:00-00:00</div>', 'miaittalonni' ),
		'<i class="fa fa-map-marker"></i>',
		'<i class="fa fa-phone"></i>',
		'<i class="fa fa-clock-o"></i>'
	);
}

/**
 * Get default footer copyright.
 *
 * @since  1.0.0
 * @return string
 */
function miaittalonni_get_default_footer_copyright() {
	return esc_html__( '&copy; %%year%% Miaittalonni. All Rights Reserved. <a href="%%terms-of-use%%">Terms of use</a> and <a href="%%privacy-policy%%">Privacy Policy</a>', 'miaittalonni' );
}

/**
 * Get default showcase description.
 *
 * @since  1.0.0
 * @return string
 */
function miaittalonni_get_default_showcase_description() {
	return esc_html__( 'A place where food and coziness compliment each other.<br> Call (555)123-4567', 'miaittalonni' );
}
