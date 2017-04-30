<?php

add_action( 'template_redirect', 'bp_fe_ajax_callbacks', 10 );
function bp_fe_ajax_callbacks() {

	if (isset($_POST['action']) && $_POST['action'] == 'ajax_login')
	{

		$nonce_check = wp_verify_nonce( $_POST['security'], 'ajax_login_nonce' );

		if ($nonce_check){

			$creds = array();
			$creds['user_login'] = $_POST['username'];
			$creds['user_password'] = $_POST['password'];
			$creds['remember'] = true;

			$user = wp_signon( $creds, false );

			if ( !is_wp_error($user) ):
				echo 'success';
				exit;
			endif;

		}

		exit;

	}

	if (isset($_POST['action']) && $_POST['action'] == 'add_appt' && isset($_POST['customer_type']))
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
		$appointment_default_status = get_option('booked_new_appointment_default','draft');

		// Get custom field data (new in v1.2)
		$custom_fields = array();

		if ( $calendar_id_for_cf ) {
			$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields_'.$calendar_id_for_cf)),true);
		}
		
		if ( !$custom_fields ) {
			$custom_fields = json_decode(stripslashes(get_option('booked_custom_fields')),true);
		}
		
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

				// Create a new appointment post for a guest customer
				$new_post = apply_filters('booked_new_appointment_args', array(
					'post_title' => date_i18n($date_format,$timestamp).' @ '.date_i18n($time_format,$timestamp).' (User: Guest)',
					'post_content' => '',
					'post_status' => $appointment_default_status,
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
					if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id[0],'booked_custom_calendars'); $calendar_name = $calendar_term->name; wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); else: $calendar_name = false; endif;
				}
	
				do_action('booked_new_appointment_created', $post_id);
	
				$timeslots = explode('-',$timeslot);
	
				if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
					$timeslotText = __('All day','booked');
				else :
					$timeslotText = date_i18n($time_format,$timestamp);
				endif;
					
				// Send a confirmation email to the User?
				$email_content = get_option('booked_appt_confirmation_email_content');
				$email_subject = get_option('booked_appt_confirmation_email_subject');
				if ($email_content && $email_subject):
					$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
					$replacements = array($name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;
	
				// Send an email to the Admin?
				$email_content = get_option('booked_admin_appointment_email_content');
				$email_subject = get_option('booked_admin_appointment_email_subject');
				if ($email_content && $email_subject):
					$admin_email = booked_which_admin_to_send_email($_POST['calendar_id']);
					$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
					$replacements = array($name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $admin_email, $email_subject, $email_content );
				endif;
	
				echo 'success###'.$date;
				
			else :
			
				$errors[] = __('A name and a valid email address are required.','booked');
			
				echo 'error###'.__('Whoops!','booked').'
'.implode('
',$errors);
			
			endif;
			exit;
			
		endif;

		if ($customer_type == 'current'):
		
			$user_id = $_POST['user_id'];

			// Create a new appointment post for a current customer
			$new_post = apply_filters('booked_new_appointment_args', array(
				'post_title' => date_i18n($date_format,$timestamp).' @ '.date_i18n($time_format,$timestamp).' (User: '.$user_id.')',
				'post_content' => '',
				'post_status' => $appointment_default_status,
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
				if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id[0],'booked_custom_calendars'); $calendar_name = $calendar_term->name; wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); else: $calendar_name = false; endif;
			}

			do_action('booked_new_appointment_created', $post_id);

			$timeslots = explode('-',$timeslot);

			if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
				$timeslotText = __('All day','booked');
			else :
				$timeslotText = date_i18n($time_format,$timestamp);
			endif;

			// Send a confirmation email to the User?
			$email_content = get_option('booked_appt_confirmation_email_content');
			$email_subject = get_option('booked_appt_confirmation_email_subject');
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

			// Send an email to the Admin?
			$email_content = get_option('booked_admin_appointment_email_content');
			$email_subject = get_option('booked_admin_appointment_email_subject');
			if ($email_content && $email_subject):
				$admin_email = booked_which_admin_to_send_email($_POST['calendar_id']);
				$user_name = booked_get_name($user_id);
				$user_data = get_userdata( $user_id );
				$email = $user_data->user_email;
				$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
				$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
				$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_subject = str_replace($tokens,$replacements,$email_subject);
				booked_mailer( $admin_email, $email_subject, $email_content );
			endif;

			echo 'success###'.$date;
			exit;
			
		endif;

		if ($customer_type == 'new'):

			$first_name = esc_attr($_POST['first_name']);
			$last_name = esc_attr($_POST['last_name']);
			$email = $_POST['email'];
			$password = wp_generate_password();

			if (isset($_POST['captcha_word'])):
	        	$captcha_word = strtolower($_POST['captcha_word']);
				$captcha_code = strtolower($_POST['captcha_code']);
	        else :
	        	$captcha_word = false;
				$captcha_code = false;
	        endif;

			if ($last_name): $username = $first_name.$last_name; else : $username = $first_name; endif;
			$username = strtolower(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username)));
			$errors = booked_registration_validation($username,$email,$captcha_word,$captcha_code);

			if (!empty($errors)):
				$rand = rand(111,999);
				if ($last_name): $username = $first_name.$last_name.'_'.$rand; else : $username = $first_name.'_'.$rand; endif;
				$username = strtolower(preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username)));
				$errors = booked_registration_validation($username,$email,$captcha_word,$captcha_code);
			endif;

			if ($last_name): $nickname = $first_name.' '.$last_name; else : $nickname = $first_name; endif;

			if (empty($errors)):
				$userdata = array(
		        	'user_login'    =>  $username,
					'user_email'    =>  $email,
					'user_pass'     =>  $password,
					'first_name'	=>	$first_name,
					'last_name'		=>	$last_name,
					'nickname'		=>	$nickname
		        );
		        $user_id = wp_insert_user( $userdata );

		        $creds = array();
				$creds['user_login'] = $username;
				$creds['user_password'] = $password;
				$creds['remember'] = true;
				$user_signon = wp_signon( $creds, false );
				if ( is_wp_error($user_signon) ){
					$signin_errors = $user_signon->get_error_message();
				}

		        $timeslots = explode('-',$timeslot);

				if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
					$timeslotText = __('All day','booked');
				else :
					$timeslotText = date_i18n($time_format,$timestamp);
				endif;
				
				if (apply_filters('booked_update_appointment_calendar', true)) {
					if (!empty($calendar_id)): $calendar_term = get_term_by('id',$calendar_id[0],'booked_custom_calendars'); $calendar_name = $calendar_term->name; wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); else: $calendar_name = false; endif;
				}

		        // Send an email to the Admin?
		        $email_content = get_option('booked_admin_appointment_email_content');
				$email_subject = get_option('booked_admin_appointment_email_subject');
				if ($email_content && $email_subject):
					$admin_email = booked_which_admin_to_send_email($_POST['calendar_id']);
					$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
					$replacements = array($nickname,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $admin_email, $email_subject, $email_content );
				endif;

				// Send a registration welcome email to the new user?
				$email_content = get_option('booked_registration_email_content');
				$email_subject = get_option('booked_registration_email_subject');
				if ($email_content && $email_subject):
					$user_name = booked_get_name($user_id);
					$tokens = array('%name%','%username%','%password%');
					$replacements = array($user_name,$username,$password);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;

				// Send an email to the User?
				$email_content = get_option('booked_appt_confirmation_email_content');
				$email_subject = get_option('booked_appt_confirmation_email_subject');
				if ($email_content && $email_subject):
					$user_name = booked_get_name($user_id);
					$user_data = get_userdata($user_id);
					$email = $user_data->user_email;
					$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
					$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
					$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
					$email_subject = str_replace($tokens,$replacements,$email_subject);
					booked_mailer( $email, $email_subject, $email_content );
				endif;

		        // Create a new appointment post for this new customer
				$new_post = apply_filters('booked_new_appointment_args', array(
					'post_title' => date_i18n($date_format,$timestamp).' @ '.date_i18n($time_format,$timestamp).' (User: '.$user_id.')',
					'post_content' => '',
					'post_status' => $appointment_default_status,
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
					if (!empty($calendar_id)): wp_set_object_terms($post_id,$calendar_id,'booked_custom_calendars'); endif;
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

	if (isset($_POST['action']) && $_POST['action'] == 'cancel_appt' && isset($_POST['appt_id']) && isset($_POST['appt_id']))
	{

		$appt_id = $_POST['appt_id'];
		$timeslot = get_post_meta($appt_id,'_appointment_timeslot',true);
		$timestamp = get_post_meta($appt_id,'_appointment_timestamp',true);
		$cf_meta_value = get_post_meta($appt_id,'_cf_meta_value',true);
		$timeslots = explode('-',$timeslot);
		$time_format = get_option('time_format');
		$date_format = get_option('date_format');

		if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
			$timeslotText = __('All day','booked');
		else :
			$timeslotText = date_i18n($time_format,$timestamp);
		endif;

		
		$appt = get_post( $appt_id );
		$appt_author = $appt->post_author;

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

		if (get_current_user_id() == $appt_author):

			// Send an email to the Admin?
			$email_content = get_option('booked_admin_cancellation_email_content');
			$email_subject = get_option('booked_admin_cancellation_email_subject');
			if ($email_content && $email_subject):
				$admin_email = booked_which_admin_to_send_email($calendar_id);
				$user_name = booked_get_name( $appt_author );
				$user_data = get_userdata( $appt_author );
				$email = $user_data->user_email;
				$tokens = array('%name%','%date%','%time%','%customfields%','%calendar%','%email%');
				$replacements = array($user_name,date_i18n($date_format,$timestamp),$timeslotText,$cf_meta_value,$calendar_name,$email);
				$email_content = htmlentities(str_replace($tokens,$replacements,$email_content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_content = html_entity_decode($email_content, ENT_QUOTES | ENT_IGNORE, "UTF-8");
				$email_subject = str_replace($tokens,$replacements,$email_subject);
				booked_mailer( $admin_email, $email_subject, $email_content );
			endif;

			wp_delete_post($appt_id,true);

		endif;
		exit;
	}

}