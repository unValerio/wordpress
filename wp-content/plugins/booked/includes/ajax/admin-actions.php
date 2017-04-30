<?php

add_action('admin_init', 'bp_admin_ajax_callbacks');
function bp_admin_ajax_callbacks() {

	if (current_user_can('edit_booked_appointments') && isset($_GET['action']) && $_GET['action'] == 'adjust_default_timeslot_count' && isset($_GET['countAdjust']) && isset($_GET['day']) && isset($_GET['timeslot']))
	{

		$calendar_id = (isset($_GET['calendar_id']) ? $_GET['calendar_id'] : false);

		$day = $_GET['day'];
		$timeslot = $_GET['timeslot'];
		$countAdjust = $_GET['countAdjust'];

		if ($calendar_id):
			$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		else :
			$booked_defaults = get_option('booked_defaults');
		endif;

		if (!empty($booked_defaults[$day][$timeslot])):

			$current_count = $booked_defaults[$day][$timeslot];
			if ($countAdjust < 0 && $current_count > 1 || $countAdjust > 0):
				$final_count = $current_count + $countAdjust;
				$booked_defaults[$day][$timeslot] = $final_count;
				if ($calendar_id):
					update_option('booked_defaults_'.$calendar_id,$booked_defaults);
				else :
					update_option('booked_defaults',$booked_defaults);
				endif;
			else :
				$final_count = 1;
			endif;
			exit;

		endif;
		exit;
	}

	if (current_user_can('edit_booked_appointments') && isset($_GET['action']) && $_GET['action'] == 'delete_timeslot' && isset($_GET['day']) && isset($_GET['timeslot']))
	{

		$calendar_id = (isset($_GET['calendar_id']) ? $_GET['calendar_id'] : false);

		$day = $_GET['day'];
		$timeslot = $_GET['timeslot'];

		if ($calendar_id):
			$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		else :
			$booked_defaults = get_option('booked_defaults');
		endif;

		if (!empty($booked_defaults[$day][$timeslot])):

			unset($booked_defaults[$day][$timeslot]);

			$timeslot_total = 0;
			foreach($booked_defaults as $default):
				if (!empty($default)):
					$timeslot_total++;
				endif;
			endforeach;

			if ($calendar_id):
				if ($timeslot_total):
					update_option('booked_defaults_'.$calendar_id,$booked_defaults);
				else :
					delete_option('booked_defaults_'.$calendar_id);
				endif;
			else :
				if ($timeslot_total):
					update_option('booked_defaults',$booked_defaults);
				else :
					delete_option('booked_defaults');
				endif;
			endif;

		endif;
		exit;
	}

	if (current_user_can('edit_booked_appointments') && isset($_GET['action']) && $_GET['action'] == 'add_timeslots' && isset($_GET['day']) && isset($_GET['time_between']) && isset($_GET['startTime']) && isset($_GET['endTime']) && isset($_GET['interval']) && isset($_GET['count']))
	{

		$calendar_id = (isset($_GET['calendar_id']) ? $_GET['calendar_id'] : false);

		$day = $_GET['day'];
		$startTime = $_GET['startTime'];
		$endTime = $_GET['endTime'];
		if ($_GET['endTime'] == '2400'):
			$endTime = '2400';
		endif;

		$interval = $_GET['interval'];
		$count = $_GET['count'];
		$time_between = $_GET['time_between'];

		if ($calendar_id):
			$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		else :
			$booked_defaults = get_option('booked_defaults');
		endif;

		if (empty($booked_defaults)): $booked_defaults = array(); endif;

		do {

			$newStartTime = date("Hi", strtotime('+'.$interval.' minutes', strtotime($startTime)));
			if (!empty($booked_defaults[$day][$startTime.'-'.$newStartTime])): $currentCount = $booked_defaults[$day][$startTime.'-'.$newStartTime]; else : $currentCount = 0; endif;
			$booked_defaults[$day][$startTime.'-'.$newStartTime] = $count + $currentCount;

			if ($time_between):
				$time_to_add = $time_between + $interval;
			else :
				$time_to_add = $interval;
			endif;
			$startTime = date("Hi", strtotime('+'.$time_to_add.' minutes', strtotime($startTime)));
			if ($startTime == '0000'):
				$startTime = '2400';
			endif;

		} while ($startTime < $endTime);

		if ($calendar_id):
			update_option('booked_defaults_'.$calendar_id,$booked_defaults);
		else :
			update_option('booked_defaults',$booked_defaults);
		endif;

		exit;

	}

	if (current_user_can('edit_booked_appointments') && isset($_GET['action']) && $_GET['action'] == 'add_timeslot' && isset($_GET['day']) && isset($_GET['startTime']) && isset($_GET['endTime']) && isset($_GET['count']))
	{

		$calendar_id = (isset($_GET['calendar_id']) ? $_GET['calendar_id'] : false);

		$day = $_GET['day'];
		$startTime = $_GET['startTime'];
		$endTime = $_GET['endTime'];
		$count = $_GET['count'];

		if ($calendar_id):
			$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		else :
			$booked_defaults = get_option('booked_defaults');
		endif;

		if (empty($booked_defaults)): $booked_defaults = array(); endif;

		if (!empty($booked_defaults[$day][$startTime.'-'.$endTime])): $currentCount = $booked_defaults[$day][$startTime.'-'.$endTime]; else : $currentCount = 0; endif;
		$booked_defaults[$day][$startTime.'-'.$endTime] = $count + $currentCount;

		if ($calendar_id):
			update_option('booked_defaults_'.$calendar_id,$booked_defaults);
		else :
			update_option('booked_defaults',$booked_defaults);
		endif;

		exit;

	}

	if (current_user_can('edit_booked_appointments') && isset($_GET['action']) && $_GET['action'] == 'delete_appt' && isset($_GET['appt_id']) && isset($_GET['appt_id']))
	{

		$time_format = get_option('time_format');
		$date_format = get_option('date_format');

		$appt_id = $_GET['appt_id'];
		$appt = get_post($appt_id);
		$user_id = $appt->post_author;
		$timestamp = get_post_meta($appt_id,'_appointment_timestamp',true);
		$cf_meta_value = get_post_meta($appt_id,'_cf_meta_value',true);

		$timeslot = get_post_meta($appt_id,'_appointment_timeslot',true);
		$timeslots = explode('-',$timeslot);

		if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
			$timeslotText = __('All day','booked');
		else :
			$timeslotText = date_i18n($time_format,$timestamp);
		endif;
		
		$appointment_calendar_id = get_the_terms( $appt_id,'booked_custom_calendars' );
		if (!empty($appointment_calendar_id)):
			foreach($appointment_calendar_id as $calendar):
				$calendar_id = $calendar->term_id;
				break;
			endforeach;
		else:
			$calendar_id = false;
		endif;
		
		if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id,'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;

		// Send an email to the user?
		$email_content = get_option('booked_cancellation_email_content');
		$email_subject = get_option('booked_cancellation_email_subject');
		if ($email_content && $email_subject):
		
			$guest_name = get_post_meta($appt_id, '_appointment_guest_name',true);
			$guest_email = get_post_meta($appt_id, '_appointment_guest_email',true);
		
			if (!$guest_name):
				$user_name = booked_get_name($user_id);
				$user_data = get_userdata( $user_id );
				$email = $user_data->user_email;
			else:
				$user_name = $guest_name;
				$email = $guest_email;
			endif;
			
			$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
			$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
			$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
			$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
			$email_subject = str_replace($tokens,$replacements,$email_subject);
			booked_mailer( $email, $email_subject, $email_content );
		endif;

		wp_delete_post($appt_id,true);
		exit;
	}

	if (current_user_can('edit_booked_appointments') && isset($_GET['action']) && $_GET['action'] == 'approve_appt' && isset($_GET['appt_id']) && isset($_GET['appt_id']))
	{
		$appt_id = $_GET['appt_id'];
		$this_appt = array(
			'ID'          => $appt_id,
		    'post_status' => 'publish'
		);

		$time_format = get_option('time_format');
		$date_format = get_option('date_format');

		$appt = get_post($appt_id);
		$user_id = $appt->post_author;
		$timestamp = get_post_meta($appt_id,'_appointment_timestamp',true);
		$cf_meta_value = get_post_meta($appt_id,'_cf_meta_value',true);

		$timeslot = get_post_meta($appt_id,'_appointment_timeslot',true);
		$timeslots = explode('-',$timeslot);

		if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
			$timeslotText = __('All day','booked');
		else :
			$timeslotText = date_i18n($time_format,$timestamp);
		endif;
		
		$appointment_calendar_id = get_the_terms( $appt_id,'booked_custom_calendars' );
		if (!empty($appointment_calendar_id)):
			foreach($appointment_calendar_id as $calendar):
				$calendar_id = $calendar->term_id;
				break;
			endforeach;
		else:
			$calendar_id = false;
		endif;
		
		if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id,'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;

		// Send an email to the user?
		$email_content = get_option('booked_approval_email_content');
		$email_subject = get_option('booked_approval_email_subject');
		if ($email_content && $email_subject):
		
			$guest_name = get_post_meta($appt_id, '_appointment_guest_name',true);
			$guest_email = get_post_meta($appt_id, '_appointment_guest_email',true);
			
			if (!$guest_name):
				$user_name = booked_get_name($user_id);
				$user_data = get_userdata( $user_id );
				$email = $user_data->user_email;
			else:
				$user_name = $guest_name;
				$email = $guest_email;
			endif;
			
			$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
			$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
			$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
			$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
			$email_subject = str_replace($tokens,$replacements,$email_subject);
			booked_mailer( $email, $email_subject, $email_content );
		endif;

		wp_update_post( $this_appt );
		exit;
	}

	if (current_user_can('edit_booked_appointments') && isset($_POST['action']) && $_POST['action'] == 'add_appt' && isset($_POST['customer_type']) && isset($_POST['customer_type']))
	{

		do_action('booked_before_creating_appointment');

		$date = $_POST['date'];
		$timestamp = $_POST['timestamp'];
		$timeslot = $_POST['timeslot'];
		$customer_type = $_POST['customer_type'];

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		$calendar_id_for_cf = $calendar_id;
		if ($calendar_id):
			$calendar_id = array($calendar_id);
			$calendar_id = array_map( 'intval', $calendar_id );
			$calendar_id = array_unique( $calendar_id );
		endif;

		$time_format = get_option('time_format');
		$date_format = get_option('date_format');

		// Get custom field data (new in v1.2)
		if ($calendar_id):
			$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields_'.$calendar_id_for_cf)),true);
		else:
			$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true);
		endif;
		$custom_field_data = array();
		$cf_meta_value = '';

		if (!empty($custom_fields)):

			$previous_field = false;

			foreach($custom_fields as $field):

				$field_name = $field['name'];
				$field_title = $field['value'];

				$field_title_parts = explode('---',$field_name);
				if ($field_title_parts[0] == 'radio-buttons-label' || $field_title_parts[0] == 'checkboxes-label'):
					$current_group_name = $field_title;
				elseif ($field_title_parts[0] == 'single-radio-button' || $field_title_parts[0] == 'single-checkbox'):
					// Don't change the group name yet
				else :
					$current_group_name = $field_title;
				endif;

				if ($field_name != $previous_field){

					if (isset($_POST[$field_name]) && $_POST[$field_name]):

						$field_value = $_POST[$field_name];
						if (is_array($field_value)){
							$field_value = implode(', ',$field_value);
						}
						$custom_field_data[$current_group_name] = $field_value;

					endif;

					$previous_field = $field_name;

				}

			endforeach;

			$custom_field_data = apply_filters('booked_custom_field_data', $custom_field_data);

			if (!empty($custom_field_data)):
				foreach($custom_field_data as $label => $value):
					$cf_meta_value .= '<p class="cf-meta-value"><strong>'.$label.'</strong><br>'.$value.'</p>';
				endforeach;
			endif;

		endif;
		// END Get custom field data
		
		if ($customer_type == 'guest'):
		
			$name = esc_attr($_POST['guest_name']);
			$email = esc_attr($_POST['guest_email']);
			
			if (is_email($email) && $name):

				// Create a new appointment post for a current customer
				$new_post = apply_filters('booked_new_appointment_args', array(
					'post_title' => date_i18n($date_format,$timestamp).' @ '.date_i18n($time_format,$timestamp).' (User: Guest)',
					'post_content' => '',
					'post_status' => 'publish',
					'post_date' => date('Y',strtotime($date)).'-'.date('m',strtotime($date)).'-01 00:00:00',
					'post_type' => 'booked_appointments'
				));
				$post_id = wp_insert_post($new_post);
	
				update_post_meta($post_id, '_appointment_guest_name', $name);
				update_post_meta($post_id, '_appointment_guest_email', $email);
				update_post_meta($post_id, '_appointment_timestamp', $timestamp);
				update_post_meta($post_id, '_appointment_timeslot', $timeslot);
	
				if (apply_filters('booked_update_cf_meta_value', true)) {
					update_post_meta($post_id, '_cf_meta_value', $cf_meta_value);
				}
	
				if (apply_filters('booked_update_appointment_calendar', true)) {
					if (isset($calendar_id) && $calendar_id): wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); endif;
				}
			
				if (isset($calendar_id[0]) && $calendar_id[0] && !empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id[0],'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;
	
				do_action('booked_new_appointment_created', $post_id);
	
				$timeslots = explode('-',$timeslot);
	
				if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
					$timeslotText = __('All day','booked');
				else :
					$timeslotText = date_i18n($time_format,$timestamp);
				endif;
	
				// Send an email to the User?
				$email_content = get_option('booked_approval_email_content');
				$email_subject = get_option('booked_approval_email_subject');
				if ($email_content && $email_subject):
					$user_name = $name;
					$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
					$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;
	
				echo $date;

			endif;
			exit;
			
		endif;

		if ($customer_type == 'current'):
			$user_id = $_POST['user_id'];

			// Create a new appointment post for a current customer
			$new_post = apply_filters('booked_new_appointment_args', array(
				'post_title' => date_i18n($date_format,$timestamp).' @ '.date_i18n($time_format,$timestamp).' (User: '.$user_id.')',
				'post_content' => '',
				'post_status' => 'publish',
				'post_date' => date('Y',strtotime($date)).'-'.date('m',strtotime($date)).'-01 00:00:00',
				'post_author' => $user_id,
				'post_type' => 'booked_appointments'
			));
			$post_id = wp_insert_post($new_post);

			update_post_meta($post_id, '_appointment_timestamp', $timestamp);
			update_post_meta($post_id, '_appointment_timeslot', $timeslot);
			update_post_meta($post_id, '_appointment_user', $user_id);

			if (apply_filters('booked_update_cf_meta_value', true)) {
				update_post_meta($post_id, '_cf_meta_value', $cf_meta_value);
			}

			if (apply_filters('booked_update_appointment_calendar', true)) {
				if (isset($calendar_id) && $calendar_id): wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); endif;
			}
		
			if (isset($calendar_id[0]) && $calendar_id[0] && !empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id[0],'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;

			do_action('booked_new_appointment_created', $post_id);

			$timeslots = explode('-',$timeslot);

			if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
				$timeslotText = __('All day','booked');
			else :
				$timeslotText = date_i18n($time_format,$timestamp);
			endif;

			// Send an email to the User?
			$email_content = get_option('booked_approval_email_content');
			$email_subject = get_option('booked_approval_email_subject');
			if ($email_content && $email_subject):
				$user_name = booked_get_name($user_id);
				$user_data = get_userdata( $user_id );
				$email = $user_data->user_email;
				$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
				$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
				$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_subject = str_replace($tokens,$replacements,$email_subject);
				booked_mailer( $email, $email_subject, $email_content );
			endif;

			echo $date;
			exit;

		else :

			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$password = wp_generate_password();

			if ($last_name): $username = $first_name.$last_name; else : $username = $first_name; endif;
			$username = strtolower(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username)));
			$errors = booked_registration_validation($username,$email);

			if (!empty($errors)):
				$rand = rand(111,999);
				if ($last_name): $username = $first_name.$last_name.'_'.$rand; else : $username = $first_name.'_'.$rand; endif;
				$username = strtolower(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username)));
				$errors = booked_registration_validation($username,$email);
			endif;

			if ($last_name): $nickname = $first_name.' '.$last_name; else : $nickname = $first_name; endif;

			if (empty($errors)):
				$userdata = array(
		        	'user_login'    =>   $username,
					'user_email'    =>   $email,
					'user_pass'		=>	 $password,
					'first_name'	=>	 $first_name,
					'last_name'		=>	 $last_name,
					'nickname'		=>	 $nickname
		        );
		        $user_id = wp_insert_user( $userdata );

		        // Send a registration welcome email to the new user?
		        $email_content = get_option('booked_registration_email_content');
				$email_subject = get_option('booked_registration_email_subject');
				if ($email_content && $email_subject):
					$tokens = array('%name%','%username%','%password%');
					$replacements = array($nickname,$username,$password);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;

				$timeslots = explode('-',$timeslot);

				if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
					$timeslotText = __('All day','booked');
				else :
					$timeslotText = date_i18n($time_format,$timestamp);
				endif;
				
				if (isset($calendar_id) && $calendar_id): $calendar_term = get_term_by('id',$calendar_id,'booked_custom_calendars'); $calendar_name = $calendar_term->name; else: $calendar_name = false; endif;

				// Send an email to the user?
				$email_content = get_option('booked_approval_email_content');
				$email_subject = get_option('booked_approval_email_subject');
				if ($email_content && $email_subject):
					$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
					$replacements = array($nickname,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;

		        if ($phone){
			        update_user_meta($user_id,'booked_phone',$phone);
		        }

		        // Create a new appointment post for this new customer
				$new_post = apply_filters('booked_new_appointment_args', array(
					'post_title' => date_i18n($date_format,$timestamp).' @ '.date_i18n($time_format,$timestamp).' (User: '.$user_id.')',
					'post_content' => '',
					'post_status' => 'publish',
					'post_date' => date('Y',strtotime($date)).'-'.date('m',strtotime($date)).'-01 00:00:00',
					'post_author' => $user_id,
					'post_type' => 'booked_appointments'
				));
				$post_id = wp_insert_post($new_post);

				update_post_meta($post_id, '_appointment_timestamp', $timestamp);
				update_post_meta($post_id, '_appointment_timeslot', $timeslot);
				update_post_meta($post_id, '_appointment_user', $user_id);

				if (apply_filters('booked_update_cf_meta_value', true)) {
					update_post_meta($post_id, '_cf_meta_value', $cf_meta_value);
				}

		        if (apply_filters('booked_update_appointment_calendar', true)) {
					if (isset($calendar_id) && $calendar_id): wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); endif;
				}

		        do_action('booked_new_appointment_created', $post_id);

				echo 'success###'.$date;
			else :
				echo 'error###'.__('Whoops!','booked').'
'.implode('
',$errors);
			endif;

		endif;
		exit;
	}

	if (current_user_can('edit_booked_appointments') && isset($_POST['action']) && $_POST['action'] == 'save_custom_fields' && isset($_POST['booked_custom_fields']))
	{
		$custom_fields = $_POST['booked_custom_fields'];
		$calendar_id = $_POST['booked_cf_calendar_id'];
		if ($custom_fields != '[]'):
			if ($calendar_id):
				update_option('booked_custom_fields_'.$calendar_id,$custom_fields);
			else:
				update_option('booked_custom_fields',$custom_fields);
			endif;
		else:
			if ($calendar_id):
				delete_option('booked_custom_fields_'.$calendar_id);
			else:
				delete_option('booked_custom_fields');
			endif;
		endif;
		exit;
	}

	if (current_user_can('edit_booked_appointments') && isset($_GET['action']) && $_GET['action'] == 'save_calendars' && isset($_GET['booked_calendars']))
	{
		$calendars = $_GET['booked_calendars'];
		update_option('booked_calendars',$calendars);
		exit;
	}

	if (current_user_can('edit_booked_appointments') && isset($_POST['action']) && $_POST['action'] == 'save_custom_time_slots' && isset($_POST['custom_timeslots_encoded']))
	{
		$custom_timeslots_encoded = stripslashes($_POST['custom_timeslots_encoded']);
		update_option('booked_custom_timeslots_encoded',$custom_timeslots_encoded);
		exit;
	}

	if (current_user_can('edit_booked_appointments') && isset($_POST['action']) && $_POST['action'] == 'add_custom_timeslot' && isset($_POST['startTime']) && isset($_POST['endTime']) && isset($_POST['count']))
	{

		$startTime = $_POST['startTime'];
		$endTime = $_POST['endTime'];
		$count = $_POST['count'];
		$current_times = json_decode(stripslashes($_POST['currentTimes']),true);

		if (isset($current_times[$startTime.'-'.$endTime])):
			$current_times[$startTime.'-'.$endTime] = $current_times[$startTime.'-'.$endTime] + $count;
		else :
			$current_times[$startTime.'-'.$endTime] = $count;
		endif;

		echo json_encode($current_times);
		exit;

	}

	if (current_user_can('edit_booked_appointments') && isset($_POST['action']) && $_POST['action'] == 'add_custom_timeslots' && isset($_POST['time_between']) && isset($_POST['startTime']) && isset($_POST['endTime']) && isset($_POST['interval']) && isset($_POST['count']))
	{

		$startTime = $_POST['startTime'];
		$endTime = $_POST['endTime'];
		if ($_POST['endTime'] == '2400'):
			$endTime = '2400';
		endif;

		$interval = $_POST['interval'];
		$count = $_POST['count'];
		$time_between = $_POST['time_between'];
		$current_times = json_decode(stripslashes($_POST['currentTimes']),true);

		do {

			$newStartTime = date("Hi", strtotime('+'.$interval.' minutes', strtotime($startTime)));

			if (isset($current_times[$startTime.'-'.$newStartTime])):
				$current_times[$startTime.'-'.$newStartTime] = $current_times[$startTime.'-'.$newStartTime] + $count;
			else :
				$current_times[$startTime.'-'.$newStartTime] = $count;
			endif;

			if ($time_between):
				$time_to_add = $time_between + $interval;
			else :
				$time_to_add = $interval;
			endif;
			$startTime = date("Hi", strtotime('+'.$time_to_add.' minutes', strtotime($startTime)));
			if ($startTime == '0000'):
				$startTime = '2400';
			endif;

		} while ($startTime < $endTime);

		echo json_encode($current_times);
		exit;

	}

	if (current_user_can('edit_booked_appointments') && isset($_POST['action']) && $_POST['action'] == 'delete_custom_timeslot' && isset($_POST['currentArray']))
	{

		$timeslot_to_delete = $_POST['timeslot'];
		$current_times = json_decode(stripslashes($_POST['currentArray']),true);

		if (isset($current_times[$timeslot_to_delete])):
			unset($current_times[$timeslot_to_delete]);
		endif;

		echo json_encode($current_times);
		exit;

	}

	if (current_user_can('edit_booked_appointments') && isset($_POST['action']) && $_POST['action'] == 'adjust_custom_timeslot_count' && isset($_POST['currentArray']) && isset($_POST['newCount']) && isset($_POST['timeslot']))
	{

		$current_times = json_decode(stripslashes($_POST['currentArray']),true);
		$timeslot = $_POST['timeslot'];
		$newCount = $_POST['newCount'];

		if (!empty($current_times[$timeslot])):

			$current_count = $current_times[$timeslot];
			if ($newCount > 0):
				$current_times[$timeslot] = $newCount;
			else :
				$current_times[$timeslot] = 1;
			endif;

		endif;

		echo json_encode($current_times);
		exit;

	}


}