<?php

add_action('widgets_init', create_function('', 'return register_widget("booked_calendar");'));

class booked_calendar extends WP_Widget {

    function booked_calendar() {
        parent::__construct(false, $name = __('Appointment Calendar','booked'));
    }
    
    function form($instance) {
	
	    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
	    $calendar = isset($instance['booked_calendar_chooser']) ? $instance['booked_calendar_chooser'] : 0;
	    
	    $args = array(
			'taxonomy'			=> 'booked_custom_calendars',
			'show_option_none' 	=> 'Default',
			'option_none_value'	=> 0,
			'hide_empty'		=> 0,
			'echo'				=> 0,
			'orderby'			=> 'name',
			'id'				=> $this->get_field_id('booked_calendar_chooser'),
			'name'				=> $this->get_field_name('booked_calendar_chooser'),
			'selected'			=> $calendar
		);

		if (!get_option('booked_hide_default_calendar')): $args['show_option_all'] = __('Default Calendar','booked'); endif;
	
	    ?>
	
		<p>
	      	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title','booked'); ?>:</label>
	      	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
	    
	    <p>
	      	<label><?php _e('Calendar to Display','booked'); ?>:</label><br>
	      	<?php echo str_replace( "\n", '', wp_dropdown_categories( $args ) ); ?>
	    </p>
	    
	    <?php
	}

    function widget($args, $instance) {
        
        extract( $args );

		// these are our widget options
		$widget_title = isset($instance['title']) ? $instance['title'] : false;
	    $title = apply_filters('widget_title', $widget_title);
	    $calendar = isset($instance['booked_calendar_chooser']) ? $instance['booked_calendar_chooser'] : false;
	
	    echo $before_widget;
	
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		echo do_shortcode('[booked-calendar size="small"'.($calendar ? ' calendar="'.$calendar.'"' : '').']');
	    
	    echo $after_widget;
	
	}
	
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['booked_calendar_chooser'] = $new_instance['booked_calendar_chooser'];
		return $instance;
    }

}