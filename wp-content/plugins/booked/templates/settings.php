<div class="booked-settings-wrap wrap">

	<?php settings_errors(); ?>

	<div class="topSavingState savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;<?php _e('Updating, please wait...','booked'); ?></div>

	<div class="booked-settings-title"><?php _e('Appointment Settings','booked'); ?></div>

	<div id="booked-admin-panel-container">

		<div id="data-ajax-url"><?php echo get_admin_url(); ?></div>
		
		<?php $booked_settings_tabs = array(
			array(
				'access' => 'admin',
				'slug' => 'general',
				'content' => '<i class="fa fa-gear"></i>&nbsp;&nbsp;'.__('General','booked')),
			array(
				'access' => 'admin',
				'slug' => 'user-emails',
				'content' => '<i class="fa fa-envelope"></i>&nbsp;&nbsp;'.__('User Emails','booked')),
			array(
				'access' => 'admin',
				'slug' => 'admin-emails',
				'content' => '<i class="fa fa-envelope-o"></i>&nbsp;&nbsp;'.__('Admin Emails','booked')),
			array(
				'access' => 'agent',
				'slug' => 'defaults',
				'content' => '<span class="savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;</span><i class="fa fa-clock-o"></i>&nbsp;&nbsp;'.__('Default Time Slots','booked')),
			array(
				'access' => 'agent',
				'slug' => 'custom-timeslots',
				'content' => '<span class="savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;</span><i class="fa fa-clock-o"></i>&nbsp;&nbsp;'.__('Custom Time Slots','booked')),
			array(
				'access' => 'agent',
				'slug' => 'custom-fields',
				'content' => '<i class="fa fa-pencil"></i>&nbsp;&nbsp;'.__('Custom Fields','booked')),
			array(
				'access' => 'admin',
				'slug' => 'shortcodes',
				'content' => '<i class="fa fa-code"></i>&nbsp;&nbsp;'.__('Shortcodes','booked')),
		);
		
		$tab_counter = 1;
			
		foreach($booked_settings_tabs as $tab_data):
			if ($tab_data['access'] == 'admin' && current_user_can('manage_options') || $tab_data['access'] == 'agent'):
				if ($tab_counter == 1): ?><ul class="booked-admin-tabs bookedClearFix"><?php endif;
				?><li<?php if ($tab_counter == 1): ?> class="active"<?php endif; ?>><a href="#<?php echo $tab_data['slug']; ?>"><?php echo $tab_data['content']; ?></a></li><?php
				$tab_counter++;
			endif;
		endforeach;
		
		?></ul>

		<div class="form-wrapper">
			
			<?php foreach($booked_settings_tabs as $tab_data):
				
				if ($tab_data['access'] == 'admin' && current_user_can('manage_options') || $tab_data['access'] == 'agent'):
				
					switch ($tab_data['slug']):
					
						case 'general': ?>
						
							<form action="options.php" class="booked-settings-form" method="post">

								<?php settings_fields('booked_plugin-group'); ?>
				
								<div id="booked-general" class="tab-content">
				
									<?php
				
									if (!is_plugin_active('booked-woocommerce-payments/booked-woocommerce-payments.php')): ?>
										
										<div class="section-row">
											<div class="section-head">
												<?php $section_title = __('Booking Type', 'booked'); ?>
												<h3><?php echo esc_attr($section_title); ?></h2>
												<p><?php _e('You have the option to choose between "Registered" and "Guest" booking. Registered booking will require all appointments to be booked by a registered user (default). Guest booking will allow anyone with a name and email address to book an appointment.','booked'); ?></p>
					
												<?php $option_name = 'booked_booking_type';
												$booking_type = get_option($option_name,'registered'); ?>
												<div class="select-box">
													<select data-condition="booking_type" name="<?php echo $option_name; ?>">
														<option value="registered"<?php echo ($booking_type == 'registered' ? ' selected="selected"' : ''); ?>><?php _e('Registered Booking','booked'); ?></option>
														<option value="guest"<?php echo ($booking_type == 'guest' ? ' selected="selected"' : ''); ?>><?php _e('Guest Booking','booked'); ?></option>
													</select>
												</div><!-- /.select-box -->
											</div><!-- /.section-body -->
										</div><!-- /.section-row -->
				
									<?php else:
									
										update_option('booked_booking_type','registered');
										$booking_type = 'registered';
										
									endif; ?>
									
									<?php if (!is_plugin_active('booked-woocommerce-payments/booked-woocommerce-payments.php')): ?>
										<div class="condition-block booking_type<?php if ($booking_type == 'registered'): ?> default<?php endif; ?>" id="condition-registered">
									<?php endif; ?>
				
										<div class="section-row">
											<div class="section-head">
												<?php $section_title = __('Profile Page', 'booked'); ?>
												<h3><?php echo esc_attr($section_title); ?></h2>
												<p><?php _e('Create a page that includes the <strong>[booked-login]</strong> shortcode for your profile template then choose it from this dropdown.','booked'); ?><br />
												<?php _e('Or instead of this page, you can use the <strong>[booked-profile]</strong> shortcode to display the Profile content anywhere.','booked'); ?></p>
					
												<?php $option_name = 'booked_profile_page';
					
												$pages = get_posts(array(
													'post_type' => 'page',
													'orderby'	=> 'name',
													'order'		=> 'asc',
													'posts_per_page' => -1
												));
					
												$selected_value = get_option($option_name); ?>
												<div class="select-box">
													<select name="<?php echo $option_name; ?>">
														<option value=""><?php _e('Choose a page to use for profile page...','booked'); ?></option>
														<?php if(!empty($pages)) :
															foreach($pages as $p) :
																$entry_id = $p->ID;
																$entry_title = get_the_title($entry_id); ?>
																<option value="<?php echo $entry_id; ?>"<?php echo ($selected_value == $entry_id ? ' selected="selected"' : ''); ?>><?php echo $entry_title; ?></option>
															<?php endforeach;
					
														endif; ?>
													</select>
												</div><!-- /.select-box -->
											</div><!-- /.section-body -->
										</div><!-- /.section-row -->
										
										<div class="section-row cf">
											<div class="section-head">
					
												<h3><?php _e('Profile Options', 'booked'); ?></h3><?php // TODO - WIP ?>
					
												<br>
												
												<?php $option_name = 'booked_disable_avatar';
												$current_value = get_option($option_name,false); ?>
					
												<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $current_value ? ' checked="checked"' : ''; ?> type="checkbox">
												<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Disable "Avatar"','booked'); ?></label><br><br>
												
												<?php $option_name = 'booked_disable_website';
												$current_value = get_option($option_name,false); ?>
					
												<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $current_value ? ' checked="checked"' : ''; ?> type="checkbox">
												<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Disable "Website"','booked'); ?></label><br><br>
												
												<?php $option_name = 'booked_disable_bio';
												$current_value = get_option($option_name,false); ?>
					
												<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $current_value ? ' checked="checked"' : ''; ?> type="checkbox">
												<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Disable "Short Bio"','booked'); ?></label>
					
											</div>
										</div><!-- /.section-row -->
					
										<div class="section-row">
											<div class="section-head">
												<?php $section_title = __('Login Redirect', 'booked'); ?>
												<h3><?php echo esc_attr($section_title); ?></h2>
												<p><?php _e('If you would like the login form to redirect somewhere else (instead of reloading the same page), you can choose a page here.','booked'); ?></p>
					
												<?php $option_name = 'booked_login_redirect_page';
					
												$pages = get_posts(array(
													'post_type' => 'page',
													'orderby'	=> 'name',
													'order'		=> 'asc',
													'posts_per_page' => -1
												));
					
												$selected_value = get_option($option_name); ?>
												<div class="select-box">
													<select name="<?php echo $option_name; ?>">
														<option value=""><?php _e('Redirect to the same page','booked'); ?></option>
														<?php if(!empty($pages)) :
															foreach($pages as $p) :
																$entry_id = $p->ID;
																$entry_title = get_the_title($entry_id); ?>
																<option value="<?php echo $entry_id; ?>"<?php echo ($selected_value == $entry_id ? ' selected="selected"' : ''); ?>><?php echo $entry_title; ?></option>
															<?php endforeach;
					
														endif; ?>
													</select>
												</div><!-- /.select-box -->
											</div><!-- /.section-body -->
										</div><!-- /.section-row -->
				
									<?php if (!is_plugin_active('booked-woocommerce-payments/booked-woocommerce-payments.php')): ?>
										</div>
									<?php endif; ?>
				
									<div class="section-row">
										<div class="section-head">
											<?php $section_title = __('Appointment Booking Redirect', 'booked'); ?>
											<h3><?php echo esc_attr($section_title); ?></h2>
											<p><?php _e('If you would like to direct your users to a custom page after a successful appointment booking (instead of going to the selected Profile Page above), you can choose a page here.','booked'); ?></p>
				
											<?php $option_name = 'booked_appointment_success_redirect_page';
				
											$pages = get_posts(array(
												'post_type' => 'page',
												'orderby'	=> 'name',
												'order'		=> 'asc',
												'posts_per_page' => -1
											));
				
											$selected_value = get_option($option_name); ?>
											<div class="select-box">
												<select name="<?php echo $option_name; ?>">
													<option value=""><?php _e('Default','booked'); ?></option>
													<?php if(!empty($pages)) :
														foreach($pages as $p) :
															$entry_id = $p->ID;
															$entry_title = get_the_title($entry_id); ?>
															<option value="<?php echo $entry_id; ?>"<?php echo ($selected_value == $entry_id ? ' selected="selected"' : ''); ?>><?php echo $entry_title; ?></option>
														<?php endforeach;
				
													endif; ?>
												</select>
											</div><!-- /.select-box -->
										</div><!-- /.section-body -->
									</div><!-- /.section-row -->
				
									<div class="section-row">
										<div class="section-head">
											<?php $section_title = __('Time Slot Intervals', 'booked'); ?>
											<h3><?php echo esc_attr($section_title); ?></h2>
											<p><?php _e('Choose the intervals you need for your appointment time slots. This will only affect the way default time slots are entered.','booked'); ?></p>
				
											<?php $option_name = 'booked_timeslot_intervals';
											$selected_value = get_option($option_name);
				
											$interval_options = array(
												'120' 				=> __('Every 2 hours','booked'),
												'60' 				=> __('Every 1 hour','booked'),
												'30' 				=> __('Every 30 minutes','booked'),
												'15' 				=> __('Every 15 minutes','booked'),
												'10' 				=> __('Every 10 minutes','booked'),
												'5' 				=> __('Every 5 minutes','booked')
											); ?>
				
											<div class="select-box">
												<select name="<?php echo $option_name; ?>">
													<?php foreach($interval_options as $current_value => $option_title):
														echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
													endforeach; ?>
												</select>
											</div><!-- /.select-box -->
										</div><!-- /.section-body -->
									</div><!-- /.section-row -->
				
									<div class="section-row">
										<div class="section-head">
											<?php $section_title = __('Appointment Buffer', 'booked'); ?>
											<h3><?php echo esc_attr($section_title); ?></h2>
											<p><?php _e('To prevent appointments from getting booked too close to the current date and/or time, you can set an appointment buffer. Available appointments time slots will be pushed up to a new date and time depending on which buffer amount you choose below.','booked'); ?></p>
				
											<?php $option_name = 'booked_appointment_buffer';
											$selected_value = get_option($option_name);
				
											$interval_options = array(
												'0' 				=> __('No buffer','booked'),
												'1' 				=> __('1 hour','booked'),
												'2' 				=> __('2 hours','booked'),
												'3' 				=> __('3 hours','booked'),
												'4' 				=> __('4 hours','booked'),
												'5' 				=> __('5 hours','booked'),
												'6' 				=> __('6 hours','booked'),
												'12' 				=> __('12 hours','booked'),
												'24' 				=> __('24 hours','booked'),
												'48' 				=> __('2 days','booked'),
												'72' 				=> __('3 days','booked'),
												'96' 				=> __('5 days','booked'),
												'144' 				=> __('6 days','booked'),
												'168' 				=> __('1 week','booked'),
												'336' 				=> __('2 weeks','booked'),
												'672' 				=> __('4 weeks','booked'),
											); ?>
				
											<div class="select-box">
												<select name="<?php echo $option_name; ?>">
													<?php foreach($interval_options as $current_value => $option_title):
														echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
													endforeach; ?>
												</select>
											</div><!-- /.select-box -->
										</div><!-- /.section-body -->
									</div><!-- /.section-row -->
				
									<div class="section-row">
										<div class="section-head">
											<?php $section_title = __('Cancellation Buffer', 'booked'); ?>
											<h3><?php echo esc_attr($section_title); ?></h2>
											<p><?php _e('To prevent appointments from getting cancelled too close to the appointment time, you can set a cancellation buffer.','booked'); ?></p>
				
											<?php $option_name = 'booked_cancellation_buffer';
											$selected_value = get_option($option_name);
				
											$interval_options = array(
												'0' 				=> __('No buffer','booked'),
												'0.25' 				=> __('15 minutes','booked'),
												'0.50' 				=> __('30 minutes','booked'),
												'0.75' 				=> __('45 minutes','booked'),
												'1' 				=> __('1 hour','booked'),
												'2' 				=> __('2 hours','booked'),
												'3' 				=> __('3 hours','booked'),
												'4' 				=> __('4 hours','booked'),
												'5' 				=> __('5 hours','booked'),
												'6' 				=> __('6 hours','booked'),
												'12' 				=> __('12 hours','booked'),
												'24' 				=> __('24 hours','booked'),
												'48' 				=> __('2 days','booked'),
												'72' 				=> __('3 days','booked'),
												'96' 				=> __('5 days','booked'),
												'144' 				=> __('6 days','booked'),
												'168' 				=> __('1 week','booked'),
												'336' 				=> __('2 weeks','booked'),
												'672' 				=> __('4 weeks','booked'),
											); ?>
				
											<div class="select-box">
												<select name="<?php echo $option_name; ?>">
													<?php foreach($interval_options as $current_value => $option_title):
														echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
													endforeach; ?>
												</select>
											</div><!-- /.select-box -->
										</div><!-- /.section-body -->
									</div><!-- /.section-row -->
				
									<div class="section-row">
										<div class="section-head">
											<?php $section_title = __('Appointment Limit', 'booked'); ?>
											<h3><?php echo esc_attr($section_title); ?></h2>
											<p><?php _e('To prevent users from booking too many appointments, you can set an appointment limit.','booked'); ?></p>
				
											<?php $option_name = 'booked_appointment_limit';
											$selected_value = get_option($option_name);
				
											$interval_options = array(
												'0' 				=> __('No limit','booked'),
												'1' 				=> __('1 appointment','booked'),
												'2' 				=> __('2 appointments','booked'),
												'3' 				=> __('3 appointments','booked'),
												'4' 				=> __('4 appointments','booked'),
												'5' 				=> __('5 appointments','booked'),
												'6' 				=> __('6 appointments','booked'),
												'7' 				=> __('7 appointments','booked'),
												'8' 				=> __('8 appointments','booked'),
												'9' 				=> __('9 appointments','booked'),
												'10' 				=> __('10 appointments','booked'),
												'15' 				=> __('15 appointments','booked'),
												'20' 				=> __('20 appointments','booked'),
												'25' 				=> __('25 appointments','booked'),
												'50' 				=> __('50 appointments','booked'),
											); ?>
				
											<div class="select-box">
												<select name="<?php echo $option_name; ?>">
													<?php foreach($interval_options as $current_value => $option_title):
														echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
													endforeach; ?>
												</select>
											</div><!-- /.select-box -->
										</div><!-- /.section-body -->
									</div><!-- /.section-row -->
				
									<div class="section-row">
										<div class="section-head">
											<?php $section_title = __('New Appointment Default', 'booked'); ?>
											<h3><?php echo esc_attr($section_title); ?></h2>
											<p><?php _e('Would you like your appointment requests to go into a pending list or should they be approved immediately?','booked'); ?></p>
				
											<?php $option_name = 'booked_new_appointment_default';
											$selected_value = get_option($option_name);
				
											$interval_options = array(
												'draft' 	=> __('Set as Pending','booked'),
												'publish' 	=> __('Approve Immediately','booked')
											); ?>
				
											<div class="select-box">
												<select name="<?php echo $option_name; ?>">
													<?php foreach($interval_options as $current_value => $option_title):
														echo '<option value="'.$current_value.'"' . ($selected_value == $current_value ? ' selected' : ''). '>' . $option_title . '</option>';
													endforeach; ?>
												</select>
											</div><!-- /.select-box -->
										</div><!-- /.section-body -->
									</div><!-- /.section-row -->
				
									<div class="section-row cf">
										<div class="section-head">
				
											<h3><?php _e('Other Options', 'booked'); ?></h3><?php // TODO - WIP ?>
				
											<br>
											
											<?php $option_name = 'booked_hide_default_calendar';
											$hide_default_calendar_button = get_option($option_name,false); ?>
				
											<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $hide_default_calendar_button ? ' checked="checked"' : ''; ?> type="checkbox">
											<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Hide "Default" calendar in the front-end calendar dropdown','booked'); ?></label><br><br>
											
											<?php $option_name = 'booked_hide_weekends';
											$hide_weekends = get_option($option_name,false); ?>
				
											<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $hide_weekends ? ' checked="checked"' : ''; ?> type="checkbox">
											<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Hide weekends on calendars','booked'); ?></label><br><br>
											
											<?php $option_name = 'booked_hide_unavailable_timeslots';
											$hide_unavailable_timeslots = get_option($option_name,false); ?>
				
											<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $hide_unavailable_timeslots ? ' checked="checked"' : ''; ?> type="checkbox">
											<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Hide unavailable time slots (on the front-end)','booked'); ?></label><br><br>
											
											<?php $option_name = 'booked_dont_allow_user_cancellations';
											$dont_allow_user_cancellations = get_option($option_name,false); ?>
				
											<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $dont_allow_user_cancellations ? ' checked="checked"' : ''; ?> type="checkbox">
											<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Do not allow users to cancel their own appointments.','booked'); ?></label><br><br>
				
											<?php $option_name = 'booked_hide_google_link';
											$hide_google_calendar_button = get_option($option_name,false); ?>
				
											<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $hide_google_calendar_button ? ' checked="checked"' : ''; ?> type="checkbox">
											<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Hide "+ Google Calender" button in appointment list','booked'); ?></label><br><br>
				
											<?php $option_name = 'booked_hide_end_times';
											$hide_hide_end_times = get_option($option_name,false); ?>
				
											<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $hide_hide_end_times ? ' checked="checked"' : ''; ?> type="checkbox">
											<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Hide end times on front-end (show only start times)','booked'); ?></label><br><br>
											
											<?php $option_name = 'booked_redirect_non_admins';
											$hide_hide_end_times = get_option($option_name,false); ?>
				
											<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>"<?php echo $hide_hide_end_times ? ' checked="checked"' : ''; ?> type="checkbox">
											<label class="checkbox-radio-label" for="<?php echo $option_name; ?>"><?php _e('Redirect users (except Admins and Booking Agents) from the "/wp-admin/" URL.','booked'); ?></label>
				
										</div>
									</div><!-- /.section-row -->
				
									<div class="section-row">
										<div class="section-head">
											<?php $section_title = __('Front-End Color Settings', 'booked'); ?>
											<h3><?php echo esc_attr($section_title); ?></h3><?php // TODO - WIP ?>
										</div><!-- /.section-head -->
										<div class="section-body">
				
											<?php
											$color_options = array(
												array(
													'name' => 'booked_light_color',
													'title' => 'Light Color',
													'val' => get_option('booked_light_color','#365769'),
													'default' => '#365769'
												),
												array(
													'name' => 'booked_dark_color',
													'title' => 'Dark Color',
													'val' => get_option('booked_dark_color','#264452'),
													'default' => '#264452'
				
												),
												array(
													'name' => 'booked_button_color',
													'title' => 'Primary Button Color',
													'val' => get_option('booked_button_color','#56C477'),
													'default' => '#56C477'
				
												),
											);
				
											foreach($color_options as $color_option):
				
												echo '<label class="booked-color-label" for="'.$color_option['name'].'">'.$color_option['title'].'</label>';
												echo '<input data-default-color="'.$color_option['default'].'" type="text" name="'.$color_option['name'].'" value="'.$color_option['val'].'" id="'.$color_option['name'].'" class="booked-color-field" />';
				
											endforeach;
											?>
				
										</div><!-- /.section-body -->
									</div>
				
									<div class="section-row submit-section" style="padding:0;">
										<?php @submit_button(); ?>
									</div><!-- /.section-row -->
				
								</div>
				
								<div id="booked-user-emails" class="tab-content">
				
									<div class="section-row">
										<div class="section-head">
											<p><strong style="font-size:17px; line-height:1.7;"><?php _e('If you do not want to send email notifications for any or all of the following actions, you can just delete the text and an email will not be sent.','booked'); ?></strong></p>
										</div>
									</div>
				
									<div class="section-row">
										<div class="section-head"><?php
				
											$option_name = 'booked_email_logo';
											$booked_email_logo = get_option($option_name);
											$section_title = __('Email Content - Logo Image', 'booked'); ?>
				
											<h3><?php echo esc_attr($section_title); ?></h3>
											<p><?php _e('Choose an image for your custom emails. Keep it 600px or less for best results.','booked'); ?></p>
				
											<input id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $booked_email_logo; ?>" type="hidden" />
											<input id="booked_email_logo_button" class="button" name="booked_email_logo_button" type="button" value="Upload Logo" />
				
											<img src="<?php echo $booked_email_logo; ?>" id="booked_email_logo-img">
										</div>
									</div>
				
									<div class="section-row">
										<div class="section-head">
											<?php $option_name = 'booked_registration_email_content';
				
$default_content = 'Hey %name%!

Thanks for registering at '.get_bloginfo('name').'. You can now login to manage your account and appointments using the following credentials:

Username: %username%
Password: %password%

Sincerely,
Your friends at '.get_bloginfo('name');
				
											$email_content_registration = get_option($option_name,$default_content);
											$section_title = __('Email Content - Registration', 'booked'); ?>
				
											<h3><?php echo esc_attr($section_title); ?></h3>
											<p><?php _e('The email content that is sent to the user upon registration (using the Booked registration form). Some tokens you can use:','booked'); ?></p>
											<ul class="cp-list">
												<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
												<li><strong>%email%</strong> &mdash; <?php _e("To display the person's email address.","booked"); ?></li>
												<li><strong>%username%</strong> &mdash; <?php _e("To display the username for login.","booked"); ?></li>
												<li><strong>%password%</strong> &mdash; <?php _e("To display the password for login.","booked"); ?></li>
											</ul><br>
				
											<?php
				
											$subject_var = 'booked_registration_email_subject';
											$subject_default = 'Thank you for registering!';
											$current_subject_value = get_option($subject_var,$subject_default); ?>
				
											<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
											<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_registration; ?></textarea>
										</div>
									</div><!-- /.section-row -->
				
									<div class="section-row" data-controller="cp_fes_controller" data-controlled_by="fes_enabled">
										<div class="section-head">
											<?php $option_name = 'booked_appt_confirmation_email_content';
				
$default_content = 'Hey %name%!

This is just an email to confirm your appointment. For reference, here\'s the appointment information:

Date: %date%
Time: %time%

Sincerely,
Your friends at '.get_bloginfo('name');
				
											$email_content_approval = get_option($option_name,$default_content);
											$section_title = __('Email Content - Appointment Confirmation', 'booked'); ?>
				
											<h3><?php echo esc_attr($section_title); ?></h3>
											<p><?php _e('The email content that is sent to the user upon appointment creation. Some tokens you can use:','booked'); ?></p>
											<ul class="cp-list">
												<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
												<li><strong>%email%</strong> &mdash; <?php _e("To display the person's email address.","booked"); ?></li>
												<li><strong>%calendar%</strong> &mdash; <?php _e("To display the calendar name (if one is used) for this appointment.","booked"); ?></li>
												<li><strong>%date%</strong> &mdash; <?php _e("To display the appointment date.","booked"); ?></li>
												<li><strong>%time%</strong> &mdash; <?php _e("To display the appointment time.","booked"); ?></li>
												<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
											</ul><br>
				
											<?php
				
											$subject_var = 'booked_appt_confirmation_email_subject';
											$subject_default = 'Your appointment confirmation from '.get_bloginfo('name').'.';
											$current_subject_value = get_option($subject_var,$subject_default); ?>
				
											<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
											<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_approval; ?></textarea>
										</div>
									</div><!-- /.section-row -->
				
									<div class="section-row" data-controller="cp_fes_controller" data-controlled_by="fes_enabled">
										<div class="section-head">
											<?php $option_name = 'booked_approval_email_content';
				
$default_content = 'Hey %name%!

The appointment you requested at '.get_bloginfo('name').' has been approved! Here\'s your appointment information:

Date: %date%
Time: %time%

Sincerely,
Your friends at '.get_bloginfo('name');
				
											$email_content_approval = get_option($option_name,$default_content);
											$section_title = __('Email Content - Appointment Approval', 'booked'); ?>
				
											<h3><?php echo esc_attr($section_title); ?></h3>
											<p><?php _e('The email content that is sent to the user upon appointment approval. Some tokens you can use:','booked'); ?></p>
											<ul class="cp-list">
												<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
												<li><strong>%email%</strong> &mdash; <?php _e("To display the person's email address.","booked"); ?></li>
												<li><strong>%calendar%</strong> &mdash; <?php _e("To display the calendar name (if one is used) for this appointment.","booked"); ?></li>
												<li><strong>%date%</strong> &mdash; <?php _e("To display the appointment date.","booked"); ?></li>
												<li><strong>%time%</strong> &mdash; <?php _e("To display the appointment time.","booked"); ?></li>
												<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
											</ul><br>
				
											<?php
				
											$subject_var = 'booked_approval_email_subject';
											$subject_default = 'Your appointment has been approved!';
											$current_subject_value = get_option($subject_var,$subject_default); ?>
				
											<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
											<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_approval; ?></textarea>
										</div>
									</div><!-- /.section-row -->
				
									<div class="section-row" data-controller="cp_fes_controller" data-controlled_by="fes_enabled">
										<div class="section-head">
											<?php $option_name = 'booked_cancellation_email_content';
				
$default_content = 'Hey %name%!

The appointment you requested at '.get_bloginfo('name').' has been cancelled. For reference, here\'s the appointment information:

Date: %date%
Time: %time%

Sincerely,
Your friends at '.get_bloginfo('name');
				
											$email_content_approval = get_option($option_name,$default_content);
											$section_title = __('Email Content - Appointment Cancellation', 'booked'); ?>
				
											<h3><?php echo esc_attr($section_title); ?></h3>
											<p><?php _e('The email content that is sent to the user upon appointment cancellation. Some tokens you can use:','booked'); ?></p>
											<ul class="cp-list">
												<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
												<li><strong>%email%</strong> &mdash; <?php _e("To display the person's email address.","booked"); ?></li>
												<li><strong>%calendar%</strong> &mdash; <?php _e("To display the calendar name (if one is used) for this appointment.","booked"); ?></li>
												<li><strong>%date%</strong> &mdash; <?php _e("To display the appointment date.","booked"); ?></li>
												<li><strong>%time%</strong> &mdash; <?php _e("To display the appointment time.","booked"); ?></li>
												<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
											</ul><br>
				
											<?php
				
											$subject_var = 'booked_cancellation_email_subject';
											$subject_default = 'Your appointment has been cancelled.';
											$current_subject_value = get_option($subject_var,$subject_default); ?>
				
											<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
											<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_approval; ?></textarea>
										</div>
									</div><!-- /.section-row -->
				
									<div class="section-row submit-section" style="padding:0;">
										<?php @submit_button(); ?>
									</div><!-- /.section-row -->
				
								</div><!-- /templates -->
				
								<div id="booked-admin-emails" class="tab-content">
				
									<div class="section-row">
										<div class="section-head">
											<p><strong style="font-size:17px; line-height:1.7;"><?php _e('If you do not want to send email notifications for any or all of the following actions, you can just delete the text and an email will not be sent.','booked'); ?></strong></p>
										</div>
									</div>
									
									<div class="section-row">
										<div class="section-head">
											<?php $section_title = __('Which Administrator or Booking Agent user should receive the notification emails by default?', 'booked'); ?>
											<h3><?php echo esc_attr($section_title); ?></h2>
											<p><?php _e('By default, Booked uses the <strong>Settings > General > E-mail Address</strong> setting. Also, each custom calendar can have their own user notification setting, this is just the default.','booked'); ?></p>
				
											<?php $option_name = 'booked_default_email_user';
				
											$all_users = get_users();
											$allowed_users = array();
											foreach ( $all_users as $user ):
											    $wp_user = new WP_User($user->ID);
											    if ( !in_array( 'subscriber', $wp_user->roles ) ):
											        array_push($allowed_users, $user);
											    endif;
											endforeach;
				
											$selected_value = get_option($option_name); ?>
											<div class="select-box">
												<select name="<?php echo $option_name; ?>">
													<option value=""><?php _e('Choose a default user for notifications','booked'); ?> ...</option>
													<?php if(!empty($allowed_users)) :
														foreach($allowed_users as $u) :
															$user_id = $u->ID;
															$username = $u->data->user_login;
															$email = $u->data->user_email; ?>
															<option value="<?php echo $email; ?>"<?php echo ($selected_value == $email ? ' selected="selected"' : ''); ?>><?php echo $email; ?> (<?php echo $username; ?>)</option>
														<?php endforeach;
				
													endif; ?>
												</select>
											</div><!-- /.select-box -->
										</div><!-- /.section-body -->
									</div><!-- /.section-row -->
				
									<div class="section-row">
										<div class="section-head">
											<?php $option_name = 'booked_admin_appointment_email_content';
				
$default_content = 'You have a new appointment request! Here\'s the appointment information:

Customer: %name%
Date: %date%
Time: %time%

Log into your website here: '.get_admin_url().' to approve this appointment.

(Sent via the '.get_bloginfo('name').' website)';
				
											$email_content_registration = get_option($option_name,$default_content);
											$section_title = __('New Appointment Request', 'booked'); ?>
				
											<h3><?php echo esc_attr($section_title); ?></h3>
											<p><?php _e('The email content that is sent (to the selected admin users above) upon appointment request. Some tokens you can use:','booked'); ?></p>
											<ul class="cp-list">
												<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
												<li><strong>%email%</strong> &mdash; <?php _e("To display the person's email address.","booked"); ?></li>
												<li><strong>%calendar%</strong> &mdash; <?php _e("To display the calendar name (if one is used) for this appointment.","booked"); ?></li>
												<li><strong>%date%</strong> &mdash; <?php _e("To display the appointment date.","booked"); ?></li>
												<li><strong>%time%</strong> &mdash; <?php _e("To display the appointment time.","booked"); ?></li>
												<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
											</ul><br>
				
											<?php
				
											$subject_var = 'booked_admin_appointment_email_subject';
											$subject_default = 'You have a new appointment request!';
											$current_subject_value = get_option($subject_var,$subject_default); ?>
				
											<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
											<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_registration; ?></textarea>
										</div>
									</div><!-- /.section-row -->
				
									<div class="section-row">
										<div class="section-head">
											<?php $option_name = 'booked_admin_cancellation_email_content';
				
$default_content = 'One of your customers has cancelled their appointment. Here\'s the appointment information:

Customer: %name%
Date: %date%
Time: %time%

(Sent via the '.get_bloginfo('name').' website)';
				
											$email_content_registration = get_option($option_name,$default_content);
											$section_title = __('Appointment Cancellation', 'booked'); ?>
				
											<h3><?php echo esc_attr($section_title); ?></h3>
											<p><?php _e('The email content that is sent (to the selected admin users above) upon cancellation. Some tokens you can use:','booked'); ?></p>
											<ul class="cp-list">
												<li><strong>%name%</strong> &mdash; <?php _e("To display the person's name.","booked"); ?></li>
												<li><strong>%email%</strong> &mdash; <?php _e("To display the person's email address.","booked"); ?></li>
												<li><strong>%calendar%</strong> &mdash; <?php _e("To display the calendar name (if one is used) for this appointment.","booked"); ?></li>
												<li><strong>%date%</strong> &mdash; <?php _e("To display the username for login.","booked"); ?></li>
												<li><strong>%time%</strong> &mdash; <?php _e("To display the password for login.","booked"); ?></li>
												<li><strong>%customfields%</strong> &mdash; <?php _e("To display all custom field values associated with this appointment.","booked"); ?></li>
											</ul><br>
				
											<?php
				
											$subject_var = 'booked_admin_cancellation_email_subject';
											$subject_default = 'An appointment has been cancelled.';
											$current_subject_value = get_option($subject_var,$subject_default); ?>
				
											<input name="<?php echo $subject_var; ?>" value="<?php echo $current_subject_value; ?>" type="text" class="field">
											<textarea name="<?php echo $option_name; ?>" class="field large"><?php echo $email_content_registration; ?></textarea>
										</div>
									</div><!-- /.section-row -->
				
									<div class="section-row submit-section" style="padding:0;">
										<?php @submit_button(); ?>
									</div><!-- /.section-row -->
				
								</div><!-- /templates -->
				
							</form>

													
						<?php break;
							
						case 'defaults': ?>
						
							<div id="booked-defaults" class="tab-content">

								<?php
				
								$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
				
								if (!empty($calendars)):
								
									if (booked_user_role() == 'booked_booking_agent'):
						
										global $current_user;
										$calendars = booked_filter_agent_calendars($current_user,$calendars);
										
										if (empty($calendars)):
											$booked_none_assigned = true;
										else:
											$first_calendar = array_slice($calendars, 0, 1);
											$default_calendar_id = array_shift($first_calendar)->term_id;
											$booked_none_assigned = false;
										endif;
									
									else:
										$booked_none_assigned = false;
									endif;
									
									if (!$booked_none_assigned):
				
									?><div id="booked-timeslotsSwitcher"><p>
										<i class="fa fa-calendar"></i><?php
				
										echo '<select name="bookedTimeslotsDisplayed">';
										if (booked_user_role() != 'booked_booking_agent'): echo '<option value="">'.__('Default Time Slots','booked').'</option>'; endif;
				
										foreach($calendars as $calendar):
				
											?><option value="<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></option><?php
				
										endforeach;
				
										echo '</select>';
				
									?></p></div><?php
										
									endif;
				
								endif;
								
								if (booked_user_role() == 'booked_booking_agent' && $booked_none_assigned):
						
									echo '<div style="text-align:center;">';
										echo '<br><br><h3>'.__('There are no calendars assigned to you.','booked').'</h3>';
										echo '<p>'.__('Get in touch with the Administration of this site to get a calendar assigned to you.','booked').'</p>';
									echo '</div>';
									
								else:
				
									?>
					
									<div id="bookedTimeslotsWrap">
										<?php if (booked_user_role() != 'booked_booking_agent'):
											booked_render_timeslots();
										else:
											$first_calendar = reset($calendars);
											booked_render_timeslots($first_calendar->term_id);
										endif; ?>
									</div>
					
									<?php $timeslot_intervals = get_option('booked_timeslot_intervals',60); ?>
					
									<div id="timepickerTemplate">
										<div class="timeslotTabs bookedClearFix">
											<a class="addTimeslotTab active" href="#Single"><?php _e('Single','booked'); ?></a>
											<a class="addTimeslotTab" href="#Bulk"><?php _e('Bulk','booked'); ?></a>
										</div>
										<div class="tsTabContent tsSingle">
											<?php echo booked_render_single_timeslot_form($timeslot_intervals); ?>
										</div>
										<div class="tsTabContent tsBulk">
											<?php echo booked_render_bulk_timeslot_form($timeslot_intervals); ?>
										</div>
									</div>
								
								<?php endif; ?>
				
							</div><!-- /templates -->
													
						<?php break;
							
						case 'custom-timeslots': ?>
						
							<div id="booked-custom-timeslots" class="tab-content">

								<form action="" id="customTimeslots">
				
									<div id="customTimeslotsWrapper">
										<div id="customTimeslotsContainer">
				
											<?php // Any custom time slots saved already?
											$booked_custom_timeslots_encoded = get_option('booked_custom_timeslots_encoded');
											$booked_custom_timeslots_decoded = json_decode($booked_custom_timeslots_encoded,true);
				
											if (!empty($booked_custom_timeslots_decoded)):
				
												$custom_timeslots_array = booked_custom_timeslots_reconfigured($booked_custom_timeslots_decoded);
				
												foreach($custom_timeslots_array as $this_timeslot):
				
													?><div class="booked-customTimeslot">
				
														<?php if (!empty($calendars)):
				
															echo '<select name="booked_custom_calendar_id">';
										
																if (booked_user_role() != 'booked_booking_agent'): echo '<option value="">'.__('Default Calendar','booked').'</option>'; endif;
				
																foreach($calendars as $calendar):
																
																	?><option<?php if ($this_timeslot['booked_custom_calendar_id'] == $calendar->term_id): echo ' selected="selected"'; endif; ?> value="<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></option><?php
				
																endforeach;
				
															echo '</select>';
				
														endif; ?>
				
														<input type="text" placeholder="<?php _e("Start date","booked"); ?>..." class="booked_custom_start_date" name="booked_custom_start_date" value="<?php echo $this_timeslot['booked_custom_start_date']; ?>">
														<input type="text" placeholder="<?php _e("Optional End date","booked"); ?>..." class="booked_custom_end_date" name="booked_custom_end_date" value="<?php echo $this_timeslot['booked_custom_end_date']; ?>">
				
														<?php if (is_array($this_timeslot['booked_this_custom_timelots'])): ?>
															<input type="hidden" name="booked_this_custom_timelots" value="<?php echo htmlentities(stripslashes(json_encode($this_timeslot['booked_this_custom_timelots']))); ?>">
														<?php else : ?>
															<input type="hidden" name="booked_this_custom_timelots" value="<?php echo htmlentities(stripslashes($this_timeslot['booked_this_custom_timelots'])); ?>">
														<?php endif; ?>
				
														<input id="vacationDayCheckbox" name="vacationDayCheckbox" type="checkbox" value="1"<?php if ($this_timeslot['vacationDayCheckbox']): echo ' checked="checked"'; endif; ?>>
														<label for="vacationDayCheckbox"><?php _e('Disable appointments','booked'); ?></label>
				
														<a href="#" class="deleteCustomTimeslot"><i class="fa fa-close"></i></a>
				
														<?php
				
														if (is_array($this_timeslot['booked_this_custom_timelots'])):
															$timeslots = $this_timeslot['booked_this_custom_timelots'];
														else:
															$timeslots = json_decode($this_timeslot['booked_this_custom_timelots'],true);
														endif;
				
														echo '<div class="customTimeslotsList">';
				
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
														endif;
				
														echo '</div>';
				
														?>
				
														<button class="button addSingleTimeslot"><?php _e('+ Single Time Slot','booked'); ?></button>
														<button class="button addBulkTimeslots"><?php _e('+ Bulk Time Slots','booked'); ?></button>
				
													</div><?php
				
												endforeach;
											endif;
				
											?>
				
										</div>
									</div>
				
									<div class="section-row submit-section bookedClearFix" style="padding:0;">
										<button class="button addCustomTimeslot"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Add Date(s)','booked'); ?></button>
										<input id="booked-saveCustomTimeslots" type="button" class="button button-primary saveCustomTimeslots" value="<?php _e('Save Custom Time Slots','booked'); ?>">
										<div class="cts-updater savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;<?php _e('Saving','booked'); ?>...</div>
									</div><!-- /.section-row -->
				
								</form>
				
								<input type="hidden" style="width:100%;" id="custom_timeslots_encoded" name="custom_timeslots_encoded" value="<?php echo htmlentities(stripslashes(stripslashes($booked_custom_timeslots_encoded))); ?>">
				
								<div style="border:1px solid #FFBA00;" class="booked-customTimeslotTemplate">
				
									<?php if (!empty($calendars)):
				
										echo '<select name="booked_custom_calendar_id">';
											if (booked_user_role() != 'booked_booking_agent'): echo '<option value="">'.__('Default Calendar','booked').'</option>'; endif;
				
											foreach($calendars as $calendar):
				
												?><option value="<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></option><?php
				
											endforeach;
				
										echo '</select>';
				
									endif; ?>
				
									<input type="text" placeholder="<?php _e("Start date","booked"); ?>..." class="booked_custom_start_date" name="booked_custom_start_date" value="">
									<input type="text" placeholder="<?php _e("Optional End date","booked"); ?>..." class="booked_custom_end_date" name="booked_custom_end_date" value="">
									<input type="hidden" name="booked_this_custom_timelots" value="">
				
									<input id="vacationDayCheckbox" name="vacationDayCheckbox" type="checkbox" value="1">
									<label for="vacationDayCheckbox"><?php _e('Disable appointments','booked'); ?></label>
				
									<a href="#" class="deleteCustomTimeslot"><i class="fa fa-close"></i></a>
				
									<div class="customTimeslotsList"></div>
				
									<button class="button addSingleTimeslot"><?php _e('+ Single Time Slot','booked'); ?></button>
									<button class="button addBulkTimeslots"><?php _e('+ Bulk Time Slots','booked'); ?></button>
				
								</div>
				
								<div id="booked-customTimePickerTemplates">
									<div class="customSingle">
										<?php echo booked_render_single_timeslot_form($timeslot_intervals,'custom'); ?>
										<button class="button-primary addSingleTimeslot_button"><?php _e('Add','booked'); ?></button>
										<button class="button cancel"><?php _e('Close','booked'); ?></button>
									</div>
									<div class="customBulk">
										<?php echo booked_render_bulk_timeslot_form($timeslot_intervals,'custom'); ?>
										<button class="button-primary addBulkTimeslots_button"><?php _e('Add','booked'); ?></button>
										<button class="button cancel"><?php _e('Close','booked'); ?></button>
									</div>
								</div>
				
							</div>
													
						<?php break;
							
						case 'custom-fields': ?>
						
							<div id="booked-custom-fields" class="tab-content">
								
								<div class="section-row">
									<div class="section-head">
				
										<div class="booked-cf-block">
											
											<?php if (!empty($calendars)):
												
												echo '<div id="booked-cfSwitcher" style="margin:0 0 30px;">';
													echo '<select name="bookedCustomFieldsDisplayed">';
								
														if (booked_user_role() != 'booked_booking_agent'): echo '<option value="">'.__('Default Calendar','booked').'</option>'; endif;
				
														foreach($calendars as $calendar):
														
															?><option value="<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></option><?php
				
														endforeach;
				
													echo '</select>';
												echo '</div>';
			
											endif; ?>
											
											<div id="booked_customFields_Wrap">
											
												<?php if (booked_user_role() != 'booked_booking_agent'):
													booked_render_custom_fields();
												else:
													$first_calendar = reset($calendars);
													booked_render_custom_fields($first_calendar->term_id);
												endif; ?>
											
											</div>
											
										</div>
				
										<ul id="booked-cf-sortable-templates">
				
											<li id="bookedCFTemplate-single-line-text-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
												<small><?php _e('Single Line Text','booked'); ?></small>
												<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
												<input type="text" name="single-line-text-label" value="" placeholder="Enter a label for this field..." />
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li>
											<li id="bookedCFTemplate-paragraph-text-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
												<small><?php _e('Paragraph Text','booked'); ?></small>
												<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
												<input type="text" name="paragraph-text-label" value="" placeholder="Enter a label for this field..." />
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li>
											<li id="bookedCFTemplate-checkboxes-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
												<small><?php _e('Checkboxes','booked'); ?></small>
												<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
												<input type="text" name="checkboxes-label" value="" placeholder="Enter a label for this checkbox group..." />
												<ul id="booked-cf-checkboxes"></ul>
												<button class="cfButton button" data-type="single-checkbox"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Checkbox','booked'); ?></button>
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li>
											<li id="bookedCFTemplate-radio-buttons-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
												<small><?php _e('Radio Buttons','booked'); ?></small>
												<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
												<input type="text" name="radio-buttons-label" value="" placeholder="Enter a label for this radio button group..." />
												<ul id="booked-cf-radio-buttons"></ul>
												<button class="cfButton button" data-type="single-radio-button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Radio Button','booked'); ?></button>
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li>
											<li id="bookedCFTemplate-drop-down-label" class="ui-state-default"><i class="main-handle fa fa-bars"></i>
												<small><?php _e('Drop Down','booked'); ?></small>
												<p><input class="cf-required-checkbox" type="checkbox" name="required" id="required"> <label for="required"><?php _e('Required Field','booked'); ?></label></p>
												<input type="text" name="drop-down-label" value="" placeholder="Enter a label for this drop-down group..." />
												<ul id="booked-cf-drop-down"></ul>
												<button class="cfButton button" data-type="single-drop-down"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php _e('Option','booked'); ?></button>
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li>
				
											<li id="bookedCFTemplate-single-checkbox" class="ui-state-default "><i class="sub-handle fa fa-bars"></i>
												<?php do_action('booked_before_custom_checkbox'); ?>
												<input type="text" name="single-checkbox" value="" placeholder="Enter a label for this checkbox..." />
												<span class="cf-delete"><i class="fa fa-close"></i></span>
												<?php do_action('booked_after_custom_checkbox'); ?>
											</li>
											<li id="bookedCFTemplate-single-radio-button" class="ui-state-default "><i class="sub-handle fa fa-bars"></i>
												<input type="text" name="single-radio-button" value="" placeholder="Enter a label for this radio button..." />
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li>
											<li id="bookedCFTemplate-single-drop-down" class="ui-state-default "><i class="sub-handle fa fa-bars"></i>
												<input type="text" name="single-drop-down" value="" placeholder="Enter a label for this option..." />
												<span class="cf-delete"><i class="fa fa-close"></i></span>
											</li>
				
											<?php do_action('booked_custom_fields_add_template') ?>
										</ul>
				
									</div>
								</div>
				
								<input id="booked_custom_fields" name="booked_custom_fields" value="<?php echo $custom_fields; ?>" type="hidden" class="field" style="width:100%;">
				
								<div class="section-row submit-section bookedClearFix" style="padding:0;">
									<input id="booked-cf-saveButton" type="button" class="button button-primary" value="<?php _e('Save Custom Fields','booked'); ?>">
									<div class="cf-updater savingState"><i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;<?php _e('Saving','booked'); ?>...</div>
								</div><!-- /.section-row -->
				
							</div><!-- /templates -->
													
						<?php break;
							
						case 'shortcodes': ?>
						
							<div id="booked-shortcodes" class="tab-content">

								<div class="section-row" style="margin-bottom:-50px;">
									<div class="section-head">
				
										<h3><?php echo __('Display the Default Calendar', 'booked'); ?></h3>
										<p><?php _e('You can use this shortcode to display the front-end booking calendar. Use the "calendar" attribute to
											display a specific calendar. Use the "year" and/or "month" attributes to display a specific month and/or
											year. You can also use the "switcher" variable to add a calendar switcher dropdown above the calendar. Your
											users can then switch between each calendar you\'ve created.','booked'); ?></p>
										<p><strong><?php _e('Example:','booked'); ?></strong> <code>[booked-calendar year="2016" month="7" calendar="12" switcher="true"]</code></p>
										<p><?php _e('This will display the calendar with the ID of 12, and it will start the calendar at July, 2016 when loaded.
											It will also display the dropdown switcher with the current calendar preselected.','booked'); ?></p>
										<p><input value="[booked-calendar]" type="text" disabled="disabled" class="field"></p>
				
									</div>
				
									<?php
				
									if (!empty($calendars)):
				
										?><div class="section-head">
											<h3><?php echo __('Display a Custom Calendar', 'booked'); ?></h3>
											<p style="margin:0 0 10px;">&nbsp;</p><?php
				
											foreach($calendars as $calendar):
				
												?><p style="margin:0 0 10px;"><strong style="font-size:14px;"><?php echo $calendar->name; ?></strong></p>
												<input value="[booked-calendar calendar=<?php echo $calendar->term_id; ?>]" type="text" disabled="disabled" class="field"><?php
				
											endforeach;
				
										?></div><?php
				
									endif;
				
									?>
				
									<div class="section-head">
				
										<h3><?php echo __('Display the Login / Register Form', 'booked'); ?></h3>
										<p><?php _e("If the Registration tab doesn't show up, be sure to allow registrations from the Settings > General page.","booked"); ?></p>
										<p><input value="[booked-login]" type="text" disabled="disabled" class="field"></p>
				
									</div>
				
									<div class="section-head">
				
										<h3><?php echo __('Display Appointments List', 'booked'); ?></h3>
										<p><?php _e("You can use this shortcode to display the currently logged in user's upcoming appointments.","booked"); ?></p>
										<p><input value="[booked-appointments]" type="text" disabled="disabled" class="field"></p>
				
									</div>
				
								</div>
				
							</div>

													
						<?php break;
					
					endswitch;
				
				endif;
				
			endforeach;
			
			?>

		</div>

	</div>
</div>