<?php

add_action('admin_init', 'bp_admin_ajax_loaders');
function bp_admin_ajax_loaders() {

	/*
	Load the timeslots for a single day
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'load_timeslots' && isset($_POST['day']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);

		// Get the saved Default Timeslots
		if ($calendar_id):
			$booked_defaults = get_option('booked_defaults_'.$calendar_id);
		else :
			$booked_defaults = get_option('booked_defaults');
		endif;

		$day = $_POST['day'];
		$time_format = get_option('time_format');

		if (!empty($booked_defaults[$day])):
			ksort($booked_defaults[$day]);
			foreach($booked_defaults[$day] as $time => $count):
				echo booked_render_timeslot_info($time_format,$time,$count);
			endforeach;
		else :
			echo '<p><small>'.__('No time slots.','booked').'</small></p>';
		endif;
		exit;

	}

	/*
	Load the timeslots for the whole week
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'load_full_timeslots')
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_render_timeslots($calendar_id);
		exit;

	}
	
	/*
	Load the custom fields
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'load_full_customfields')
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_render_custom_fields($calendar_id);
		exit;

	}

	/*
	Load the calendar picker
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_picker' && isset($_POST['gotoMonth']))
	{

		$timestamp = ($_POST['gotoMonth'] != 'false' ? strtotime($_POST['gotoMonth']) : current_time('timestamp'));

		$year = date('Y',$timestamp);
		$month = date('m',$timestamp);

		booked_admin_calendar($year,$month,false,'small');
		exit;

	}

	/*
	Load a calendar month
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_month' && isset($_POST['gotoMonth']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		$timestamp = ($_POST['gotoMonth'] != 'false' ? strtotime($_POST['gotoMonth']) : current_time('timestamp'));

		$year = date('Y',$timestamp);
		$month = date('m',$timestamp);

		booked_admin_calendar($year,$month,$calendar_id);
		exit;

	}

	/*
	Load a calendar date
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'calendar_date' && isset($_POST['date']))
	{

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_admin_calendar_date_content($_POST['date'],$calendar_id);
		exit;

	}


	/*
	Refresh a calendar date square
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'refresh_date_square' && isset($_POST['date']))
	{
		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);
		booked_admin_calendar_date_square($_POST['date'],$calendar_id);
		exit;
	}


	/*
	Load the user info modal
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'user_info_modal' && isset($_POST['user_id']))
	{

		if (!$_POST['user_id'] && isset($_POST['appt_id'])):
		
			$guest_name = get_post_meta($_POST['appt_id'], '_appointment_guest_name',true);
			$guest_email = get_post_meta($_POST['appt_id'], '_appointment_guest_email',true);
		
			echo '<p><small>'.__('Contact Information','booked').'</small></p>';
			echo '<p><strong class="booked-left-title">'.__('Name','booked').':</strong> '.$guest_name.'</p>';
			if ($guest_email) : echo '<p><strong class="booked-left-title">'.__('Email','booked').':</strong> <a href="mailto:'.$guest_email.'">'.$guest_email.'</a></p>'; endif;
			
		else :

			// Customer Information
			$user_info = get_userdata($_POST['user_id']);
			$display_name = booked_get_name($_POST['user_id']);
			$email = $user_info->user_email;
			$phone = get_user_meta($_POST['user_id'], 'booked_phone', true);
	
			echo '<p><small>'.__('Contact Information','booked').'</small></p>';
			echo '<p><strong class="booked-left-title">'.__('Name','booked').':</strong> '.$display_name.'</p>';
			if ($email) : echo '<p><strong class="booked-left-title">'.__('Email','booked').':</strong> <a href="mailto:'.$email.'">'.$email.'</a></p>'; endif;
			if ($phone) : echo '<p><strong class="booked-left-title">'.__('Phone','booked').':</strong> <a href="tel:'.preg_replace('/[^0-9+]/', '', $phone).'">'.$phone.'</a></p>'; endif;

		endif;

		// Appointment Information
		if (isset($_POST['appt_id'])):

			$time_format = get_option('time_format');
			$date_format = get_option('date_format');
			$appt_id = $_POST['appt_id'];

			$timestamp = get_post_meta($appt_id, '_appointment_timestamp',true);
			$timeslot = get_post_meta($appt_id, '_appointment_timeslot',true);
			$cf_meta_value = get_post_meta($appt_id, '_cf_meta_value',true);

			$date_display = date_i18n($date_format,$timestamp);
			$day_name = date_i18n('l',$timestamp);

			$timeslots = explode('-',$timeslot);
			$time_start = date($time_format,strtotime($timeslots[0]));
			$time_end = date($time_format,strtotime($timeslots[1]));

			if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
				$timeslotText = __('All day','booked');
			else :
				$timeslotText = $time_start.' '.__('to','booked').' '.$time_end;
			endif;

			echo '<br><p><small>'.__('Appointment Information','booked').'</small></p>';
			do_action('booked_before_appointment_information_admin');
			echo '<p><strong class="booked-left-title">'.__('Date','booked').':</strong> '.$day_name.', '.$date_display.'</p>';
			echo '<p><strong class="booked-left-title">'.__('Time','booked').':</strong> '.$timeslotText.'</p>';
			echo ($cf_meta_value ? '<div class="cf-meta-values">'.$cf_meta_value.'</div>' : '');
			do_action('booked_after_appointment_information_admin');

		endif;

		// Close button
		echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';
		exit;


	}

	/*
	Load the New Appointment form
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'new_appointment_form' && isset($_POST['date']) && isset($_POST['timeslot']))
	{

		$date = $_POST['date'];
		$timeslot = $_POST['timeslot'];
		$timeslot_parts = explode('-',$timeslot);

		$date_format = get_option('date_format');
		$time_format = get_option('time_format');

		$args = array('orderby' => 'display_name');
		$user_array = get_users($args);

		$calendar_id = (isset($_POST['calendar_id']) ? $_POST['calendar_id'] : false);

		if ($timeslot_parts[0] == '0000' && $timeslot_parts[1] == '2400'):
			$timeslotText = __('All day','booked');
		else :
			$timeslotText = date_i18n($time_format,strtotime($timeslot_parts[0])).' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1]));
		endif;

		?><p><small><?php _e('New Appointment','booked'); ?></small></p>
		<p class="name"><b><i class="fa fa-calendar-o"></i>&nbsp;&nbsp;<?php echo date_i18n($date_format, strtotime($date)); ?>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php echo $timeslotText; ?></b></p>
		<form action="" method="post" class="booked-form" id="newAppointmentForm"<?php if ($calendar_id): echo ' data-calendar-id="'.$calendar_id.'"'; endif; ?>>

			<input type="hidden" name="date" value="<?php echo date('Y-m-j', strtotime($date)); ?>" />
			<input type="hidden" name="timestamp" value="<?php echo strtotime($date.' '.$timeslot_parts[0]); ?>" />
			<input type="hidden" name="timeslot" value="<?php echo $timeslot; ?>" />
			
			<?php $guest_booking = (get_option('booked_booking_type','registered') == 'guest' ? true : false); ?>

			<div class="field">
				<input data-condition="customer_type" type="radio" name="customer_type" id="customer_current" value="current" checked> <label for="customer_current"><?php _e('Current Customer','booked'); ?></label>
			</div>
			<div class="field">
				<input data-condition="customer_type" type="radio" name="customer_type" id="customer_new" value="new"> <label for="customer_new"><?php _e('New Customer','booked'); ?></label>
			</div>
			
			<?php if ($guest_booking): ?>
				<div class="field">
					<input data-condition="customer_type" type="radio" name="customer_type" id="customer_guest" value="guest"> <label for="customer_guest"><?php _e('Guest','booked'); ?></label>
				</div>
			<?php endif; ?>

			<hr>

			<div class="condition-block customer_type default" id="condition-current">
				<div class="field">
					<select data-placeholder="<?php _e('Select a customer ...','booked'); ?>" id="userList" name="user_id">
						<option></option>
						<?php foreach($user_array as $user): ?>
							<option value="<?php echo $user->ID; ?>"><?php echo booked_get_name($user->ID); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="condition-block customer_type" id="condition-new">
				<div class="field">
					<input value="<?php _e('First name...','booked'); ?>" title="<?php _e('First name...','booked'); ?>" type="text" class="textfield" name="first_name" />
					<input value="<?php _e('Last name...','booked'); ?>" title="<?php _e('Last name...','booked'); ?>" type="text" class="textfield" name="last_name" />
				</div>
				<div class="field">
					<input value="<?php _e('Email...','booked'); ?>" title="<?php _e('Email...','booked'); ?>" type="email" class="large textfield" name="email" />
				</div>
			</div>
			
			<?php if ($guest_booking): ?>
			
				<div class="condition-block customer_type" id="condition-guest">
					<div class="field">
						<input value="<?php _e('Name...','booked'); ?>" title="<?php _e('First name...','booked'); ?>" type="text" class="large textfield" name="guest_name" />
					</div>
					<div class="field">
						<input value="<?php _e('Email...','booked'); ?>" title="<?php _e('Email...','booked'); ?>" type="email" class="large textfield" name="guest_email" />
					</div>
				</div>
			
			<?php endif; ?>

			<?php booked_custom_fields($calendar_id); ?>

			<hr>

			<input type="hidden" name="action" value="add_appt" />
			<input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />

			<div class="field">
				<input type="submit" class="button button-primary" value="<?php _e('Create Appointment','booked'); ?>">
				<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
			</div>

		</form>

		<?php echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';
		exit;

	}

	/*
	Load the Custom Time Slots List
	*/
	if (isset($_POST['load']) && $_POST['load'] == 'custom_timeslots_list' && isset($_POST['json_array']))
	{

		$timeslots = json_decode(stripslashes($_POST['json_array']),true);

		if (!empty($timeslots)):

			echo '<div class="cts-header"><span class="slotsTitle">'.__('Slots Available','booked').'</span>'.__('Time Slot','booked').'</div>';

			foreach ($timeslots as $timeslot => $count):

				$time = explode('-',$timeslot);
				$time_format = get_option('time_format');

				echo '<span class="timeslot" data-timeslot="'.$timeslot.'">';
				echo '<span class="slotsBlock"><span class="changeCount minus" data-count="-1"><i class="fa fa-minus-circle"></i></span><span class="count"><em>'.$count.'</em> ' . _n('slot','slots',$count,'booked') . '</span><span class="changeCount add" data-count="1"><i class="fa fa-plus-circle"></i></span></span>';

				if ($time[0] == '0000' && $time[1] == '2400'):
					echo '<span class="start"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;' . strtoupper(__('All day','booked')) . '</span>';
				else :
					echo '<span class="start"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;' . date($time_format,strtotime('2014-01-01 '.$time[0])) . '</span> &ndash; <span class="end">' . date($time_format,strtotime('2014-01-01 '.$time[1])) . '</span>';
				endif;

				echo '<span class="delete"><i class="fa fa-remove"></i></span>';
				echo '</span>';

			endforeach;

			echo '</div>';

		endif;

		exit;
	}


}