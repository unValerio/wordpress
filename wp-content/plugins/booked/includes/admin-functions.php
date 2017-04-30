<?php

function booked_render_single_timeslot_form($timeslot_intervals,$type = false){

	ob_start();

	booked_render_count_select('count','How many?');

	?><span class="allday-wrap"><input type="checkbox" id="all_day<?php echo $type ? '_'.$type : ''; ?>" name="all_day" value="1"> <label for="all_day<?php echo $type ? '_'.$type : ''; ?>"><?php _e('All day time slot','booked'); ?></label></span><?php

	echo booked_render_time_select('startTime',$timeslot_intervals,__('Start time ...','booked'));
	echo booked_render_time_select('endTime',$timeslot_intervals,__('End time ...','booked'));

	$html = ob_get_clean();

	return apply_filters( 'booked_single_timeslot_form', $html );

}

function booked_render_bulk_timeslot_form($timeslot_intervals,$type = false){

	ob_start();

	booked_render_count_select('count',__('# of Each ...','booked'));
	echo booked_render_time_select('startTime',$timeslot_intervals,__('Start time ...','booked'));
	echo booked_render_time_select('endTime',$timeslot_intervals,__('End time ...','booked'));
	booked_render_time_between_select('time_between',__('Time between ...','booked'));
	booked_render_interval_select('interval',__('Appt Length ...','booked'));

	$html = ob_get_clean();

	return apply_filters( 'booked_bulk_timeslot_form', $html );

}

function booked_render_time_select($select_name,$interval,$placeholder){
	$time = 0;
	$time_format = get_option('time_format');

	$html = '<select name="'.$select_name.'">';
	$html .= '<option value="">'.$placeholder.'</option>';
		do {
			$time_display = booked_convertTime($time);
			$html .= '<option value="'.date('Hi',strtotime('2014-01-01 '.$time_display)).'">'.date($time_format,strtotime('2014-01-01 '.$time_display)).'</option>';
			$time = $time + $interval;
		} while ($time < 1440);
		$html .= '<option value="2400">'.date($time_format,strtotime('2014-01-01 24:00')).'</option>';
	$html .= '</select>';

	return apply_filters( 'booked_time_select_field', $html );
}

function booked_render_timeslots($calendar_id = false){

	if ($calendar_id):
		$booked_defaults = get_option('booked_defaults_'.$calendar_id);
	else :
		$booked_defaults = get_option('booked_defaults');
	endif;

	$time_format = get_option('time_format');
	$first_day_of_week = (get_site_option('start_of_week') == 0 ? 7 : 1);

	$day_loop = array(
		__('Sun','booked'),
		__('Mon','booked'),
		__('Tue','booked'),
		__('Wed','booked'),
		__('Thu','booked'),
		__('Fri','booked'),
		__('Sat','booked')
	);

	// Need to use the english three-letter day names to save settings properly
	$day_loop_english_array = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');

	if ($first_day_of_week == 1):
		$sunday_item = array_shift($day_loop); $day_loop[] = $sunday_item;
		$sunday_item_array = array_shift($day_loop_english_array); $day_loop_english_array[] = $sunday_item_array;
	endif;

	?><table class="booked-timeslots"<?php echo ($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : ''); ?>>
		<thead>
			<tr>
				<?php foreach($day_loop as $day):
					echo '<th>'.$day.'</th>';
				endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php foreach($day_loop_english_array as $day): ?>
					<td data-day="<?php echo $day; ?>" class="addTimeslot"><span class="cancel button"><?php _e('Cancel','booked'); ?></span><a href="#" class="button"><?php _e('Add ...','booked'); ?></a><a href="#" class="button-mobile"><i class="fa fa-plus"></i></a></td>
				<?php endforeach; ?>
			</tr>
			<tr>
				<?php foreach($day_loop_english_array as $day):

					echo '<td class="dayTimeslots" data-day="'.$day.'">';
						if (!empty($booked_defaults[$day])):
							ksort($booked_defaults[$day]);
							foreach($booked_defaults[$day] as $time => $count):
								echo booked_render_timeslot_info($time_format,$time,$count);
							endforeach;
						else :
							echo '<p><small>'.__('No time slots.','booked').'</small></p>';
						endif;
					echo '</td>';

				endforeach; ?>
			</tr>
		</tbody>
	</table><?php

}

function booked_render_timeslot_info($time_format,$time,$count){

	$html = '<span class="timeslot" data-timeslot="'.$time.'">';
		$time = explode('-',$time);

		do_action( 'booked_single_timeslot_before', $time );

		if ($time[0] == '0000' && $time[1] == '2400'):
			$timeslotText = '<span class="start" style="color:#aaa">' . strtoupper(__('All day time slot','booked')) . '</span>';
		else :
			$timeslotText = '<span class="start">'.date($time_format,strtotime('2014-01-01 '.$time[0])).'</span> &ndash; <span class="end">'.date($time_format,strtotime('2014-01-01 '.$time[1])).'</span>';
		endif;

		$html .= $timeslotText;
		$html .= '<span class="slotsBlock">';
			$html .= '<span class="changeCount minus" data-count="-1"><i class="fa fa-minus-circle"></i></span>';
			$html .= '<span class="count"><em>'.$count.'</em> '._n('slot','slots',$count,'booked').'</span>';
			$html .= '<span class="changeCount add" data-count="1"><i class="fa fa-plus-circle"></i></span>';
		$html .= '</span>';
		$html .= '<span class="delete"><i class="fa fa-remove"></i></span>';

		do_action( 'booked_single_timeslot_after', $time );

	$html .= '</span>';

	return $html;

}

function booked_render_interval_select($select_name,$placeholder){
	echo '<select name="'.$select_name.'">'; ?>
	<option value="60" selected><?php _e('Every 1 hour','booked'); ?></option>
	<option value="90"><?php _e('Every 1 hour, 30 minutes','booked'); ?></option>
	<option value="120"><?php _e('Every 2 hours','booked'); ?></option>
	<option value="45"><?php _e('Every 45 minutes','booked'); ?></option>
	<option value="30"><?php _e('Every 30 minutes','booked'); ?></option>
	<option value="20"><?php _e('Every 20 minutes','booked'); ?></option>
	<option value="15"><?php _e('Every 15 minutes','booked'); ?></option>
	<option value="10"><?php _e('Every 10 minutes','booked'); ?></option>
	<option value="5"><?php _e('Every 5 minutes','booked'); ?></option>
	<?php echo '</select>';
}

function booked_render_time_between_select($select_name,$placeholder){
	echo '<select name="'.$select_name.'">'; ?>
	<option value="0" selected><?php echo $placeholder; ?></option>
	<option value="5"><?php _e('5 minutes','booked'); ?></option>
	<option value="10"><?php _e('10 minutes','booked'); ?></option>
	<option value="15"><?php _e('15 minutes','booked'); ?></option>
	<option value="20"><?php _e('20 minutes','booked'); ?></option>
	<option value="30"><?php _e('30 minutes','booked'); ?></option>
	<option value="45"><?php _e('45 minutes','booked'); ?></option>
	<option value="60"><?php _e('1 hour','booked'); ?></option>
	<?php echo '</select>';
}

function booked_render_count_select($select_name,$placeholder){
	echo '<select name="'.$select_name.'">'; ?>
	<option value="1" selected><?php _e('1 time slot','booked'); ?></option>
	<option value="2"><?php _e('2 time slots','booked'); ?></option>
	<option value="3"><?php _e('3 time slots','booked'); ?></option>
	<option value="4"><?php _e('4 time slots','booked'); ?></option>
	<option value="5"><?php _e('5 time slots','booked'); ?></option>
	<option value="6"><?php _e('6 time slots','booked'); ?></option>
	<option value="7"><?php _e('7 time slots','booked'); ?></option>
	<option value="8"><?php _e('8 time slots','booked'); ?></option>
	<option value="9"><?php _e('9 time slots','booked'); ?></option>
	<option value="10"><?php _e('10 time slots','booked'); ?></option>
	<option value="11"><?php _e('11 time slots','booked'); ?></option>
	<option value="12"><?php _e('12 time slots','booked'); ?></option>
	<option value="13"><?php _e('13 time slots','booked'); ?></option>
	<option value="14"><?php _e('14 time slots','booked'); ?></option>
	<option value="15"><?php _e('15 time slots','booked'); ?></option>
	<option value="16"><?php _e('16 time slots','booked'); ?></option>
	<option value="17"><?php _e('17 time slots','booked'); ?></option>
	<option value="18"><?php _e('18 time slots','booked'); ?></option>
	<option value="19"><?php _e('19 time slots','booked'); ?></option>
	<option value="20"><?php _e('20 time slots','booked'); ?></option>
	<?php echo '</select>';
}

function booked_admin_set_timezone(){
	$timezone_seconds = (int)get_site_option('gmt_offset') * 3600;
	$timezone_name = timezone_name_from_abbr(null, $timezone_seconds, true);
	date_default_timezone_set($timezone_name);
}

function booked_admin_calendar($year = false,$month = false,$calendar_id = false,$size = false){

	$local_time = current_time('timestamp');

	$year = ($year ? $year : date('Y',$local_time));
	$month = ($month ? $month : date('m',$local_time));
	$today = date('j',$local_time); // Defaults to current day
	$last_day = date('t',strtotime($year.'-'.$month));

	$monthShown = date($year.'-'.$month.'-01');
	$currentMonth = date('Y-m-01',$local_time);

	$first_day_of_week = (get_site_option('start_of_week') == 0 ? 7 : 1); 	// 1 = Monday, 7 = Sunday, Get from WordPress Settings

	$start_timestamp = strtotime($year.'-'.$month.'-01 00:00:00');
	$end_timestamp = strtotime($year.'-'.$month.'-'.$last_day.' 23:59:59');

	$args = array(
		'post_type' => 'booked_appointments',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'meta_query' => array(
			array(
				'key'     => '_appointment_timestamp',
				'value'   => array( $start_timestamp, $end_timestamp ),
				'compare' => 'BETWEEN',
			)
		)
	);

	if ($calendar_id):
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'booked_custom_calendars',
				'field'    => 'id',
				'terms'    => $calendar_id,
			)
		);
	endif;

	$bookedAppointments = new WP_Query($args);
	if($bookedAppointments->have_posts()):
		while ($bookedAppointments->have_posts()):
			$bookedAppointments->the_post();
			global $post;
			$timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
			$day = date('j',$timestamp);
			$appointments_array[$day][$post->ID]['timestamp'] = $timestamp;
			$appointments_array[$day][$post->ID]['status'] = $post->post_status;
		endwhile;
		$appointments_array = apply_filters('booked_appointments_array', $appointments_array);
	endif;

	// Appointments Array
	// [DAY] => [POST_ID] => [TIMESTAMP/STATUS]

	if ($calendar_id):
		$calendar_name = get_term_by('id',$calendar_id,'booked_custom_calendars');
		$calendar_name = $calendar_name->name;
	else :
		$calendar_name = false;
	endif;
	
	$hide_weekends = get_option('booked_hide_weekends',false);

	?><table class="booked-calendar<?php echo ($size ? ' '.$size : ''); ?>"<?php echo ($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : ''); ?> data-monthShown="<?php echo $monthShown; ?>">
		<thead>
			<tr>
				<th colspan="<?php if (!$hide_weekends): ?>7<?php else: ?>5<?php endif; ?>">
					<a href="#" data-goto="<?php echo date('Y-m-01', strtotime("-1 month", strtotime($year.'-'.$month.'-01'))); ?>" class="page-left"><i class="fa fa-arrow-left"></i></a>
					<span class="calendarSavingState">
						<i class="fa fa-refresh fa-spin"></i>
					</span>
					<span class="monthName">
						<?php if ($monthShown != $currentMonth): ?>
							<a href="#" class="backToMonth" data-goto="<?php echo $currentMonth; ?>"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;<?php _e('Back to','booked'); ?> <?php echo date_i18n('F',strtotime($currentMonth)); ?></a>
						<?php endif; ?>
						<?php echo date_i18n("F Y", strtotime($year.'-'.$month.'-01')); ?>
						<?php if ($calendar_name): ?>
							<span class="calendarName"><i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;<?php echo $calendar_name; ?></span>
						<?php endif; ?>
					</span>
					<a href="#" data-goto="<?php echo date('Y-m-01', strtotime("+1 month", strtotime($year.'-'.$month.'-01'))); ?>" class="page-right"><i class="fa fa-arrow-right"></i></a>
				</th>
			</tr>
			<tr class="days">
				<?php if ($first_day_of_week == 7 && !$hide_weekends): echo '<th>'.__('Sun','booked').'</th>'; endif; ?>
				<th><?php _e('Mon','booked'); ?></th>
				<th><?php _e('Tue','booked'); ?></th>
				<th><?php _e('Wed','booked'); ?></th>
				<th><?php _e('Thu','booked'); ?></th>
				<th><?php _e('Fri','booked'); ?></th>
				<?php if (!$hide_weekends): ?><th><?php _e('Sat','booked'); ?></th><?php endif; ?>
				<?php if ($first_day_of_week == 1 && !$hide_weekends): echo '<th>'.__('Sun','booked').'</th>'; endif; ?>
			</tr>
		</thead>
		<tbody><?php

			$today_date = date('Y',$local_time).'-'.date('m',$local_time).'-'.date('j',$local_time);
			$days = date("t",strtotime($year.'-'.$month.'-01'));		// Days in current month
			$lastmonth = date("t", mktime(0,0,0,$month-1,1,$year)); 	// Days in previous month

			$start = date("N", mktime(0,0,0,$month,1,$year)); 			// Starting day of current month
			if ($first_day_of_week == 7): $start = $start + 1; endif;
			if ($start > 7): $start = 1; endif;
			$finish = $days; 											// Finishing day of current month
			$laststart = $start - 1; 									// Days of previous month in calander

			$counter = 1;
			$nextMonthCounter = 1;

			if ($calendar_id):
				$booked_defaults = get_option('booked_defaults_'.$calendar_id);
				if (!$booked_defaults):
					$booked_defaults = get_option('booked_defaults');
				endif;
			else :
				$booked_defaults = get_option('booked_defaults');
			endif;

			$booked_defaults = booked_apply_custom_timeslots_filter($booked_defaults,$calendar_id);

			if($start > 5){ $rows = 6; } else { $rows = 5; }

			for($i = 1; $i <= $rows; $i++){
				echo '<tr class="week">';
				for($x = 1; $x <= 7; $x++){

					$classes = array();

					$appointments_count = 0;

					if(($counter - $start) < 0){

						$date = (($lastmonth - $laststart) + $counter);
						$classes[] = 'blur';
						$check_month = $month - 1;

					} else if(($counter - $start) >= $days){

						$date = $nextMonthCounter;
						$nextMonthCounter++;
						$classes[] = 'blur';
						$check_month = $month + 1;

					} else {
						
						$check_month = $month;

						$date = ($counter - $start + 1);
						if($today == $counter - $start + 1){
							if ($today_date == $year.'-'.$month.'-'.$date):
								$classes[] = 'today';
							endif;
						}

						$day_name = date('D',strtotime($year.'-'.$month.'-'.$date));
						$full_count = (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name]) ? $booked_defaults[$day_name] : false);
						$total_full_count = 0;
						if ($full_count):
							foreach($full_count as $full_counter){
								$total_full_count = $total_full_count + $full_counter;
							}
						endif;

						if (isset($appointments_array[$date]) && !empty($appointments_array[$date])):
							$appointments_count = count($appointments_array[$date]);
							if ($appointments_count > 0 && $appointments_count < $total_full_count): $classes[] = 'partial';
							elseif ($appointments_count >= $total_full_count): $classes[] = 'booked'; endif;
						endif;

					}
					
					$day_of_week = date('N',strtotime($year.'-'.$check_month.'-'.$date));
						
					if ($hide_weekends && $day_of_week == 6 || $hide_weekends && $day_of_week == 7):
					
						$html = '';
					
					else:

						echo '<td data-date="'.$year.'-'.$check_month.'-'.$date.'" class="'.implode(' ',$classes).'">';
							echo '<span class="date"><span class="number">'. $date . '</span></span>';
							if ($appointments_count > 0): echo '<span class="count">'.$appointments_count.' '._n('appointment','appointments',$appointments_count,'booked').'</span>'; endif;
						echo '</td>';

					endif;

					$counter++;
					$class = '';
				}
				echo '</tr>';
			} ?>
		</tbody>
	</table><?php

}

function booked_admin_calendar_date_content($date,$calendar_id = false){

	$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
	
	if (!empty($calendars)):
		$tabbed = true;
		if (booked_user_role() == 'booked_booking_agent'):
			global $current_user;
			$calendars = booked_filter_agent_calendars($current_user,$calendars);	
		endif;
	else :
		$tabbed = false;
	endif;

	echo '<div class="booked-appt-list">';

		$time_format = get_option('time_format');
		$date_format = get_option('date_format');

		/*
		Grab all of the appointments for this day
		*/

		if ($tabbed):

			?><ul id="bookedAppointmentTabs" class="bookedClearFix">
				<?php // Show the Default calendar to admins, not booking agents
				if (booked_user_role() != 'booked_booking_agent'):
					$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
					$appointments_in_tab = booked_get_admin_appointments($date,$time_format,$date_format,'default',false,$calendars);
					$total_appointments = (!empty($appointments_in_tab) ? count($appointments_in_tab) : 0);
					?><li<?php if (!$calendar_id): ?> class="active"<?php endif; ?>><a href="#calendar-default"><?php _e('Default Calendar','booked'); ?><?php echo ($total_appointments ? '<span>'.$total_appointments.'</span>' : ''); ?></a></li><?php
				endif;
				foreach($calendars as $calendar):
					$appointments_in_tab = booked_get_admin_appointments($date,$time_format,$date_format,$calendar->term_id);
					$total_appointments = (!empty($appointments_in_tab) ? count($appointments_in_tab) : 0);
					?><li<?php if ($calendar_id == $calendar->term_id): ?> class="active"<?php endif; ?>><a href="#calendar-<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?><?php echo ($total_appointments ? '<span>'.$total_appointments.'</span>' : ''); ?></a></li><?php
				endforeach;
			?></ul><?php

			$tab_title = __('Appointments for','booked');
			if (booked_user_role() != 'booked_booking_agent'):
				?><div id="bookedCalendarAppointmentsTab-default" class="bookedAppointmentTab<?php if (!$calendar_id): ?> active<?php endif; ?>"><?php
					booked_admin_calendar_date_loop($date,$time_format,$date_format,false,$tab_title,$tabbed,$calendars);
				?></div><?php
			endif;
			foreach($calendars as $calendar):
				?><div id="bookedCalendarAppointmentsTab-<?php echo $calendar->term_id; ?>" class="bookedAppointmentTab<?php if ($calendar_id == $calendar->term_id): ?> active<?php endif; ?>"><?php
					$display_calendar_id = $calendar->term_id;
					$tab_title = $calendar->name.' '.__('for','booked');
					booked_admin_calendar_date_loop($date,$time_format,$date_format,$display_calendar_id,$tab_title,$tabbed,$calendars);
				?></div><?php
			endforeach;

		else :

			$tab_title = __('Appointments for','booked');
			booked_admin_calendar_date_loop($date,$time_format,$date_format,$calendar_id,$tab_title,false,$calendars);

		endif;

	echo '</div>';

}

function booked_get_admin_appointments($date,$time_format,$date_format,$calendar_id = false,$tabbed = false,$calendars = false){
	
	$year = date('Y',strtotime($date));
	$month = date('m',strtotime($date));
	$day = date('d',strtotime($date));

	$start_timestamp = strtotime($year.'-'.$month.'-'.$day.' 00:00:00');
	$end_timestamp = strtotime($year.'-'.$month.'-'.$day.' 23:59:59');

	$args = array(
		'post_type' => 'booked_appointments',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'meta_query' => array(
			array(
				'key'     => '_appointment_timestamp',
				'value'   => array( $start_timestamp, $end_timestamp ),
				'compare' => 'BETWEEN'
			)
		)
	);

	if ($calendar_id && $calendar_id != 'default'):
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'booked_custom_calendars',
				'field'    => 'id',
				'terms'    => $calendar_id,
			)
		);
	elseif (!$calendar_id && $tabbed && !empty($calendars) || $calendar_id = 'default'):

		foreach($calendars as $calendar_term){
            $not_in_calendar[] = $calendar_term->term_id;
        }

		$args['tax_query'] = array(
			array(
				'taxonomy' 			=> 'booked_custom_calendars',
				'field'    			=> 'id',
				'terms'            	=> $not_in_calendar,
				'include_children' 	=> false,
				'operator'         	=> 'NOT IN'
			)
		);

	endif;

	$appointments_array = array();

	$bookedAppointments = new WP_Query($args);
	if($bookedAppointments->have_posts()):
		while ($bookedAppointments->have_posts()):
			$bookedAppointments->the_post();
			global $post;
			$timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
			$timeslot = get_post_meta($post->ID, '_appointment_timeslot',true);
			$day = date('d',$timestamp);
			
			$guest_name = get_post_meta($post->ID, '_appointment_guest_name',true);
			$guest_email = get_post_meta($post->ID, '_appointment_guest_email',true);
			
			$appointments_array[$post->ID]['post_id'] = $post->ID;
			$appointments_array[$post->ID]['timestamp'] = $timestamp;
			$appointments_array[$post->ID]['timeslot'] = $timeslot;
			$appointments_array[$post->ID]['status'] = $post->post_status;
			
			if (!$guest_name):
				$user_id = get_post_meta($post->ID, '_appointment_user',true);
				$appointments_array[$post->ID]['user'] = $user_id;
			else:
				$appointments_array[$post->ID]['guest_name'] = $guest_name;
				$appointments_array[$post->ID]['guest_email'] = $guest_email;
			endif;
		
		endwhile;
		$appointments_array = apply_filters('booked_appointments_array', $appointments_array);
	endif;
	
	return $appointments_array;
	
}

function booked_admin_calendar_date_loop($date,$time_format,$date_format,$calendar_id = false,$tab_title,$tabbed = false,$calendars = false){

	$year = date('Y',strtotime($date));
	$month = date('m',strtotime($date));
	$day = date('d',strtotime($date));

	$date_display = date_i18n($date_format,strtotime($date));
	$day_name = date('D',strtotime($date));

	$appointments_array = booked_get_admin_appointments($date,$time_format,$date_format,$calendar_id,$tabbed,$calendars);

	/*
	Start the list
	*/

	echo '<h2>'.$tab_title.' '.$date_display.'</h2>';

	/*
	Get today's default timeslots
	*/

	if ($calendar_id):
		$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		if (!$booked_defaults):
			$booked_defaults = get_option('booked_defaults');
		endif;
	else :
		$booked_defaults = get_option('booked_defaults');
	endif;

	$formatted_date = date('Ymd',strtotime($date));
	$booked_defaults = booked_apply_custom_timeslots_filter($booked_defaults,$calendar_id);

	if (isset($booked_defaults[$formatted_date]) && !empty($booked_defaults[$formatted_date])):
		$todays_defaults = is_array($booked_defaults[$formatted_date]) ? $booked_defaults[$formatted_date] : json_decode($booked_defaults[$formatted_date],true);
	elseif (isset($booked_defaults[$formatted_date]) && empty($booked_defaults[$formatted_date])):
		$todays_defaults = false;
	elseif (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name])):
		$todays_defaults = $booked_defaults[$day_name];
	else :
		$todays_defaults = false;
	endif;
	
	

	/*
	There are timeslots available, let's loop through them
	*/

	if ($todays_defaults){

		ksort($todays_defaults);

		foreach($todays_defaults as $timeslot => $count):

			$appts_in_this_timeslot = array();

			/*
			Are there any appointments in this particular timeslot?
			If so, let's create an array of them.
			*/

			foreach($appointments_array as $post_id => $appointment):
				if ($appointment['timeslot'] == $timeslot):
					$appts_in_this_timeslot[] = $post_id;
				endif;
			endforeach;

			/*
			Calculate the number of spots available based on total minus the appointments booked
			*/

			$spots_available = $count - count($appts_in_this_timeslot);
			$spots_available = ($spots_available < 0 ? $spots_available = 0 : $spots_available = $spots_available);

			/*
			Display the timeslot
			*/

			$timeslot_parts = explode('-',$timeslot);

			$current_timestamp = current_time('timestamp');
			$this_timeslot_timestamp = strtotime($year.'-'.$month.'-'.$day.' '.$timeslot_parts[0]);

			if ($current_timestamp < $this_timeslot_timestamp){
				$available = true;
			} else {
				$available = false;
			}

			if ($timeslot_parts[0] == '0000' && $timeslot_parts[1] == '2400'):
				$timeslotText = __('All day','booked');
			else :
				$timeslotText = date_i18n($time_format,strtotime($timeslot_parts[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1]));
			endif;

			echo '<div class="timeslot bookedClearFix">';
				echo '<span class="timeslot-time"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;'.$timeslotText.'</span>';
				echo '<span class="timeslot-count">';

					echo '<span class="spots-available'.($spots_available == 0 ? ' empty' : '').'">'.$spots_available.' '._n('time slot','time slots',$spots_available,'booked').' '.__('available','booked').'</span>';

					/*
					Display the appointments set in this timeslot
					*/

					if (!empty($appts_in_this_timeslot)):

						$booked_appts = count($appts_in_this_timeslot);

						echo '<strong>'.$booked_appts.' '._n('Appointment','Appointments',$booked_appts,'booked').':</strong>';

						foreach($appts_in_this_timeslot as $appt_id):

							if (!isset($appointments_array[$appt_id]['guest_name'])):
								$user_info = get_userdata($appointments_array[$appt_id]['user']);
								if (isset($user_info->ID)):
									if ($user_info->user_firstname):
										$user_display = '<a href="#" class="user" data-user-id="'.$appointments_array[$appt_id]['user'].'">'.$user_info->user_firstname.($user_info->user_lastname ? ' '.$user_info->user_lastname : '').'</a>';
									elseif ($user_info->display_name):
										$user_display = '<a href="#" class="user" data-user-id="'.$appointments_array[$appt_id]['user'].'">'.$user_info->display_name.'</a>';
									else :
										$user_display = '<a href="#" class="user" data-user-id="'.$appointments_array[$appt_id]['user'].'">'.$user_info->user_login.'</a>';
									endif;
								else :
									$user_display = __('(this user no longer exists)','booked');
								endif;
							else :
								$user_display = '<a href="#" class="user" data-user-id="0">'.$appointments_array[$appt_id]['guest_name'].'</a>';
							endif;

							$status = ($appointments_array[$appt_id]['status'] !== 'publish' && $appointments_array[$appt_id]['status'] !== 'future' ? 'pending' : 'approved');
							echo '<span class="appt-block" data-appt-id="'.$appt_id.'">';
								
								echo $user_display;

								do_action('booked_admin_calendar_buttons_before', $calendar_id, $appt_id, $status);

								if ( apply_filters('booked_admin_show_calendar_buttons', true) ) {
									echo '<a href="#" class="delete"'.($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : '').'><i class="fa fa-remove"></i></a>'.($status == 'pending' ? '<button data-appt-id="'.$appt_id.'" class="approve button button-primary">'.__('Approve','booked').'</button>' : '');
								}

								do_action('booked_admin_calendar_buttons_after', $calendar_id, $appt_id, $status);

							echo '</span>';
							unset($appointments_array[$appt_id]);

						endforeach;

					endif;

				echo '</span>';
				echo '<span class="timeslot-people"><button'.(!$available ? ' disabled' : '').' data-timeslot="'.$timeslot.'" data-date="'.$date.'"'.($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : '').' class="new-appt button"'.(!$spots_available ? ' disabled' : '').'>'.__('New Appointment','booked').' ...</button></span>';
			echo '</div>';

		endforeach;

		/*
		Are there any additional appointments for this day that are not in the default timeslots?
		*/

		if (!empty($appointments_array)):

			echo '<span class="additional-timeslots">';
			echo '<br><p>'.__('There are additional appointments booked from previously available time slots:','booked').'</p>';
			foreach($appointments_array as $appointment):

				$user_info = get_userdata($appointment['user']);
				$status = ($appointment['status'] !== 'publish' ? 'pending' : 'approved');
				$timeslot = explode('-',$appointment['timeslot']);
				echo '<div class="timeslot bookedClearFix" data-appt-id="'.$appointment['post_id'].'">';
					echo '<span class="timeslot-time">'.date_i18n($time_format,strtotime($timeslot[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot[1])).'</span>';
					echo '<span class="timeslot-count count-wide">';
						echo '<a href="#" class="user" data-user-id="'.$appointments_array[$appointment['post_id']]['user'].'">'.$user_info->user_firstname.' '.$user_info->user_lastname.'</a>'.($status == 'pending' ? '<span class="pending-text"> ('.__('pending','booked').')</span>' : '');
						echo '<a href="#" class="delete"'.($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : '').'><i class="fa fa-remove"></i></a>'.($status == 'pending' ? '<button data-appt-id="'.$appointment['post_id'].'" class="approve button button-primary">'.__('Approve','booked').'</button>' : '');
					echo '</span>';
				echo '</div>';

			endforeach;
			echo '</span>';

		endif;

	/*
	There are no default timeslots, however there are appointments booked.
	*/

	} else if (!$todays_defaults && !empty($appointments_array)) {

		echo '<span class="additional-timeslots">';
		echo '<p>'.__('There are no appointment slots available for this day, however there are appointments booked from previously available time slots:','booked').'</p>';
		foreach($appointments_array as $appointment):

			$user_info = get_userdata($appointment['user']);
			$status = ($appointment['status'] !== 'publish' ? 'pending' : 'approved');
			$timeslot = explode('-',$appointment['timeslot']);
			echo '<div class="timeslot bookedClearFix" data-appt-id="'.$appointment['post_id'].'">';
				echo '<span class="timeslot-time">'.date_i18n($time_format,strtotime($timeslot[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot[1])).'</span>';
				echo '<span class="timeslot-count count-wide">';
					echo '<a href="#" class="user" data-user-id="'.$appointments_array[$appointment['post_id']]['user'].'">'.$user_info->user_firstname.' '.$user_info->user_lastname.'</a>'.($status == 'pending' ? '<span class="pending-text"> ('.__('pending','booked').')</span>' : '');
					echo '<a href="#" class="delete"'.($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : '').'><i class="fa fa-remove"></i></a>'.($status == 'pending' ? '<button data-appt-id="'.$appointment['post_id'].'" class="approve button button-primary">'.__('Approve','booked').'</button>' : '');
				echo '</span>';
			echo '</div>';

		endforeach;
		echo '</span>';

	/*
	There are no default timeslots and no appointments booked for this particular day.
	*/

	} else {
		echo '<p>'.__('There are no appointment time slots available for this day.','booked').' <a href="'.get_admin_url(null,'admin.php?page=booked-settings#defaults').'">'.__('Would you like to add some?','booked').'</a></p>';
	}
}

function booked_admin_calendar_date_square($date,$calendar_id = false){

	//booked_admin_set_timezone();

	$local_time = current_time('timestamp');

	$year = date('Y',strtotime($date));
	$month = date('m',strtotime($date));
	$this_day = date('j',strtotime($date)); // Defaults to current day
	$last_day = date('t',strtotime($year.'-'.$month));

	$monthShown = date($year.'-'.$month.'-01');
	$currentMonth = date('Y-m-01',$local_time);

	$first_day_of_week = (get_site_option('start_of_week') == 0 ? 7 : 1); 	// 1 = Monday, 7 = Sunday, Get from WordPress Settings

	$start_timestamp = strtotime($year.'-'.$month.'-01 00:00:00');
	$end_timestamp = strtotime($year.'-'.$month.'-'.$last_day.' 23:59:59');

	if ($calendar_id):
		$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		if (!$booked_defaults):
			$booked_defaults = get_option('booked_defaults');
		endif;
	else :
		$booked_defaults = get_option('booked_defaults');
	endif;

	$args = array(
		'post_type' => 'booked_appointments',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => '_appointment_timestamp',
				'value'   => array( $start_timestamp, $end_timestamp ),
				'compare' => 'BETWEEN',
			)
		)
	);

	if ($calendar_id):
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'booked_custom_calendars',
				'field'    => 'id',
				'terms'    => $calendar_id,
			)
		);
	endif;

	$bookedAppointments = new WP_Query($args);
	if($bookedAppointments->have_posts()):
		while ($bookedAppointments->have_posts()):
			$bookedAppointments->the_post();
			global $post;
			$timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
			$day = date('j',$timestamp);
			$appointments_array[$day][$post->ID]['timestamp'] = $timestamp;
			$appointments_array[$day][$post->ID]['status'] = $post->post_status;
		endwhile;
		$appointments_array = apply_filters('booked_appointments_array', $appointments_array);
	endif;

	$classes[] = 'active';

	$today_date = date('Y').'-'.date('m').'-'.date('j');
	if ($today_date == $_POST['date']):
		$classes[] = 'today';
	endif;

	$day_name = date('D',strtotime($date));
	$full_count = (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name]) ? $booked_defaults[$day_name] : false);
	$total_full_count = 0;
	if ($full_count):
		foreach($full_count as $full_counter){
			$total_full_count = $total_full_count + $full_counter;
		}
	endif;

	if (isset($appointments_array[$this_day]) && !empty($appointments_array[$this_day])):
		$appointments_count = count($appointments_array[$this_day]);
		if ($appointments_count > 0 && $appointments_count < $total_full_count): $classes[] = 'partial';
		elseif ($appointments_count >= $total_full_count): $classes[] = 'booked'; endif;
	endif;

	echo '<td data-date="'.$date.'" class="'.implode(' ',$classes).'">';
	echo '<span class="date"><span class="number">'. $this_day . '</span></span>';
	if (isset($appointments_count) && $appointments_count > 0): echo '<span class="count">'.$appointments_count.' '._n('appointment','appointments',$appointments_count,'booked').'</span>'; endif;
	echo '</td>';

}

function booked_render_custom_fields($calendar = false){
	
	?><form id="booked-cf-sortables-form">
		<ul id="booked-cf-sortables"><?php
	
			if (!$calendar):
				$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true);
			else:
				$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields_'.$calendar)),true);
			endif;
	
			if (!empty($custom_fields)):
	
				$look_for_subs = false;
	
				foreach($custom_fields as $field):
	
					if ($look_for_subs):
	
						$field_type = explode('---',$field['name']);
						$field_type = $field_type[0];
	
						if ($field_type == 'single-checkbox'):
	
							?><li class="ui-state-default"><i class="sub-handle fa fa-bars"></i>
								<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>" placeholder="Enter a label for this checkbox..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li><?php
	
						elseif ($field_type == 'single-radio-button'):
	
							?><li class="ui-state-default"><i class="sub-handle fa fa-bars"></i>
								<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>" placeholder="Enter a label for this radio button..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li><?php
	
						elseif ($field_type == 'single-drop-down'):
	
							?><li class="ui-state-default"><i class="sub-handle fa fa-bars"></i>
								<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>" placeholder="Enter a label for this option..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li><?php
	
						elseif ($field_type == 'required'):
	
							// do nothing
	
						else :
	
							if ($look_for_subs == 'checkboxes'):
	
								?></ul>
								<button class="cfButton button" data-type="single-checkbox"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Checkbox','booked'); ?></button>
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li><?php
	
							elseif ($look_for_subs == 'radio-buttons'):
	
								?></ul>
								<button class="cfButton button" data-type="single-radio-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Radio Button','booked'); ?></button>
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li><?php
	
							elseif ($look_for_subs == 'dropdowns'):
	
								?></ul>
								<button class="cfButton button" data-type="single-option"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Option','booked'); ?></button>
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li><?php
	
							endif;
	
							$reset_subs = apply_filters(
								'booked_custom_fields_add_template_subs',
								$field_type,
								$field['name'],
								$field['value'],
								$look_for_subs
							);
	
							if ( $reset_subs ) {
								$look_for_subs = false;
							}
	
						endif;
	
					endif;
	
					$field_parts = explode('---',$field['name']);
					$field_type = $field_parts[0];
					$end_of_string = explode('___',$field_parts[1]);
					$numbers_only = $end_of_string[0];
					$is_required = (isset($end_of_string[1]) ? true : false);
	
					switch($field_type):
	
						case 'single-line-text-label' :
	
							?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Single Line Text','booked'); ?></small>
								<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>" placeholder="Enter a label for this field..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li><?php
	
						break;
	
						case 'paragraph-text-label' :
	
							?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Paragraph Text','booked'); ?></small>
								<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>" placeholder="Enter a label for this field..." />
								<span class="cf-delete"><i class="fa fa-close"></i></span>
							</li><?php
	
						break;
	
						case 'checkboxes-label' :
	
							?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Checkboxes','booked'); ?></small>
								<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>" placeholder="Enter a label for this checkbox group..." />
								<ul id="booked-cf-checkboxes"><?php
	
							$look_for_subs = 'checkboxes';
	
						break;
	
						case 'radio-buttons-label' :
	
							?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Radio Buttons','booked'); ?></small>
								<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>" placeholder="Enter a label for this radio button group..." />
								<ul id="booked-cf-radio-buttons"><?php
	
							$look_for_subs = 'radio-buttons';
	
						break;
	
						case 'drop-down-label' :
	
							?><li class="ui-state-default"><i class="main-handle fa fa-bars"></i>
								<small><?php _e('Drop Down','booked'); ?></small>
								<p><input class="cf-required-checkbox"<?php if ($is_required): echo ' checked="checked"'; endif; ?> type="checkbox" name="required---<?php echo $numbers_only; ?>" id="required---<?php echo $numbers_only; ?>"> <label for="required---<?php echo $numbers_only; ?>"><?php _e('Required Field','booked'); ?></label></p>
								<input type="text" name="<?php echo $field['name']; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>" placeholder="Enter a label for this drop-down group..." />
								<ul id="booked-cf-drop-down"><?php
	
							$look_for_subs = 'dropdowns';
	
						break;
	
						default:
							$look_for_subs_action = apply_filters(
								'booked_custom_fields_add_template_main',
								false, // default value to return when there is no addon plugin to hook on it
								$field_type,
								$field['name'],
								$field['value'],
								$is_required,
								$look_for_subs,
								$numbers_only
							);
							$look_for_subs = $look_for_subs_action ? $look_for_subs_action : $look_for_subs;
						break;
	
					endswitch;
	
				endforeach;
	
	
				if ($look_for_subs):
	
					do_action('booked_custom_fields_add_template_subs_end', $field_type, $look_for_subs);
	
					if ($look_for_subs == 'checkboxes'):
	
						?></ul>
						<button class="cfButton button" data-type="single-checkbox"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Checkbox','booked'); ?></button>
						<span class="cf-delete"><i class="fa fa-close"></i></span>
					</li><?php
	
					elseif ($look_for_subs == 'radio-buttons'):
	
						?></ul>
						<button class="cfButton button" data-type="single-radio-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Radio Button','booked'); ?></button>
						<span class="cf-delete"><i class="fa fa-close"></i></span>
					</li><?php
	
					elseif ($look_for_subs == 'dropdowns'):
	
						?></ul>
						<button class="cfButton button" data-type="single-drop-down"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Option','booked'); ?></button>
						<span class="cf-delete"><i class="fa fa-close"></i></span>
					</li><?php
	
					endif;
	
				endif;
	
			endif;
		?></ul>
	</form>
	
	<button class="cfButton button" data-type="single-line-text-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Single Line Text','booked'); ?></button>&nbsp;
	<button class="cfButton button" data-type="paragraph-text-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Paragraph Text','booked'); ?></button>&nbsp;
	<button class="cfButton button" data-type="checkboxes-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Checkboxes','booked'); ?></button>&nbsp;
	<button class="cfButton button" data-type="radio-buttons-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Radio Buttons','booked'); ?></button>&nbsp;
	<button class="cfButton button" data-type="drop-down-label"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Drop Down','booked'); ?></button>
	<?php do_action('booked_custom_fields_add_buttons');
}