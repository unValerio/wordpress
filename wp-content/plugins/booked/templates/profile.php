<?php

// This template only shows up if you are logged in or if you have a username after the /profile/ in the url.

global $current_user,$custom_query,$custom_recipe_title,$custom_type,$error,$post;

//get_currentuserinfo();
$current_user = wp_get_current_user();

$profile_username = $current_user->user_login;
$my_id = $current_user->ID;
$my_profile = true;

$user_data = get_user_by( 'id', $current_user->ID );

?><div id="booked-profile-page"<?php if ($my_profile): ?> class="me"<?php endif; ?>><?php

if (empty($user_data)) {

	echo '<h2>' . __('No profile here!','booked') . '</h2>';
	echo '<p>' . __('Sorry, this user profile does not exist.','booked') . '</p>';

} else { ?>

	<?php

	$user_meta = get_user_meta($user_data->ID);
	$user_url = $user_data->data->user_url;
	$user_desc = $user_meta['description'][0];
	$h3_class = '';

	$disable_avatar = get_option('booked_disable_avatar',false);
	$disable_website = get_option('booked_disable_website',false);
	$disable_bio = get_option('booked_disable_bio',false);

	?>

	<div class="booked-profile-header bookedClearFix"<?php if ($disable_avatar): ?> style="min-height:32px; padding:20px 25px 14px"<?php endif; ?>>


		<?php if (!$disable_avatar): ?>
			<div class="booked-avatar">
				<?php echo booked_avatar($user_data->ID,150); ?>
			</div>
		<?php endif; ?>

		<div class="booked-info"<?php if ($disable_avatar): ?> style="padding-left:0;"<?php endif; ?>>
			<div class="booked-user">
				<h3 class="<?php echo $h3_class; ?>"<?php if ($disable_website && $disable_bio): ?> style="margin:25px 0 0 5px"<?php endif; ?>><?php echo booked_get_name( $user_data->ID ); ?></h3>
				<?php if ($user_url && !$disable_website){ echo '<p><a href="'.$user_url.'" target="_blank">'.$user_url.'</a></p>'; } ?>
				<?php if ($user_desc && !$disable_bio){ echo wpautop($user_desc); } ?>
				<?php if ($my_profile): ?>
					<a class="booked-logout-button" href="<?php echo wp_logout_url(get_permalink($post->ID)); ?>" title="<?php _e('Logout','booked'); ?>"><?php _e('Logout','booked'); ?></a>
				<?php endif; ?>
			</div>
		</div>

	</div>

	<ul class="booked-tabs bookedClearFix">
		<?php

			$default_tabs = array(
				'appointments' => array(
					'title' => __('Upcoming Appointments','booked'),
					'fa-icon' => 'fa-calendar',
					'class' => false
				),
				'history' => array(
					'title' => __('Appointment History','booked'),
					'fa-icon' => 'fa-calendar-o',
					'class' => false
				),
				'edit' => array(
					'title' => __('Edit Profile','booked'),
					'fa-icon' => 'fa-edit',
					'class' => 'edit-button'
				)
			);

			echo apply_filters('booked_profile_tabs',$default_tabs);

		?>
	</ul>

	<?php $appointment_default_status = get_option('booked_new_appointment_default','draft');

	if ( is_user_logged_in() && $my_profile ) : ?>

		<?php echo apply_filters('booked_profile_tab_content',$default_tabs); ?>

	<?php endif; ?>


<?php } ?>

</div>