<?php

if (!shortcode_exists('booked-calendar')) {
	add_shortcode('booked-calendar', 'booked_calendar_shortcode');
}


/* CALENDAR SWITCHER SHORTCODE */
class BookedShortcodes {

	function __construct(){
		add_shortcode('booked-calendar-switcher', array($this, 'booked_calendar_switcher_shortcode') );
		add_shortcode('booked-calendar', array($this, 'booked_calendar_shortcode') );
		add_shortcode('booked-appointments', array($this, 'booked_appointments_shortcode') );
		add_shortcode('booked-login', array($this, 'booked_login_form') );
	}

	/* CALENDAR SWITCHER SHORTCODE */
    public function booked_calendar_switcher_shortcode( $attrs ){

		if( $attrs ){
			extract( $attrs );
		}

		$rand = rand(0000000,9999999);

		$args = array(
			'taxonomy'		=> 'booked_custom_calendars',
			'hide_empty'	=> 0,
			'echo'			=> 0,
			'class'			=> 'booked_calendar_chooser',
			'id'			=> 'booked_calendar_chooser_'.$rand,
			'name'			=> 'booked_calendar_chooser_'.$rand
		);

		if( isset($id) ){
			$args['include'] = wp_parse_id_list( $id );
			$args['orderby'] = 'include';
		}

		if (!get_option('booked_hide_default_calendar')): $args['show_option_all'] = __('Default Calendar','booked'); endif;

		return str_replace( "\n", '', wp_dropdown_categories( $args ) );

	}

	/* CALENDAR SHORTCODE */
	public function booked_calendar_shortcode($atts, $content = null){

		$atts = shortcode_atts(
			array(
				'size' => 'large',
				'calendar' => false,
				'year' => false,
				'month' => false,
				'switcher' => false
			), $atts );

		ob_start();

		$atts = apply_filters('booked_calendar_shortcode_atts', $atts );
		$rand = rand(0000000,9999999);

		echo '<div class="booked-calendar-shortcode-wrap">';

			echo '<div id="data-ajax-url">'.home_url().'/</div>';

			if ($atts['switcher']):
				$args = array(
					'taxonomy'		=> 'booked_custom_calendars',
					'hide_empty'	=> 0,
					'echo'			=> 0,
					'id'			=> 'booked_calendar_chooser_'.$rand,
					'name'			=> 'booked_calendar_chooser_'.$rand,
					'class'			=> 'booked_calendar_chooser',
					'selected'		=> $atts['calendar'],
					'orderby'		=> 'name',
					'order'			=> 'ASC'
				);
				if (!get_option('booked_hide_default_calendar')): $args['show_option_all'] = __('Default Calendar','booked'); endif;
				echo '<div class="booked-calendarSwitcher"><p><i class="fa fa-calendar"></i>' . str_replace( "\n", '', wp_dropdown_categories( $args ) ) . '</p></div>';
			endif;

			if (get_option('booked_hide_default_calendar') && !$atts['calendar']):
				$calendars = get_terms('booked_custom_calendars',array('orderby'=>'name','order'=>'ASC'));
				$atts['calendar'] = $calendars[0]->term_id;
			endif;

			echo '<div class="booked-calendar-wrap '.$atts['size'].'">';
				booked_fe_calendar($atts['year'],$atts['month'],$atts['calendar']);
			echo '</div>';

		echo '</div>';

		wp_reset_postdata();

		return ob_get_clean();

	}

	/* APPOINTMENTS SHORTCODE */
	public function booked_appointments_shortcode($atts = null, $content = null) {

		ob_start();

		if (is_user_logged_in()):

			global $current_user;

			//get_currentuserinfo();
			$current_user = wp_get_current_user();

			$my_id = $current_user->ID;

			$historic = isset($atts['historic']) && $atts['historic'] ? true : false;

			$time_format = get_option('time_format');
			$date_format = get_option('date_format');
			$appointments_array = booked_user_appointments($my_id,false,$time_format,$date_format,$historic);
			$total_appts = count($appointments_array);
			$appointment_default_status = get_option('booked_new_appointment_default','draft');

			if (!isset($atts['remove_wrapper'])): echo '<div id="booked-profile-page" class="booked-shortcode">'; endif;

				echo '<div class="booked-profile-appt-list">';

					if ($historic):
						echo '<h4><span class="count">' . number_format($total_appts) . '</span> ' . _n('Past Appointment','Past Appointments',$total_appts,'booked') . '</h4>';
					else:
						echo '<h4><span class="count">' . number_format($total_appts) . '</span> ' . _n('Upcoming Appointment','Upcoming Appointments',$total_appts,'booked') . '</h4>';
					endif;

					foreach($appointments_array as $appt):

						$today = date_i18n($date_format);
						$date_display = date_i18n($date_format,$appt['timestamp']);
						if ($date_display == $today){
							$date_display = __('Today','booked');
							$day_name = '';
						} else {
							$day_name = date_i18n('l',$appt['timestamp']).', ';
						}

						$date_to_convert = date('F j, Y',$appt['timestamp']);

						$cf_meta_value = get_post_meta($appt['post_id'], '_cf_meta_value',true);

						$timeslots = explode('-',$appt['timeslot']);
						$time_start = date($time_format,strtotime($timeslots[0]));
						$time_end = date($time_format,strtotime($timeslots[1]));

						$appt_date_time = strtotime($date_to_convert.' '.date('H:i:s',strtotime($timeslots[0])));
						$current_timestamp = current_time('timestamp');

						$google_date_startend = date('Ymd',$appt['timestamp']);
						$google_time_start = date('Hi',strtotime($timeslots[0]));
						$google_time_end = date('Hi',strtotime($timeslots[1]));

						$cancellation_buffer = get_option('booked_cancellation_buffer',0);

						if ($cancellation_buffer):
							if ($cancellation_buffer < 1){
								$time_type = 'minutes';
								$time_count = $cancellation_buffer * 60;
							} else {
								$time_type = 'hours';
								$time_count = $cancellation_buffer;
							}
							$buffered_timestamp = strtotime('+'.$time_count.' '.$time_type,$current_timestamp);
							$date_to_compare = $buffered_timestamp;
						else:
							$date_to_compare = current_time('timestamp');
						endif;

						if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
							$timeslotText = __('All day','booked');
							$google_date_startend_end = date('Ymd',strtotime(date('Y-m-d',$appt['timestamp']) . '+ 1 Day'));
							$google_time_end = '0000';
						else :
							$timeslotText = (!get_option('booked_hide_end_times') ? __('from','booked').' ' : __('at','booked').' ') . $time_start . (!get_option('booked_hide_end_times') ? ' ' . __('to','booked').' '.$time_end : '');
							$google_date_startend_end = $google_date_startend;
						endif;

						$status = ($appt['status'] == 'draft' ? __('pending','booked') : __('approved','booked'));
						$status_class = $appt['status'] == 'draft' ? 'pending' : 'approved';

						echo '<span class="appt-block bookedClearFix '.(!$historic ? $status_class : 'approved').'" data-appt-id="'.$appt['post_id'].'">';
							if (!$historic):
								if ($appointment_default_status !== 'publish'):
									echo '<span class="status-block">'.($status_class == 'pending' ? '<i class="fa fa-circle-o"></i>' : '<i class="fa fa-check-circle"></i>').'&nbsp;&nbsp;'.$status.'</span>';
								endif;
							endif;
							echo (!empty($appt['calendar_id']) ? '<div class="calendar-name"><strong>'.__('Calendar').':</strong> '.$appt['calendar_id'][0]->name.'</div>' : '');
							echo '<i class="fa fa-calendar"></i>&nbsp;&nbsp;<strong>'.$day_name.$date_display.'</strong><br><i class="fa fa-clock-o"></i>&nbsp;&nbsp;' . $timeslotText;

							do_action('booked_shortcode_appointments_additional_information', $appt['post_id']);

							echo ($cf_meta_value ? '<br><i class="fa fa-info-circle"></i>&nbsp;&nbsp;<a href="#" class="booked-show-cf">'.__('Additional information','booked').'</a><div class="cf-meta-values-hidden">'.$cf_meta_value.'</div>' : '');

							if (!$historic):
								if ($appt_date_time >= $date_to_compare):
									echo '<div class="booked-cal-buttons">';
										if (!get_option('booked_hide_google_link',false)): echo '<a href="//www.google.com/calendar/render?action=TEMPLATE&text='.urlencode(sprintf(__('Appointment with %s','booked'),get_bloginfo('name'))).'&dates='.$google_date_startend.'T'.$google_time_start.'00/'.$google_date_startend_end.'T'.$google_time_end.'00&details=&location=&sf=true&output=xml" target="_blank" rel="nofollow" class="google-cal-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;'.__('Google Calendar','booked').'</a>'; endif;

										if ( apply_filters('booked_shortcode_appointments_allow_cancel', true, $appt['post_id']) && !get_option('booked_dont_allow_user_cancellations',false) ) {
											if ( $appt_date_time >= $date_to_compare ) { echo '<a href="#" data-appt-id="'.$appt['post_id'].'" class="cancel">'.__('Cancel','booked').'</a>'; }
										}

										do_action('booked_shortcode_appointments_buttons', $appt['post_id']);
									echo '</div>';
								endif;
							endif;

						echo '</span>';

					endforeach;

				echo '</div>';


			if (!isset($atts['remove_wrapper'])): echo '</div>'; endif;

			wp_reset_postdata();

		else :

			return '<p>'.__('Please log in to view your upcoming appointments.','booked').'</p>';

		endif;

		return ob_get_clean();

	}

	/* LOGIN SHORTCODE */
	public function booked_login_form( $atts, $content = null ) {

		global $post;

		if (!is_user_logged_in()) {

			ob_start();

			?><div id="booked-profile-page">

				<div id="booked-page-form">

					<ul class="booked-tabs login bookedClearFix">
						<li<?php if ( !isset($_POST['submit'] ) ) { ?> class="active"<?php } ?>><a href="#login"><i class="fa fa-user"></i><?php _e('Login','booked'); ?></a></li>
						<?php if (get_option('users_can_register')): ?><li<?php if ( isset($_POST['submit'] ) ) { ?> class="active"<?php } ?>><a href="#register"><i class="fa fa-edit"></i><?php _e('Register','booked'); ?></a></li><?php endif; ?>
						<li><a href="#forgot"><i class="fa fa-question"></i><?php _e('Forgot your password?','booked'); ?></a></li>
					</ul>

					<div id="profile-login" class="booked-tab-content">

						<?php if (isset($reset) && $reset == true) { ?>

							<p class="booked-form-notice">
							<strong><?php _e('Success!','booked'); ?></strong><br />
							<?php _e('Check your email to reset your password.','booked'); ?>
							</p>

						<?php } ?>

						<?php $login_redirect = get_option('booked_login_redirect_page') ? get_option('booked_login_redirect_page') : $post->ID; ?>

						<div class="booked-form-wrap bookedClearFix">
							<div class="booked-custom-error"><?php _e('Both fields are required to log in.','booked'); ?></div>
							<?php if (isset($_GET['loginfailed'])): ?><div class="booked-custom-error not-hidden"><?php _e('Sorry, those login credentials are incorrect.','booked'); ?></div><?php endif; ?>
							<?php echo wp_login_form( array( 'echo' => false, 'redirect' => get_the_permalink($login_redirect) ) ); ?>
						</div>
					</div>

					<?php if (get_option('users_can_register')): ?>

					<div id="profile-register" class="booked-tab-content">
						<div class="booked-form-wrap bookedClearFix">

							<?php if ( isset($_POST['submit'] ) ) {

						        // sanitize user form input
						        global $username, $first_name, $last_name, $password, $email;

						        $first_name =   sanitize_user( $_POST['first_name'] );
						        $last_name 	=   sanitize_user( $_POST['last_name'] );
						        $password 	= 	wp_generate_password();
						        $email      =   sanitize_email( $_POST['email'] );

						        if (isset($_POST['captcha_word'])):
						        	$captcha_word = strtolower($_POST['captcha_word']);
									$captcha_code = strtolower($_POST['captcha_code']);
						        else :
						        	$captcha_word = false;
									$captcha_code = false;
						        endif;

						        if ($last_name): $username = $first_name.$last_name; else : $username = $first_name; endif;
								$username = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username));
								$errors = booked_registration_validation($username,$email,$captcha_word,$captcha_code);

							    if (!empty($errors)):
									$rand = rand(111,999);
									if ($last_name): $username = $first_name.$last_name.'_'.$rand; else : $username = $first_name.'_'.$rand; endif;
									$username = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($username));
									$errors = booked_registration_validation($username,$email,$captcha_word,$captcha_code);
								endif;

								if (empty($errors)):
						        	$registration_complete = booked_complete_registration();
						        else :
						        	$registration_complete = 'error';
						        endif;

						    } else {

							    $registration_complete = false;

						    }

						    if ($registration_complete && $registration_complete != 'error'){

							    echo $registration_complete;

						    } else {

						    	if ($registration_complete == 'error'){
							    	?><div class="booked-custom-error" style="display:block"><?php echo implode('<br>', $errors); ?></div><?php
						    	}

							    $first_name = (isset($_POST['first_name']) ? $_POST['first_name'] : '');
							    $last_name = (isset($_POST['last_name']) ? $_POST['last_name'] : '');
								$email = (isset($_POST['email']) ? $_POST['email'] : '');

								booked_registration_form($first_name,$last_name,$email);

						    }
							?>

						</div>
					</div>

					<?php endif; ?>

					<div id="profile-forgot" class="booked-tab-content">
						<div class="booked-form-wrap bookedClearFix">
							<div class="booked-custom-error"><?php _e('A username or email address is required to reset your password.','booked'); ?></div>
							<form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
								<p class="username">
									<label for="user_login" class="hide"><?php _e('Username or Email','booked'); ?></label>
									<input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
								</p>

								<?php do_action('login_form', 'resetpass'); ?>
								<input type="submit" name="user-submit" value="<?php _e('Reset my password','booked'); ?>" class="user-submit button-primary" tabindex="1002" />
								<input type="hidden" name="redirect_to" value="<?php the_permalink(); ?>?reset=true" />
								<input type="hidden" name="user-cookie" value="1" />

							</form>
						</div>
					</div>
				</div><!-- END #booked-page-form -->

			</div><?php

			$content = ob_get_clean();
		}

		return $content;

	}

}

new BookedShortcodes;