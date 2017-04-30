<?php

class BookedDashboardWidget {

	function __construct(){
		if (current_user_can('edit_booked_appointments')):
			add_action( 'wp_dashboard_setup', array($this, 'booked_dashboard_widget') );
		endif;
	}

	public function booked_dashboard_widget() {

		wp_add_dashboard_widget(
	        'booked_upcoming_appointments',
	        '<i class="fa fa-calendar"></i>&nbsp;&nbsp;Upcoming Appointments',
	        array($this, 'booked_dashboard_widget_function')
	    );

	}

	public function booked_dashboard_widget_function() {

		echo '<div id="data-ajax-url">'.get_admin_url().'</div>';
		echo '<div class="booked-pending-appt-list booked-dashboard-widget">';

			/*
			Set some variables
			*/

			$time_format = get_option('time_format');
			$date_format = get_option('date_format');

			/*
			Grab all of the appointments for this day
			*/
			
			$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
			if (!empty($calendars) && booked_user_role() == 'booked_booking_agent'):
				
				global $current_user;
				$calendars = booked_filter_agent_calendars($current_user,$calendars);
				
				foreach($calendars as $calendar):
					$calendar_ids[] = $calendar->term_id;
				endforeach;
			
				$args = array(
				   'post_type' => 'booked_appointments',
				   'posts_per_page' => -1,
				   'post_status' => 'publish',
				   'meta_key' => '_appointment_timestamp',
				   'orderby' => 'meta_value_num',
				   'order' => 'ASC',
				   'tax_query' => array(
						array(
							'taxonomy' => 'booked_custom_calendars',
							'field'    => 'id',
							'terms'    => $calendar_ids,
						),
					),
				);
				
			elseif (empty($calendars) && booked_user_role() == 'booked_booking_agent'):
				$args = false;
			else:
				$args = array(
					'post_type' => 'booked_appointments',
					'posts_per_page' => -1,
					'post_status' => 'publish',
					'meta_key' => '_appointment_timestamp',
					'orderby' => 'meta_value_num',
					'order' => 'ASC'
				);
			endif;

			$appointments_array = array();
			$counter = 0;

			$bookedAppointments = new WP_Query($args);
			if($bookedAppointments->have_posts()):
				while ($bookedAppointments->have_posts()):

					$bookedAppointments->the_post();
					global $post;

					$calendars = array();
	
					$calendar_terms = get_the_terms($post->ID,'booked_custom_calendars');
					if (!empty($calendar_terms)):
						foreach($calendar_terms as $calendar){
							$calendars[$calendar->term_id] = $calendar->name;
						}
					endif;
					
					$guest_name = get_post_meta($post->ID, '_appointment_guest_name',true);
					$guest_email = get_post_meta($post->ID, '_appointment_guest_email',true);
					
					$timestamp = get_post_meta($post->ID, '_appointment_timestamp',true);
					$timeslot = get_post_meta($post->ID, '_appointment_timeslot',true);
					$user_id = get_post_meta($post->ID, '_appointment_user',true);
					$day = date('d',$timestamp);

					$current_timestamp = current_time('timestamp');

					if ($timestamp >= $current_timestamp){
						$counter++;
						
						if (!$guest_name):
							$user_id = get_post_meta($post->ID, '_appointment_user',true);
							$appointments_array[$post->ID]['user'] = $user_id;
						else:
							$appointments_array[$post->ID]['guest_name'] = $guest_name;
							$appointments_array[$post->ID]['guest_email'] = $guest_email;
						endif;
						
						$appointments_array[$post->ID]['post_id'] = $post->ID;
						$appointments_array[$post->ID]['timestamp'] = $timestamp;
						$appointments_array[$post->ID]['timeslot'] = $timeslot;
						$appointments_array[$post->ID]['status'] = $post->post_status;
						$appointments_array[$post->ID]['calendar'] = implode(',',$calendars);
						if ($counter == 10): break; endif;
					}

				endwhile;
				$appointments_array = apply_filters('booked_appointments_array', $appointments_array);
			endif;

			echo '<div class="pending-appt'.(!empty($appointments_array) ? ' no-pending-message' : '').'">';
				echo '<p style="text-align:center;">'.__('There are no upcoming appointments.','booked').'</p>';
			echo '</div>';

			/*
			Let's loop through the pending appointments
			*/

			foreach($appointments_array as $appt):

				echo '<div class="pending-appt bookedClearFix" data-appt-id="'.$appt['post_id'].'">';

					$date_display = date_i18n($date_format,$appt['timestamp']);
					$day_name = date_i18n('l',$appt['timestamp']);

					$timeslots = explode('-',$appt['timeslot']);
					$time_start = date($time_format,strtotime($timeslots[0]));
					$time_end = date($time_format,strtotime($timeslots[1]));

					$date_to_compare = strtotime(date('F j, Y',$appt['timestamp']).' '.date('H:i:s',strtotime($timeslots[0])));
					$late_date = current_time('timestamp');

					if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
						$timeslotText = __('All day','booked');
					else :
						$timeslotText = $time_start.'&ndash;'.$time_end;
					endif;

					$status = ($appt['status'] == 'draft' ? 'pending' : 'approved');
					echo '<span class="appt-block" data-appt-id="'.$appt['post_id'].'">';

						if (!isset($appt['guest_name'])):
							$user_info = get_userdata($appt['user']);
							if (isset($user_info->ID)):
								if ($user_info->user_firstname):
									$user_display = '<a href="#" class="user" data-user-id="'.$appt['user'].'">'.$user_info->user_firstname.($user_info->user_lastname ? ' '.$user_info->user_lastname : '').'</a>';
								elseif ($user_info->display_name):
									$user_display = '<a href="#" class="user" data-user-id="'.$appt['user'].'">'.$user_info->display_name.'</a>';
								else :
									$user_display = '<a href="#" class="user" data-user-id="'.$appt['user'].'">'.$user_info->user_login.'</a>';
								endif;
							else :
								$user_display = __('(this user no longer exists)','booked');
							endif;
						else :
							$user_display = '<a href="#" class="user" data-user-id="0">'.$appt['guest_name'].'</a>';
						endif;
					
						echo $user_display;
						echo '<br>';
						if ($late_date > $date_to_compare): echo '<span class="late-appt">' . __('This appointment has passed.','booked') . '</span><br>'; endif;
						if ($appt['calendar']): echo '<strong style="color:#000">'.$appt['calendar'].'</strong><br>'; endif;
						echo '<i class="fa fa-calendar"></i>&nbsp;&nbsp;'.$day_name.', '.$date_display;
						echo '&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i>&nbsp;&nbsp;'.$timeslotText;

					echo '</span>';

				echo '</div>';

			endforeach;

		echo '</div>';

		wp_reset_postdata();

	}

}

new BookedDashboardWidget;