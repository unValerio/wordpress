<?php

$background_options = array(
	'background_image'        => '',
	'background_position'     => 'center',
	'background_repeat'       => 'no-repeat',
	'background_attachment'   => '',
	'background_size'         => '',
	'background_color'        => '',
	'invert_text_colorscheme' => '',
);

$widget_title_css = '';
$output           = array();

foreach ( $background_options as $property => $default_value ) {

	$value = $default_value;

	if ( ! empty( $instance[ $property ] ) ) {

		switch ( $property ) {
			case 'background_position':
				$value = str_replace( '-', ' ', $instance[ $property ] );
			break;

			case 'background_image':
				$value = wp_get_attachment_image_src( $instance[ $property ], 'full' );
				if ( is_array( $value ) && ! empty( $value ) ) {
					$value = sprintf( 'url("%s")', esc_url( $value[0] ) );
				}
			break;

			case 'invert_text_colorscheme':
				if ( is_array( $instance[ $property ] ) && isset( $instance[ $property ][ $property ] ) ) {
					$value = get_theme_mod(
						sprintf(
							'%s_text_color',
							'true' === $instance[ $property ][ $property ] ? 'invert' : 'regular'
						)
					);

					// Add widget header color css rules
					if ( ! empty( $value ) ) {
						$widget_title_css = sprintf(
							'#%1$s .widget-title { color: %2$s; }',
							esc_html( $args['widget_id'] ),
							esc_html( $value )
						);
					}
				}
				$property = 'color';
			break;

			default:
				$value = $instance[ $property ];
			break;
		}
	}

	if ( ! empty( $value ) ) {
		$output[ $property ] = sprintf( '%s: %s;',
			str_replace( '_', '-', esc_html( $property ) ),
			'background_image' === $property ? $value : esc_html( $value )
		);
	}
}

// Remove background options if no background image is set
if ( ! isset( $output['background_image'] ) ) {
	unset( $output['background_position'] );
	unset( $output['background_repeat'] );
	unset( $output['background_size'] );
}

if ( 0 < sizeof( $output ) ) {
	echo sprintf(
		'<style scoped>%1$s {%2$s}%3$s</style>',
		'.subscribe-follow__wrap',
		join( '', $output ),
		$widget_title_css
	);
}
