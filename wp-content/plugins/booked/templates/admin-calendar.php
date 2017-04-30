<section id="booked-plugin-page">
	<div id="data-ajax-url"><?php echo get_admin_url(); ?></div>
	
	<?php
			
	$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
	$booked_none_assigned = true;
	$default_calendar_id = false;
								
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
		
			?><div class="booked-calendarSwitcher"><p>
				<i class="fa fa-calendar"></i><?php
			
				echo '<select name="bookedCalendarDisplayed">';
				if (booked_user_role() != 'booked_booking_agent'): echo '<option value="">'.__('All Appointments','booked').'</option>'; endif;
			
				foreach($calendars as $calendar):
					
					?><option value="<?php echo $calendar->term_id; ?>"><?php echo $calendar->name; ?></option><?php
				
				endforeach;
				
				echo '</select>';
				
			?></p></div><?php
			
		endif;
		
	else :
	
		?><div class="noCalendarsSpacer"></div><?php
	
	endif;
	
	if (booked_user_role() == 'booked_booking_agent' && $booked_none_assigned):
		
		echo '<div style="text-align:center;">';
			echo '<br><br><h3>'.__('There are no calendars assigned to you.','booked').'</h3>';
			echo '<p>'.__('Get in touch with the Administration of this site to get a calendar assigned to you.','booked').'</p>';
		echo '</div>';
		
	else:
	
		?><div class="booked-admin-calendar-wrap">
			<?php booked_admin_calendar(false,false,$default_calendar_id); ?>
		</div><?php
			
	endif; ?>

</section>