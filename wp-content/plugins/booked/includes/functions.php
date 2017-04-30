<?php

// Booked Front-End Calendar
function booked_fe_calendar($year = false,$month = false,$calendar_id = false){

	do_action('booked_fe_calendar_before');

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
			$timeslot = get_post_meta($post->ID, '_appointment_timeslot',true);
			$day = date('j',$timestamp);
			$appointments_array[$day][$post->ID]['timeslot'] = $timeslot;
			$appointments_array[$day][$post->ID]['timestamp'] = $timestamp;
			$appointments_array[$day][$post->ID]['status'] = $post->post_status;
		endwhile;

		$appointments_array = apply_filters('booked_appointments_array', $appointments_array);
	endif;

	$hide_weekends = get_option('booked_hide_weekends',false);

	// Appointments Array
	// [DAY] => [POST_ID] => [TIMESTAMP/STATUS]

	?><table class="booked-calendar"<?php echo ($calendar_id ? ' data-calendar-id="'.$calendar_id.'"' : ''); ?>>
		<thead>
			<tr>
				<th colspan="<?php if (!$hide_weekends): ?>7<?php else: ?>5<?php endif; ?>">
					<?php if ($monthShown != $currentMonth): ?><a href="#" data-goto="<?php echo date('Y-m-01', strtotime("-1 month", strtotime($year.'-'.$month.'-01'))); ?>" class="page-left"><i class="fa fa-arrow-left"></i></a><?php endif; ?>
					<span class="calendarSavingState">
						<i class="fa fa-refresh fa-spin"></i>
					</span>
					<span class="monthName">
						<?php echo date_i18n("F Y", strtotime($year.'-'.$month.'-01')); ?>
						<?php if ($monthShown != $currentMonth): ?>
							<a href="#" class="backToMonth" data-goto="<?php echo $currentMonth; ?>"><?php _e('Back to','booked'); ?> <?php echo date_i18n('F',strtotime($currentMonth)); ?></a>
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
			$days = date("t",strtotime($year.'-'.$month.'-01'));	 	// Days in current month
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
			$buffer = get_option('booked_appointment_buffer',0);

			if($start > 5){ $rows = 6; } else { $rows = 5; }

			for($i = 1; $i <= $rows; $i++){
				echo '<tr class="week">';
				for($x = 1; $x <= 7; $x++){

					$classes = array();

					$appointments_count = 0;

					$check_month = $month;

					if(($counter - $start) < 0){

						$date = (($lastmonth - $laststart) + $counter);
						$classes[] = 'blur';
						$check_month = $month - 1;

					} else if(($counter - $start) >= $days){

						$date = ($nextMonthCounter);
						$nextMonthCounter++;
						$classes[] = 'blur';
						$check_month = $month + 1;

					} else {

						$date = ($counter - $start + 1);
						if($today == $counter - $start + 1){
							if ($today_date == $year.'-'.$month.'-'.$date):
								$classes[] = 'today';
							endif;
						}

						$day_name = date('D',strtotime($year.'-'.$month.'-'.$date));

						$formatted_date = date('Ymd',strtotime($year.'-'.$month.'-'.$date));
						if (isset($booked_defaults[$formatted_date]) && !empty($booked_defaults[$formatted_date])):
							$full_count = (is_array($booked_defaults[$formatted_date]) ? $booked_defaults[$formatted_date] : json_decode($booked_defaults[$formatted_date],true));
						elseif (isset($booked_defaults[$formatted_date]) && empty($booked_defaults[$formatted_date])):
							$full_count = false;
						elseif (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name])):
							$full_count = $booked_defaults[$day_name];
						else :
							$full_count = false;
						endif;

						$total_full_count = 0;
						if ($full_count):
							foreach($full_count as $full_counter){
								$total_full_count = $total_full_count + $full_counter;
							}
						endif;

						if (isset($appointments_array[$date]) && !empty($appointments_array[$date])):
							$appointments_count = count($appointments_array[$date]);
							if ($appointments_count >= $total_full_count): $classes[] = 'booked'; endif;
						endif;

						if ($buffer):
							$current_timestamp = $local_time;
							$buffered_timestamp = strtotime('+'.$buffer.' hours',$current_timestamp);
							$date_to_compare = $buffered_timestamp;
							$currentTime = date_i18n('H:i:s',$buffered_timestamp);
						else:
							$date_to_compare = $local_time;
							$currentTime = date_i18n('H:i:s');
						endif;

						if ( strtotime($year.'-'.$month.'-'.$date.' '.$currentTime) < $date_to_compare || $total_full_count < 1) : $classes[] = 'prev-date'; endif;

					}

					$appointments_left = 0;

					/* Check to see if the last appointment time slot is passed */
					if (isset($day_name) && !in_array('prev-date',$classes)):
						if ($calendar_id):
							$booked_defaults = get_option('booked_defaults_'.$calendar_id);
							if (!$booked_defaults):
								$booked_defaults = get_option('booked_defaults');
							endif;
						else :
							$booked_defaults = get_option('booked_defaults');
						endif;

						$formatted_date = date('Ymd',strtotime($year.'-'.$month.'-'.$date));
						$booked_defaults = booked_apply_custom_timeslots_filter($booked_defaults,$calendar_id);

						if (isset($booked_defaults[$formatted_date]) && !empty($booked_defaults[$formatted_date])):
							$todays_defaults = (is_array($booked_defaults[$formatted_date]) ? $booked_defaults[$formatted_date] : json_decode($booked_defaults[$formatted_date],true));
						elseif (isset($booked_defaults[$formatted_date]) && empty($booked_defaults[$formatted_date])):
							$todays_defaults = false;
						elseif (isset($booked_defaults[$day_name]) && !empty($booked_defaults[$day_name])):
							$todays_defaults = $booked_defaults[$day_name];
						else :
							$todays_defaults = false;
						endif;

						if (!empty($todays_defaults)):

							if ($buffer):
								$current_timestamp = $local_time;
								$buffered_timestamp = strtotime('+'.$buffer.' hours',$current_timestamp);
								$date_to_compare = date('YmdHis',$buffered_timestamp);
							else:
								$date_to_compare = date('YmdHis',$local_time);
							endif;

							$time_right_now = date('YmdHis',$local_time);
							$this_date = $year.'-'.$month.'-'.$date;

							$appts_in_this_timeslot = 0; $total_appointments_available = 0;
							foreach($todays_defaults as $timeslot => $count):
								$this_timeslot = explode('-',$timeslot);
								$start_of_timeslot = $this_timeslot[0];
								$full_date_start_of_timeslot = date('YmdHis',strtotime($this_date.' '.$start_of_timeslot));
								if ($date_to_compare < $full_date_start_of_timeslot):
									$total_appointments_available = $total_appointments_available + $count;
									if (isset($appointments_array[$date]) && !empty($appointments_array[$date])):
										foreach($appointments_array[$date] as $post_id => $appointment):
											if ($appointment['timeslot'] == $timeslot):
												$appts_in_this_timeslot++;
											endif;
										endforeach;
									endif;
									$appointments_left = $total_appointments_available - $appts_in_this_timeslot;
								endif;
							endforeach;

							if (!$appointments_left):
								$classes[] = 'prev-date';
							endif;

						endif;
					endif;
					/* END - Check to see if the last appointment time slot is passed */

					$day_of_week = date('N',strtotime($year.'-'.$check_month.'-'.$date));

					if ($hide_weekends && $day_of_week == 6 || $hide_weekends && $day_of_week == 7):

						$html = '';

					else:

						$html = '<td data-date="'.$year.'-'.$check_month.'-'.$date.'" class="'.implode(' ',$classes).'">';
						$html .= '<span class="date'.($appointments_left && !in_array('prev-date',$classes) && !in_array('blur',$classes) ? ' tooltipster" title="'.sprintf( _n('%d time slot','%d time slots',$appointments_left,'booked'), $appointments_left) : '').'"><span class="number">'. $date .'</span></span>';
						$html .= '</td>';

						$combined_date = $year.'-'.$month.'-'.$date;
						echo apply_filters('booked_fe_single_date',$html,$combined_date,$classes);

					endif;

					$counter++;
					$class = '';
				}
				echo '</tr>';
			} ?>
		</tbody>
	</table><?php

	do_action('booked_fe_calendar_after');

}

function booked_fe_calendar_date_content($date,$calendar_id = false){

	do_action('booked_fe_calendar_date_before');

	echo '<div class="booked-appt-list">';

		/*
		Set some variables
		*/

		$local_time = current_time('timestamp');

		$year = date('Y',strtotime($date));
		$month = date('m',strtotime($date));
		$day = date('d',strtotime($date));

		$start_timestamp = strtotime($year.'-'.$month.'-'.$day.' 00:00:00');
		$end_timestamp = strtotime($year.'-'.$month.'-'.$day.' 23:59:59');

		$time_format = get_option('time_format');
		$date_display = date_i18n('F jS, Y',strtotime($date));
		$day_name = date('D',strtotime($date));

		/*
		Grab all of the appointments for this day
		*/

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

		if ($calendar_id):
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'booked_custom_calendars',
					'field'    => 'id',
					'terms'    => $calendar_id,
				)
			);
		endif;

		$appointments_array = array();

		$bookedAppointments = new WP_Query( apply_filters('booked_fe_date_content_query',$args) );
		if($bookedAppointments->have_posts()):
			while ($bookedAppointments->have_posts()):
				$bookedAppointments->the_post();
				global $post;
				$timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
				$timeslot = get_post_meta($post->ID, '_appointment_timeslot',true);
				$user_id = get_post_meta($post->ID, '_appointment_user',true);
				$day = date('d',$timestamp);
				$appointments_array[$post->ID]['post_id'] = $post->ID;
				$appointments_array[$post->ID]['timestamp'] = $timestamp;
				$appointments_array[$post->ID]['timeslot'] = $timeslot;
				$appointments_array[$post->ID]['status'] = $post->post_status;
				$appointments_array[$post->ID]['user'] = $user_id;
			endwhile;
			$appointments_array = apply_filters('booked_appointments_array', $appointments_array);
		endif;

		/*
		Start the list
		*/

		echo '<h2>'.__('Available Appointments','booked').':</h2>';

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
			$todays_defaults = (is_array($booked_defaults[$formatted_date]) ? $booked_defaults[$formatted_date] : json_decode($booked_defaults[$formatted_date],true));
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

			$temp_count = 0;

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
				$spots_available = ($spots_available < 0 ? 0 : $spots_available);

				/*
				Display the timeslot
				*/

				$timeslot_parts = explode('-',$timeslot);

				$buffer = get_option('booked_appointment_buffer',0);

				if ($buffer):
					$current_timestamp = $local_time;
					$buffered_timestamp = strtotime('+'.$buffer.' hours',$current_timestamp);
					$current_timestamp = $buffered_timestamp;
				else:
					$current_timestamp = $local_time;
				endif;

				$this_timeslot_timestamp = strtotime($year.'-'.$month.'-'.$day.' '.$timeslot_parts[0]);

				if ($current_timestamp < $this_timeslot_timestamp){
					$available = true;
				} else {
					$available = false;
				}

				$hide_unavailable_timeslots = get_option('booked_hide_unavailable_timeslots',false);

				if ($spots_available && $available || !$hide_unavailable_timeslots):

					$temp_count++;

					if ($timeslot_parts[0] == '0000' && $timeslot_parts[1] == '2400'):
						$timeslotText = __('All day','booked');
					else :
						$timeslotText = date_i18n($time_format,strtotime($timeslot_parts[0])) . (!get_option('booked_hide_end_times') ? ' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1])) : '');
					endif;

					if ($hide_unavailable_timeslots && !$available):
						$html = '';
					else:
						$button_text = (!$spots_available || !$available ? __('Unavailable','booked') : __('Book Appointment','booked'));
						$html = '<div class="timeslot bookedClearFix">';
							$html .= '<span class="timeslot-time"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;' . $timeslotText . '</span>';
							$html .= '<span class="timeslot-count">';
								$html .= '<span class="spots-available'.($spots_available == 0 ? ' empty' : '').'">'.$spots_available.' '._n('time slot','time slots',$spots_available,'booked').' '.__('available','booked').'</span>';
							$html .= '</span>';
							$html .= '<span class="timeslot-people"><button data-timeslot="'.$timeslot.'" data-date="'.$date.'" class="new-appt button"'.(!$spots_available || !$available ? ' disabled' : '').'><span class="button-timeslot">'.$timeslotText.'</span>'.apply_filters('booked_button_book_appointment', '<span class="button-text">'.$button_text.'</span></button></span>');
						$html .= '</div>';
					endif;

					echo apply_filters('booked_fe_calendar_date_appointments',$html,$time_format,$timeslot_parts,$spots_available,$available,$timeslot,$date);

				endif;

			endforeach;

			if (!$temp_count):

				echo '<p>'.__('There are no appointment time slots available for this day.','booked').'</p>';

			endif;

		/*
		There are no default timeslots and no appointments booked for this particular day.
		*/

		} else {
			echo '<p>'.__('There are no appointment time slots available for this day.','booked').'</p>';
		}

	echo '</div>';

	do_action('booked_fe_calendar_date_after');

}


function booked_custom_fields($calendar_id = false){

	if ($calendar_id):
		$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields_'.$calendar_id)),true);
		if (empty($custom_fields)): $custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true); endif;
	else:
		$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true);
	endif;

	if (!empty($custom_fields)):

		echo '<div class="cf-block">';

		$look_for_subs = false;
		$temp_count = 0;

		foreach($custom_fields as $field):

			$temp_count++;

			$field_parts = explode('---',$field['name']);
			$field_type = $field_parts[0];
			$end_of_string = explode('___',$field_parts[1]);
			$numbers_only = $end_of_string[0];
			$is_required = (isset($end_of_string[1]) ? true : false);

			if ($look_for_subs):

				if ($field_type == 'single-checkbox'):

					?><span class="checkbox-radio-block"><input type="checkbox" name="<?php echo $field['name']; ?>[]" id="<?php echo $field['name'].'-'.$temp_count; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>"> <label for="<?php echo $field['name'].'-'.$temp_count; ?>"><?php echo $field['value']; ?></label></span><?php

				elseif ($field_type == 'single-radio-button'):

					?><span class="checkbox-radio-block"><input type="radio" name="<?php echo $field['name']; ?>[]" id="<?php echo $field['name'].'-'.$temp_count; ?>" value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>"> <label for="<?php echo $field['name'].'-'.$temp_count; ?>"><?php echo $field['value']; ?></label></span><?php

				elseif ($field_type == 'single-drop-down'):

					?><option value="<?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?>"><?php echo htmlentities($field['value'], ENT_QUOTES | ENT_IGNORE, "UTF-8"); ?></option><?php

				else :

					if ($look_for_subs == 'checkboxes'):

						?></div><?php

					elseif ($look_for_subs == 'radio-buttons'):

						?></div><?php

					elseif ($look_for_subs == 'dropdowns'):

						?></select></div><?php

					endif;

					$reset_subs = apply_filters(
						'booked_custom_fields_add_template_subs',
						$field_type,
						$field['name'],
						$field['value'],
						$is_required,
						$look_for_subs
					);

					if ( $reset_subs ) {
						$look_for_subs = false;
					}

				endif;

			endif;

			switch($field_type):

				case 'single-line-text-label' :

					?><div class="field">
						<label class="field-label"><?php echo $field['value']; ?><?php if ($is_required): ?><i class="required-asterisk fa fa-asterisk"></i><?php endif; ?></label>
						<input<?php if ($is_required): echo ' required="required"'; endif; ?> type="text" name="<?php echo $field['name']; ?>" value="" class="large textfield" />
					</div><?php

				break;

				case 'paragraph-text-label' :

					?><div class="field">
						<label class="field-label"><?php echo $field['value']; ?><?php if ($is_required): ?><i class="required-asterisk fa fa-asterisk"></i><?php endif; ?></label>
						<textarea<?php if ($is_required): echo ' required="required"'; endif; ?> name="<?php echo $field['name']; ?>"></textarea>
					</div><?php

				break;

				case 'checkboxes-label' :

					?><div class="field">
						<label class="field-label"><?php echo $field['value']; ?><?php if ($is_required): ?><i class="required-asterisk fa fa-asterisk"></i><?php endif; ?></label>
						<input<?php if ($is_required): echo ' required="required"'; endif; ?> type="hidden" name="<?php echo $field['name']; ?>" /><?php
					$look_for_subs = 'checkboxes';

				break;

				case 'radio-buttons-label' :

					?><div class="field">
						<label class="field-label"><?php echo $field['value']; ?><?php if ($is_required): ?><i class="required-asterisk fa fa-asterisk"></i><?php endif; ?></label>
						<input<?php if ($is_required): echo ' required="required"'; endif; ?> type="hidden" name="<?php echo $field['name']; ?>" /><?php

					$look_for_subs = 'radio-buttons';

				break;

				case 'drop-down-label' :

					?><div class="field">
						<label class="field-label"><?php echo $field['value']; ?><?php if ($is_required): ?><i class="required-asterisk fa fa-asterisk"></i><?php endif; ?></label>
						<input type="hidden" name="<?php echo $field['name']; ?>" />
						<select<?php if ($is_required): echo ' required="required"'; endif; ?> name="<?php echo $field['name']; ?>"><option value=""><?php _e('Choose...','booked'); ?></option><?php

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
						$look_for_subs
					);

					$look_for_subs = $look_for_subs_action ? $look_for_subs_action : $look_for_subs;
				break;

			endswitch;

		endforeach;

		if ($look_for_subs):

			do_action('booked_custom_fields_add_template_subs_end', $field_type, $look_for_subs);

			if ($look_for_subs == 'checkboxes'):

				?></div><?php

			elseif ($look_for_subs == 'radio-buttons'):

				?></div><?php

			elseif ($look_for_subs == 'dropdowns'):

				?></select></div><?php

			endif;

		endif;

		echo '</div>';

	endif;

}


add_action( 'wp_login_failed', 'booked_fe_login_fail' );  // hook failed login
function booked_fe_login_fail( $username ) {
	if (isset($_SERVER['HTTP_REFERER'])):
		$referrer = $_SERVER['HTTP_REFERER'];
		$referrer = explode('?',$referrer);
		$referrer = $referrer[0];
		if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
		  wp_redirect( $referrer . '?loginfailed' );
		  exit;
		}
	endif;
}

function booked_user_appointments($user_id,$only_count = false,$time_format = false,$date_format = false,$historic = false){

	if (!$date_format || !$time_format){
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');
	}

	$order = $historic ? 'DESC' : 'ASC';

	$args = array(
		'post_type' => 'booked_appointments',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'author' => $user_id,
		'meta_key' => '_appointment_timestamp',
		'orderby' => 'meta_value_num',
		'order' => $order
	);

	$appointments_array = array();

	$bookedAppointments = new WP_Query($args);

	if($bookedAppointments->have_posts()):
		while ($bookedAppointments->have_posts()):

			$bookedAppointments->the_post();
			global $post;
			$appt_date_value = date('Y-m-d',get_post_meta($post->ID, '_appointment_timestamp',true));
			$appt_timeslot = get_post_meta($post->ID, '_appointment_timeslot',true);
			$appt_timeslots = explode('-',$appt_timeslot);
			$appt_time_start = date('H:i:s',strtotime($appt_timeslots[0]));

			$appt_timestamp = strtotime($appt_date_value.' '.$appt_time_start);
			$current_timestamp = current_time('timestamp');

			$day = date('d',$appt_timestamp);
			$category = get_the_category();
			$calendar_id = wp_get_post_terms( $post->ID, 'booked_custom_calendars' );

			if (!$historic && $appt_timestamp >= $current_timestamp || $historic && $appt_timestamp < $current_timestamp){
				$appointments_array[$post->ID]['post_id'] = $post->ID;
				$appointments_array[$post->ID]['timestamp'] = $appt_timestamp;
				$appointments_array[$post->ID]['timeslot'] = $appt_timeslot;
				$appointments_array[$post->ID]['calendar_id'] = $calendar_id;
				$appointments_array[$post->ID]['status'] = $post->post_status;
			}

		endwhile;
		$appointments_array = apply_filters('booked_appointments_array', $appointments_array);
	endif;

	wp_reset_postdata();
	if ($only_count):
		return count($appointments_array);
	else :
		return $appointments_array;
	endif;

}

function booked_profile_update_submit(){

	if (is_user_logged_in()):

		global $error,$current_user,$post;

		//get_currentuserinfo();
		$current_user = wp_get_current_user();

		$error = array();

		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

		    /* Update user password. */
		    if (isset($_POST['pass1']) && isset($_POST['pass2']) && $_POST['pass1'] && $_POST['pass2'] ) {
		        if ( $_POST['pass1'] == $_POST['pass2'] )
		            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
		        else
		            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
		    }

		    /* Update user information. */
		    if ( isset( $_POST['url'] ) )
		    	wp_update_user( array( 'ID' => $current_user->ID, 'user_url' => esc_url( $_POST['url'] ) ) );
		    if ( isset( $_POST['email'] ) ){

		    	$email_exists = email_exists(esc_attr( $_POST['email'] ));

		        if (!is_email(esc_attr( $_POST['email'] )))
		            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
		        elseif( $email_exists && $email_exists != $current_user->ID )
		            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
		        else{
		            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
		        }
		    }

		    if ( isset( $_POST['nickname'] ) ):
		        update_user_meta( $current_user->ID, 'nickname', esc_attr( $_POST['nickname'] ) );
		        wp_update_user( array ('ID' => $current_user->ID, 'display_name' => esc_attr( $_POST['nickname'] )));
		    endif;

		    if ( isset($_POST['description']) )
		        update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );

	        // Avatar Upload
	        $avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : false;
			if (isset($avatar,$_POST['avatar_nonce']) && $avatar && wp_verify_nonce( $_POST['avatar_nonce'], 'avatar_upload' )) {
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );

				$attachment_id = media_handle_upload( 'avatar', 0 );

				if ( is_wp_error( $attachment_id ) ) {
					$error[] = __('Error uploading avatar.','booked');
				} else {
					update_user_meta( $current_user->ID, 'avatar', $attachment_id );
				}
			} else {
				$error[] = __('Avatar uploader security check failed.','booked');
			}
			// END AVATAR

		    /* Redirect so the page will show updated info.*/
		    if ( count($error) == 0 ) {
		        //action hook for plugins and extra fields saving
		        do_action('edit_user_profile_update', $current_user->ID);
				wp_redirect( get_permalink($post->ID) );
		        exit;
		    }
		}

	endif;

}

add_action('get_header','booked_profile_update_submit');

function booked_profile_content_appointments(){

	?>
	<div id="data-ajax-url"><?php echo home_url(); ?>/</div>

	<?php if (isset($_GET['appt_requested']) && isset($_GET['new_account'])){

		echo '<p class="booked-form-notice">'.__('Your appointment has been requested! We have also set up an account for you. Your login information has been sent via email. When logged in, you can view your upcoming appointments below. Be sure to change your password to something more memorable by using the Edit Profile tab above.','booked').'</p>';

	} else if (isset($_GET['appt_requested'])){

		$appointment_default_status = get_option('booked_new_appointment_default','draft');

		if ($appointment_default_status == 'draft'):
			echo '<p class="booked-form-notice">'.__('Your appointment has been requested! It will be updated below if approved.','booked').'</p>';
		else :
			echo '<p class="booked-form-notice">'.__('Your appointment has been added to our calendar!','booked').'</p>';
		endif;

	} ?>

	<?php echo do_shortcode('[booked-appointments remove_wrapper=1]');

}

function booked_profile_content_history(){

	echo do_shortcode('[booked-appointments remove_wrapper=1 historic=1]');

}

function booked_profile_content_edit(){

	global $current_user;

	echo '<h4><i class="fa fa-edit"></i>&nbsp;&nbsp;'.__('Edit Profile','booked').'</h4>'; ?>

    <form method="post" enctype="multipart/form-data" id="booked-page-form" action="<?php the_permalink(); ?>">

	    <?php

	    $disable_avatar = get_option('booked_disable_avatar',false);
		$disable_website = get_option('booked_disable_website',false);
		$disable_bio = get_option('booked_disable_bio',false);

		?>


		<?php if (!$disable_avatar): ?>
	    	<div class="bookedClearFix">
	            <p class="form-avatar">
	                <label for="avatar"><?php _e('Update Avatar', 'booked'); ?><?php if (BOOKED_DEMO_MODE): ?> <span class="not-bold"><?php _e('(disabled in demo)', 'cooked'); ?></span><?php endif; ?></label><br>
	                <span class="booked-upload-wrap"><span><?php _e('Choose image ...','booked'); ?></span><input<?php if (BOOKED_DEMO_MODE): ?> disabled<?php endif; ?> class="field" name="avatar" type="file" id="avatar" value="" /></span>
	                <?php wp_nonce_field( 'avatar_upload', 'avatar_nonce' ); ?>
	                <span class="hint-p"><?php _e('Recommended size: 100px by 100px or larger', 'booked'); ?></span>
	            </p><!-- .form-nickname -->
	    	</div>
    	<?php endif; ?>

        <div class="bookedClearFix">
            <p class="form-nickname">
                <label for="nickname"><?php _e('Display Name', 'booked'); ?><?php if (BOOKED_DEMO_MODE): ?> <span class="not-bold"><?php _e('(disabled in demo)', 'cooked'); ?></span><?php endif; ?></label>
                <input<?php if (BOOKED_DEMO_MODE): ?> disabled<?php endif; ?> class="text-input" name="nickname" type="text" id="nickname" value="<?php the_author_meta( 'nickname', $current_user->ID ); ?>" />
            </p><!-- .form-nickname -->
            <p class="form-email">
                <label for="email"><?php _e('E-mail *', 'booked'); ?><?php if (BOOKED_DEMO_MODE): ?> <span class="not-bold"><?php _e('(disabled in demo)', 'cooked'); ?></span><?php endif; ?></label>
                <input<?php if (BOOKED_DEMO_MODE): ?> disabled<?php endif; ?> class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
            </p><!-- .form-email -->
            <?php if (!$disable_website): ?>
	            <p class="form-url">
	                <label for="url"><?php _e('Website', 'booked'); ?><?php if (BOOKED_DEMO_MODE): ?> <span class="not-bold"><?php _e('(disabled in demo)', 'cooked'); ?></span><?php endif; ?></label>
	                <input<?php if (BOOKED_DEMO_MODE): ?> disabled<?php endif; ?> class="text-input" name="url" type="text" id="url" value="<?php the_author_meta( 'user_url', $current_user->ID ); ?>" />
	            </p><!-- .form-url -->
			<?php endif; ?>
        </div>
        <div class="bookedClearFix">
            <p class="form-password">
                <label for="pass1"><?php _e('Change Password', 'booked'); ?><?php if (BOOKED_DEMO_MODE): ?> <span class="not-bold"><?php _e('(disabled in demo)', 'cooked'); ?></span><?php endif; ?></label>
                <input<?php if (BOOKED_DEMO_MODE): ?> disabled<?php endif; ?> class="text-input" name="pass1" type="password" id="pass1" />
            </p><!-- .form-password -->
            <p class="form-password last">
                <label for="pass2"><?php _e('Repeat Password', 'booked'); ?><?php if (BOOKED_DEMO_MODE): ?> <span class="not-bold"><?php _e('(disabled in demo)', 'cooked'); ?></span><?php endif; ?></label>
                <input<?php if (BOOKED_DEMO_MODE): ?> disabled<?php endif; ?> class="text-input" name="pass2" type="password" id="pass2" />
            </p><!-- .form-password -->
        </div>
        <?php if (!$disable_bio): ?>
	        <p class="form-textarea">
	            <label for="description"><?php _e('Short Bio', 'booked') ?><?php if (BOOKED_DEMO_MODE): ?> <span class="not-bold"><?php _e('(disabled in demo)', 'cooked'); ?></span><?php endif; ?></label>
	            <textarea<?php if (BOOKED_DEMO_MODE): ?> disabled<?php endif; ?> name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
	        </p><!-- .form-textarea -->
	    <?php endif; ?>

        <?php
            //action hook for plugin and extra fields
            do_action('edit_user_profile',$current_user);
        ?>
        <p class="form-submit">
            <input name="updateuser" type="submit" id="updateuser" class="submit button button-primary" value="<?php _e('Update', 'booked'); ?>" />
            <?php wp_nonce_field( 'update-user' ) ?>
            <input name="action" type="hidden" id="action" value="update-user" />
        </p><!-- .form-submit -->
    </form><!-- #adduser --><?php

}