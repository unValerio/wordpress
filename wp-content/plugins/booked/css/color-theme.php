<?php
	
/*
Color Theme Template
*/

$button_color = get_option('booked_button_color','#0bbe5f');
$light_color = get_option('booked_light_color','#c4f2d4');
$dark_color = get_option('booked_dark_color','#039146');

?>

/* Light Color */
body #booked-profile-page input[type=submit].button-primary:hover,
body table.booked-calendar input[type=submit].button-primary:hover,
body .booked-modal input[type=submit].button-primary:hover,
body table.booked-calendar thead,
body table.booked-calendar thead th,
body table.booked-calendar .booked-appt-list .timeslot .timeslot-people button:hover,
body #booked-profile-page .booked-profile-appt-list .appt-block .google-cal-button,
body #booked-profile-page .booked-profile-header
{ background:<?php echo $light_color; ?> !important; }

body #booked-profile-page input[type=submit].button-primary:hover,
body table.booked-calendar input[type=submit].button-primary:hover,
body .booked-modal input[type=submit].button-primary:hover,
body table.booked-calendar th,
body table.booked-calendar .booked-appt-list .timeslot .timeslot-people button:hover,
body #booked-profile-page .booked-profile-appt-list .appt-block .google-cal-button,
body #booked-profile-page .booked-profile-header
{ border-color:<?php echo $light_color; ?> !important; }


/* Dark Color */
body table.booked-calendar tr.days,
body table.booked-calendar tr.days th,
body .booked-calendarSwitcher,
body .booked-calendarSwitcher select
{ background:<?php echo $dark_color; ?> !important; }

body table.booked-calendar tr.days th
{ border-color:<?php echo $dark_color; ?> !important; }


/* Primary Button Color */
body #booked-profile-page input[type=submit].button-primary,
body table.booked-calendar input[type=submit].button-primary,
body .booked-modal input[type=submit].button-primary,
body table.booked-calendar .booked-appt-list .timeslot .timeslot-people button,
body #booked-profile-page .booked-profile-appt-list .appt-block.approved .status-block
{ background:<?php echo $button_color; ?>; }

body #booked-profile-page input[type=submit].button-primary,
body table.booked-calendar input[type=submit].button-primary,
body .booked-modal input[type=submit].button-primary,
body table.booked-calendar .booked-appt-list .timeslot .timeslot-people button
{ border-color:<?php echo $button_color; ?>; }