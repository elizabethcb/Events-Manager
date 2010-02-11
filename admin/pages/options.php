
<div class="wrap">
<pre>
<?php //print_r($opts); ?>
</pre>
<?php // Use this for the current sites to make sure their options are correct
//dbem_reset_options(); ?>
<div id='icon-options-general' class='icon32'><br />
</div>
<h2><?php
	_e ( 'Event Manager Options', 'dbem' );
	?></h2>
<form id="dbem_options_form" method="post" action="<?php echo DBEM_ADMIN_MENU_URI ?>&action=options_update">
<h3><?php
	_e ( 'Events page', 'dbem' );
	?></h3>
<table class="form-table">  
 					<?php
	dbem_options_select ( $opts, __( 'Events page' ), 'events_page', dbem_get_all_pages (), __ ( 'This option allows you to select which page to use as an events page' ) );
	dbem_options_radio_binary ( $opts, __( 'Display calendar in events page?', 'dbem' ), 'display_calendar_in_events_page', __ ( 'This options allows to display the calendar in the events page, instead of the default list. It is recommended not to display both the calendar widget and a calendar page.' ) )?>
	      </table>
<h3><?php
	_e ( 'Events format', 'dbem' );
	?></h3>
<table class="form-table">
 	<?php
 	/* Marcus Begin Edit */
 	dbem_options_textarea ( $opts, __( 'Default event list format', 'dbem' ), 'event_list_item_format', __ ( 'The format of any events in a list.<br/>Insert one or more of the following placeholders: <code>#_NAME</code>, <code>#_LOCATION</code>, <code>#_ADDRESS</code>, <code>#_TOWN</code>, <code>#_NOTES</code>.<br/> Use <code>#_LINKEDNAME</code> for the event name with a link to the given event page.<br/> Use <code>#_EVENTPAGEURL</code> to print the event page URL and make your own customised links.<br/> Use <code>#_LOCATIONPAGEURL</code> to print the location page URL and make your own customised links.<br/>To insert date and time values, use <a href="http://www.php.net/manual/en/function.date.php">PHP time format characters</a>  with a <code>#</code> symbol before them, i.e. <code>#m</code>, <code>#M</code>, <code>#j</code>, etc.<br/> For the end time, put <code>#@</code> in front of the character, ie. <code>#@h</code>, <code>#@i</code>, etc.<br/> You can also create a date format without prepending <code>#</code> by wrapping it in #_{} or #@_{} (e.g. <code>#_{d/m/Y}</code>). If there is no end date, the value is not shown.<br/> Feel free to use HTML tags as <code>li</code>, <code>br</code> and so on.', 'dbem' ) );
 	/* Marcus End Edit */
	dbem_options_input_text ( $opts, __( 'Single event page title format', 'dbem' ), 'event_page_title_format', __ ( 'The format of a single event page title. Follow the previous formatting instructions.', 'dbem' ) );
	dbem_options_textarea ( $opts, __( 'Default single event format', 'dbem' ), 'single_event_format', __ ( 'The format of a single event page.<br/>Follow the previous formatting instructions. <br/>Use <code>#_MAP</code> to insert a map.<br/>Use <code>#_CONTACTNAME</code>, <code>#_CONTACTEMAIL</code>, <code>#_CONTACTPHONE</code> to insert respectively the name, e-mail address and phone number of the designated contact person. <br/>Use <code>#_ADDBOOKINGFORM</code> to insert a form to allow the user to respond to your events reserving one or more places (RSVP).<br/> Use <code>#_REMOVEBOOKINGFORM</code> to insert a form where users, inserting their name and e-mail address, can remove their bookings.', 'dbem' ) );
	dbem_options_radio_binary ( $opts, __( 'Show events page in lists?', 'dbem' ), 'list_events_page', __ ( 'Check this option if you want the events page to appear together with other pages in pages lists.', 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'Events page title', 'dbem' ), 'events_page_title', __ ( 'The title on the multiple events page.', 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'No events message', 'dbem' ), 'no_events_message', __ ( 'The message displayed when no events are available.', 'dbem' ) );
	dbem_options_textarea ( $opts, __( 'Map text format', 'dbem' ), 'map_text_format', __ ( 'The format the text appearing in the event page map cloud.<br/>Follow the previous formatting instructions.', 'dbem' ) );
	?>
			</table>

<h3><?php
	_e ( 'Locations format', 'dbem' );
	?></h3>
<table class="form-table"><?php
	dbem_options_input_text ( $opts, __( 'Single location page title format', 'dbem' ), 'location_page_title_format', __ ( 'The format of a single location page title.<br/>Follow the previous formatting instructions.', 'dbem' ) );
	dbem_options_textarea ( $opts, __( 'Default single location page format', 'dbem' ), 'single_location_format', __ ( 'The format of a single location page.<br/>Insert one or more of the following placeholders: <code>#_NAME</code>, <code>#_ADDRESS</code>, <code>#_TOWN</code>, <code>#_DESCRIPTION</code>.<br/> Use <code>#_MAP</code> to display a map of the event location, and <code>#_IMAGE</code> to display an image of the location.<br/> Use <code>#_NEXTEVENTS</code> to insert a list of the upcoming events, <code>#_PASTEVENTS</code> for a list of past events, <code>#_ALLEVENTS</code> for a list of all events taking place in this location.', 'dbem' ) );
	dbem_options_textarea ( $opts, __( 'Default location baloon format', 'dbem' ), 'location_baloon_format', __ ( 'The format of of the text appearing in the baloon describing the location in the map.<br/>Insert one or more of the following placeholders: <code>#_NAME</code>, <code>#_ADDRESS</code>, <code>#_TOWN</code>, <code>#_DESCRIPTION</code> or <code>#_IMAGE</code>.', 'dbem' ) );
	dbem_options_textarea ( $opts, __( 'Default location event list format', 'dbem' ), 'location_event_list_item_format', __ ( 'The format of the events the list inserted in the location page through the <code>#_NEXTEVENTS</code>, <code>#_PASTEVENTS</code> and <code>#_ALLEVENTS</code> element. <br/> Follow the events formatting instructions', 'dbem' ) );
	dbem_options_textarea ( $opts, __( 'Default no events message', 'dbem' ), 'location_no_events_message', __ ( 'The message to be displayed in the list generated by <code>#_NEXTEVENTS</code>, <code>#_PASTEVENTS</code> and <code>#_ALLEVENTS</code> when no events are available.', 'dbem' ) );
	
	?>
				</table>

<h3><?php
	_e ( 'RSS feed format', 'dbem' );
	?></h3>
<table class="form-table"><?php
	
	dbem_options_input_text ( $opts, __( 'RSS main title', 'dbem' ), 'rss_main_title', __ ( 'The main title of your RSS events feed.', 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'RSS main description', 'dbem' ), 'rss_main_description', __ ( 'The main description of your RSS events feed.', 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'RSS title format', 'dbem' ), 'rss_title_format', __ ( 'The format of the title of each item in the events RSS feed.', 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'RSS description format', 'dbem' ), 'rss_description_format', __ ( 'The format of the description of each item in the events RSS feed. Follow the previous formatting instructions.', 'dbem' ) );
	?>
		</table>

<h3><?php
	_e ( 'Maps and geotagging', 'dbem' );
	?></h3>
<table class='form-table'> 
				    <?php
	$gmap_is_active = $opts['gmap_is_active'];
	?>
					 
				   	<tr valign="top">
		<th scope="row"><?php
	_e ( 'Enable Google Maps integration?', 'dbem' );
	?></th>
		<td><input id="dbem_gmap_is_active_yes" name="gmap_is_active"
			type="radio" value="1"
			<?php
	if ($gmap_is_active)
		echo "checked='checked'";
	?> /><?php
	_e ( 'Yes' );
	?> <br />
		<input name="gmap_is_active" type="radio" value="0"
			<?php
	if (! $gmap_is_active)
		echo "checked='checked'";
	?> /> <?php
	_e ( 'No' );
	?>  <br />
							<?php
	_e ( 'Check this option to enable Goggle Map integration.', 'dbem' )?>
						</td>
	</tr>
					 <?php
	dbem_options_input_text ( $opts, __( 'Google Maps API Key', 'dbem' ), 'gmap_key', sprintf ( __ ( "To display Google Maps you need a Google Maps API key. Don't worry, it's free, you can get one <a href='%s'>here</a>.", 'dbem' ), 'http://code.google.com/apis/maps/signup.html' ) );
	?>       
				</table>

<h3><?php
	_e ( 'RSVP and bookings', 'dbem' );
	?></h3>
<table class='form-table'>
				   <?php
	dbem_options_select ( $opts, __( 'Default contact person', 'dbem' ), 'default_contact_person', dbem_get_indexed_users (), __ ( 'Select the default contact person. This user will be employed whenever a contact person is not explicitly specified for an event', 'dbem' ) );
	dbem_options_radio_binary ( $opts, __( 'Enable the RSVP e-mail notifications?', 'dbem' ), 'rsvp_mail_notify_is_active', __ ( 'Check this option if you want to receive an email when someone books places for your events.', 'dbem' ) );
	dbem_options_textarea ( $opts, __( 'Contact person email format', 'dbem' ), 'contactperson_email_body', __ ( 'The format or the email which will be sent to  the contact person. Follow the events formatting instructions. <br/>Use <code>#_RESPNAME</code>, <code>#_RESPEMAIL</code> and <code>#_RESPPHONE</code> to display respectively the name, e-mail, address and phone of the respondent.<br/>Use <code>#_SPACES</code> to display the number of spaces reserved by the respondent.<br/> Use <code>#_BOOKEDSEATS</code> and <code>#_AVAILABLESEATS</code> to display respectively the number of booked and available seats.', 'dbem' ) );
	dbem_options_textarea ( $opts, __( 'Contact person email format', 'dbem' ), 'respondent_email_body', __ ( 'The format or the email which will be sent to reposdent. Follow the events formatting instructions. <br/>Use <code>#_RESPNAME</code> to display the name of the respondent.<br/>Use <code>#_CONTACTNAME</code> and <code>#_CONTACTMAIL</code> a to display respectively the name and e-mail of the contact person.<br/>Use <code>#_SPACES</code> to display the number of spaces reserved by the respondent.', 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'Notification sender name', 'dbem' ), 'mail_sender_name', __ ( "Insert the display name of the notification sender.", 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'Notification sender address', 'dbem' ), 'mail_sender_address', __ ( "Insert the address of the notification sender. It must corresponds with your gmail account user", 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'Default notification receiver address', 'dbem' ), 'mail_receiver_address', __ ( "Insert the address of the receiver of your notifications", 'dbem' ) );
	dbem_options_input_text ( $opts, 'Mail sending port', 'rsvp_mail_port', __ ( "The port through which you e-mail notifications will be sent. Make sure the firewall doesn't block this port", 'dbem' ) );
	dbem_options_select ( $opts, __( 'Mail sending method', 'dbem' ), 'rsvp_mail_send_method', array ('smtp' => 'SMTP', 'mail' => __ ( 'PHP mail function', 'dbem' ), 'sendmail' => 'Sendmail', 'qmail' => 'Qmail' ), __ ( 'Select the method to send email notification.', 'dbem' ) );
	dbem_options_radio_binary ( $opts, __( 'Use SMTP authentication?', 'dbem' ), 'rsvp_mail_SMTPAuth', __ ( 'SMTP authenticatio is often needed. If you use GMail, make sure to set this parameter to Yes', 'dbem' ) );
	dbem_options_input_text ( $opts, 'SMTP host', 'smtp_host', __ ( "The SMTP host. Usually it corresponds to 'localhost'. If you use GMail, set this value to 'ssl://smtp.gmail.com:465'.", 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'SMTP username', 'dbem' ), 'smtp_username', __ ( "Insert the username to be used to access your SMTP server.", 'dbem' ) );
	dbem_options_input_password ( $opts, __( 'SMTP password', 'dbem' ), "smtp_password", __ ( "Insert the password to be used to access your SMTP server", 'dbem' ) );
	?>
				 
						 
			   
					</table>

<h3><?php
	_e ( 'Images size', 'dbem' );
	?></h3>
<table class='form-table'> <?php
	dbem_options_input_text ( $opts, __( 'Maximum width (px)', 'dbem' ), 'image_max_width', __ ( 'The maximum allowed width for images uploades', 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'Maximum height (px)', 'dbem' ), 'image_max_height', __ ( "The maximum allowed width for images uploaded, in pixels", 'dbem' ) );
	dbem_options_input_text ( $opts, __( 'Maximum size (bytes)', 'dbem' ), 'image_max_size', __ ( "The maximum allowed size for images uploaded, in pixels", 'dbem' ) );
	?>
					 </table>



<p class="submit"><input type="submit" id="dbem_options_submit" name="Submit" value="<?php _e ( 'Save Changes' )?>" /></p>

				
			<?php
	settings_fields ( 'dbem-options' );
	?> 
			</form>
</div>
