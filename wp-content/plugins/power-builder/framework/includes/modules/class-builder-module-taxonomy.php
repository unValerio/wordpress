<?php
class Tm_Builder_Module_Taxonomy extends Tm_Builder_Module {

	protected $settings = array(
		'terms_type',
		'category',
		'post_tag',
		'post_format',
		'super_title',
		'title',
		'subtitle',
		'title_delimiter',
		'title_length',
		'description_length',
		'post_count',
		'more',
		'more_text',
		'layout_type',
		'columns',
		'padding',
		'disabled_on',
		'admin_label',

		/*  Custom CSS  */
		'module_id',
		'module_class',

		/* system variable */
		'term_id',
	);

	private $function_name = null;

	public function init() {
		$this->name					= esc_html__( 'Taxonomy', 'tm_builder' );
		$this->icon					= 'f02c';
		$this->slug					= 'tm_pb_taxonomy';
		$this->main_css_element		= '%%order_class%%.tm_pb_taxonomy';

		$this->whitelisted_fields	= $this->settings;

		$this->fields_defaults		= array(
			'terms_type'			=> array( 'category_name' ),
			'super_title'			=> array( '' ),
			'title'					=> array( '' ),
			'subtitle'				=> array( '' ),
			'title_delimiter'		=> array( 'on' ),
			'title_length'			=> array( '5' ),
			'description_length'	=> array( '5' ),
			'post_count'			=> array( 'on' ),
			'more'					=> array( 'on' ),
			'more_text'				=> array( 'more' ),
			'layout_type'			=> array( 'grid' ),
			'columns'				=> array( '3' ),
			'padding'				=> array( '10' ),
		);
	}

	public function get_fields() {
		return array(
			/*  General Settings  */
			'terms_type' => array(
				'label'					=> esc_html__( 'Choose taxonomy type', 'tm_builder' ),
				'type'					=> 'select',
				'option_category'		=> 'basic_option',
				'options'				=> array(
					'category'			=> esc_html__( 'Categories', 'tm_builder' ),
					'post_tag'				=> esc_html__( 'Tag', 'tm_builder' ),
					//'post_format'			=> esc_html__( 'Post Format', 'tm_builder' ),
				),
				'affects'				=> array(
					'#tm_pb_category',
					'#tm_pb_post_tag',
					'#tm_pb_post_format',
					'#tm_pb_include',
					'#tm_pb_posts_per_page',
					//'#tm_pb_post_offset',
				),
				'description'			=> esc_html__( 'Choose taxonomy type', 'tm_builder' ),
			),
			'category' => array(
				'label'					=> esc_html__( 'Include categories', 'tm_builder' ),
				'option_category'		=> 'basic_option',
				'depends_show_if'		=> 'category',
				'renderer'				=> 'tm_builder_include_categories_option',
				'renderer_options'		=> array(
					'use_terms' => false,
					'input_name' => 'tm_pb_category',
				),
				'description'			=> esc_html__( 'Choose which categories you would like to include.', 'tm_builder' ),
			),
			'post_tag' => array(
				'label'					=> esc_html__( 'Include tags', 'tm_builder' ),
				'option_category'		=> 'basic_option',
				'depends_show_if'		=> 'post_tag',
				'renderer'				=> 'tm_builder_include_categories_option',
				'renderer_options'		=> array(
					'use_terms'  => true,
					'term_name'  => 'post_tag',
					'input_name' => 'tm_pb_post_tag',
				),
				'description'		=> esc_html__( 'Choose which categories you would like to include.', 'tm_builder' ),
			),
			/*'post_format' => array(
				'label'					=> esc_html__( 'Include post format', 'tm_builder' ),
				'option_category'		=> 'basic_option',
				'depends_show_if'		=> 'post_format',
				'renderer'				=> 'tm_builder_include_categories_option',
				'renderer_options'		=> array(
					'use_terms'	=> true,
					'term_name'	=> 'post_format',
					'input_name' => 'tm_pb_post_format',
				),
				'description'			=> esc_html__( 'Choose which post format you would like to include/', 'tm_builder' ),
			),*/
			'super_title' => array(
				'label'					=> esc_html__( 'Super Title', 'tm_builder' ),
				'type'					=> 'text',
				'option_category'		=> 'configuration',
				'default'				=> $this->fields_defaults['super_title'][0],
			),
			'title' => array(
				'label'					=> esc_html__( 'Title', 'tm_builder' ),
				'type'					=> 'text',
				'option_category'		=> 'configuration',
				'default'				=> $this->fields_defaults['title'][0],
			),
			'subtitle' => array(
				'label'					=> esc_html__( 'Sub Title', 'tm_builder' ),
				'type'					=> 'text',
				'option_category'		=> 'configuration',
				'default'				=> $this->fields_defaults['subtitle'][0],
			),
			'title_delimiter' => array(
				'label'					=> esc_html__( 'Display title delimiter', 'tm_builder' ),
				'type'					=> 'yes_no_button',
				'option_category'		=> 'configuration',
				'options'				=> array(
					'on'		=> esc_html__( 'Yes', 'tm_builder' ),
					'off'		=> esc_html__( 'No', 'tm_builder' ),
				),
				'affects'				=> array(
					'#tm_pb_background_color',
				),
			),
			'title_length' => array(
				'label'					=> esc_html__( 'Title words length ( Set 0 to hide title. )', 'tm_builder' ),
				'option_category'		=> 'basic_option',
				'type'					=> 'range',
				'default'				=> '5',
			),
			'description_length' => array(
				'label'					=> esc_html__( 'Description words length ( Set 0 to hide excerpt. )', 'tm_builder' ),
				'option_category'		=> 'basic_option',
				'type'					=> 'range',
				'default'				=> '5',
			),
			'post_count' => array(
				'label'					=> esc_html__( 'Display post count in terms', 'tm_builder' ),
				'type'					=> 'yes_no_button',
				'option_category'		=> 'configuration',
				'options'				=> array(
					'on'		=> esc_html__( 'Yes', 'tm_builder' ),
					'off'		=> esc_html__( 'No', 'tm_builder' ),
				),
			),
			'more' => array(
				'label'					=> esc_html__( 'Display more button', 'tm_builder' ),
				'type'					=> 'yes_no_button',
				'option_category'		=> 'configuration',
				'options'				=> array(
					'on'		=> esc_html__( 'Yes', 'tm_builder' ),
					'off'		=> esc_html__( 'No', 'tm_builder' ),
				),
				'affects'				=> array(
					'#tm_pb_more_text',
				),
			),
			'more_text' => array(
				'label'					=> esc_html__( 'More button text', 'tm_builder' ),
				'type'					=> 'text',
				'option_category'		=> 'configuration',
				'depends_show_if'		=> 'on',
				'default'				=> $this->fields_defaults['more_text'][0],
			),
			'layout_type' => array(
				'label'					=> esc_html__( 'Choose Layout Type', 'tm_builder' ),
				'type'					=> 'select',
				'option_category'		=> 'basic_option',
				'options'				=> apply_filters( 'tm_pb_module_taxonomy_layout_type',
					array(
						'grid'			=> esc_html__( 'Grid', 'tm_builder' ),
						'tiles'			=> esc_html__( 'Tiles', 'tm_builder' ),
					)
				),
				'affects'				=> array(
					'#tm_pb_columns',

				),
			),
			'columns' => array(
				'label'					=> esc_html__( 'Columns number', 'tm_builder' ),
				'option_category'		=> 'configuration',
				'type'					=> 'range',
				'default'				=> '3',
				'depends_show_if'		=> 'grid',
				'range_settings' => array(
					'min'  => '1',
					'max'  => '4',
					'step' => '1',
				),
			),
			'padding' => array(
				'label'					=> esc_html__( 'Items padding ( size in pixels )', 'tm_builder' ),
				'option_category'		=> 'configuration',
				'type'					=> 'range',
				'default'				=> '20',
				'range_settings' => array(
					'min'  => '0',
					'max'  => '50',
					'step' => '1',
				),
			),

			'disabled_on' => array(
				'label'					=> esc_html__( 'Disable on', 'tm_builder' ),
				'type'					=> 'multiple_checkboxes',
				'options'				=> array(
					'phone'		=> esc_html__( 'Phone', 'tm_builder' ),
					'tablet'	=> esc_html__( 'Tablet', 'tm_builder' ),
					'desktop'	=> esc_html__( 'Desktop', 'tm_builder' ),
				),
				'additional_att'		=> 'disable_on',
				'option_category'		=> 'configuration',
				'description'			=> esc_html__( 'This will disable the module on selected devices', 'tm_builder' ),
			),
			'admin_label' => array(
				'label'					=> esc_html__( 'Admin Label', 'tm_builder' ),
				'type'					=> 'text',
				'option_category'		=> 'configuration',
				'description'			=> esc_html__( 'This will change the label of the module in the builder for easy identification.', 'tm_builder' ),
			),

			/*  Custom CSS  */
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'tm_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'tm_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'tm_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'tm_pb_custom_css_regular',
			),
		);
	}

	public function get_items(){
		$terms_type		= $this->_var( 'terms_type' );
		$taxonomy		= $this->_var( $terms_type );

		$terms = get_terms( $terms_type, array('include' => $taxonomy, 'hide_empty' => false ) );

		if ( empty( $terms ) ) {
			return '';
		}

		$items = '';

		$columns = $this->_var( 'columns' );
		$columns_class = ( int ) ( 12 / $columns ) ;
		$items_grid_class = apply_filters( 'tm_pb_module_taxonomy_items_class', 'col-xs-12 col-sm-12 col-md-6 ');
		$items_class = $items_grid_class . ' col-lg-' . $columns_class . ' col-xl-' . $columns_class;
		$columns = $this->_var( 'items_class', $items_class );

		$title_length = $this->_var( 'title_length' );
		$title_visible = ( '0' === $title_length ) ? false : true ;

		$button_text = $this->_var( 'more_text' );
		$button_visible = ( ! $button_text || 'on' !== $this->_var( 'more' ) ) ? false : true ;

		$post_count = ( 'on' !== $this->_var( 'post_count' ) ) ? false : true ;

		$description_length = $this->_var( 'description_length' );
		$description_visible = ( '0' === $description_length ) ? false : true ;

		$layout_type = $this->_var( 'layout_type');
		$template_count_min = apply_filters( 'tm_pb_module_taxonomy_template_count_min', 1 );
		$template_count = $template_count_min;
		$template_count_max = apply_filters( 'tm_pb_module_taxonomy_template_count_max', 3);

		foreach ( $terms as $term_key => $term ){

			$this->_var( 'term_id' , $term->term_id );

			$title = tm_builder_core()->utility()->attributes->get_title(
				apply_filters( 'tm_pb_module_taxonomy_title_settings',
					array(
						'visible'		=> $title_visible,
						'length'		=> $title_length,
						'html'			=> '<h6 %1$s><a href="%2$s" %3$s>%4$s</a></h6>',
					)
				),
				'term',
				$term->term_id
			);
			$this->_var( 'term_title' , $title );

			$description = tm_builder_core()->utility()->attributes->get_content(
				apply_filters( 'tm_pb_module_taxonomy_content_settings',
					array(
						'visible'		=> $description_visible,
						'length'		=> $description_length,
						'content_type'	=> 'description',
					)
				),
				'term',
				$term->term_id
			);
			$this->_var( 'description' , $description );

			$count = tm_builder_core()->utility()->meta_data->get_post_count_in_term(
				apply_filters( 'tm_pb_module_taxonomy_content_settings',
					array(
						'visible'		=> $post_count,
						'sufix'			=> _n_noop( '%s post', '%s posts', '__tm' ),
						'html'			=> '%1$s<span %4$s>%5$s%6$s</span>',
					)
				),
				$term->term_id
			);
			$this->_var( 'count' , $count );

			$button = tm_builder_core()->utility()->attributes->get_button(
				apply_filters( 'tm_pb_module_taxonomy_button_settings',
					array(
						'visible'	=> $button_visible,
						'text'		=> $button_text,
						'html'		=> '<span class="button--holder"><a href="%1$s" %2$s %3$s><span class="btn__text">%4$s</span>%5$s</a></span>',

					)
				),
				'term',
				$term->term_id
			);
			$this->_var( 'button' , $button );

			$permalink = tm_builder_core()->utility()->attributes->get_term_permalink( $term->term_id );
			$this->_var( 'permalink' , $permalink );

			if ( 'tiles' === $layout_type ) {
				$template_part = 'taxonomy/taxonomy-' . $layout_type . '-item-' . $template_count . '.php';

				if( $template_count !== $template_count_max ){
					$template_count++;
				}else{
					$template_count = $template_count_min;
				}
			}else{
				$template_part = 'taxonomy/taxonomy-' . $layout_type . '-item.php';
			}
			$items .= $this->get_template_part( $template_part );

		}

		return $items;
	}

	/**
	 * Aggregates all blog-related styles definitions
	 */
	public function set_styles() {

		$items_padding = $this->_var( 'padding' );
		$style = apply_filters( 'tm_pb_module_taxonomy_style', array(
			'holder_style'		=> array(
				'selector'		=> '%%order_class%%.tm_pb_taxonomy .tm_pb_taxonomy__wrapper',
				'declaration'	=> 'margin: 0 0 0 -' . $items_padding . 'px;',
			),
			'inner_style'		=> array(
				'selector'		=> '%%order_class%%.tm_pb_taxonomy .tm_pb_taxonomy__inner',
				'declaration'	=> 'margin: 0 0 ' . $items_padding . 'px ' . $items_padding . 'px; width: calc(100% - ' . $items_padding . 'px)',
			),
			'tiles_item_type_2' => array(
				'selector'		=> '%%order_class%%.tm_pb_taxonomy .tm_pb_taxonomy__inner.tiles-item-type-2',
				'declaration'	=> 'width: calc(50% - ' . $items_padding . 'px); float: left;',
				'media_query'	=> TM_Builder_Element::get_media_query( 'md' ),
			),
		), $items_padding );

		if ( ! empty( $style ) ) {
			foreach ( $style as $key => $value ) {
				TM_Builder_Element::set_style( $this->function_name, $value );
			}
		}
	}

	public function shortcode_callback( $atts, $content = null, $function_name ) {
		$this->function_name = $function_name;
		$this->set_vars( $this->settings );

		$this->set_styles();

		$delimiter = ( 'on' === $this->_var( 'title_delimiter' ) ) ? '<span class="title-delimiter"></span>' : '' ;
		$this->_var( 'delimiter' , $delimiter  );

		$this->_var( 'items' , $this->get_items() );

		$taxonomy_class = 'tm_pb_taxonomy_' . $this->_var( 'layout_type');

		$content = $this->get_template_part( 'taxonomy/taxonomy.php' );
		$classes = array( $taxonomy_class );
		$output  = $this->wrap_module( $content, $classes, $function_name );

		return $output;
	}
}
new Tm_Builder_Module_Taxonomy;
