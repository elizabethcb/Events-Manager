
<?php

// change prefix according to event/recurrence
$_GET ['action'] == "edit_recurrence" ? $pref = "recurrence_" : $pref = "event_";

// Set what to edit.

$form_destination = ($_GET ['action'] == "edit_recurrence") ?  DBEM_ADMIN_MENU_URI . "&amp;action=update_event&amp;recurrence_event=" . $event['event_id']
	: $form_destination = DBEM_ADMIN_MENU_URI . "amp;action=update_event&amp;event_id=" . $event['event_id'];

$locale_code = substr ( get_locale (), 0, 2 );
$localised_date_format = $loc_date_form[$locale_code];

$hours_locale = "24";
// Setting 12 hours format for those countries using it
if (preg_match ( "/en|sk|zh|us|uk/", $locale_code ))
	$hours_locale = "12";

$localised_example = str_replace ( "y", "2010", str_replace ( "m", "11", str_replace ( "d", "28", $localised_date_format ) ) );
$localised_end_example = str_replace ( "y", "2010", str_replace ( "m", "11", str_replace ( "d", "28", $localised_date_format ) ) );

$localised_date = $localised_end_date = '';
if ($event [$pref . 'start_date'] != "") {
	//preg_match ( "/(\d{4})-(\d{2})-(\d{2})/", $event [$pref . 'start_date'], $matches );
	//$year = $matches [1];
	//$month = $matches [2];
	//$day = $matches [3];
	//$localised_date = str_replace ( "yy", $year, str_replace ( "mm", $month, str_replace ( "dd", $day, $localised_date_format ) ) );
	$localised_date = date($localised_date_format, $event['event_start_date']);
}

if ($event ['recurrence_end_date'] != "") {
	//preg_match ( "/(\d{4})-(\d{2})-(\d{2})/", $event [$pref . 'end_date'], $matches );
	//$end_year = $matches [1];
	//$end_month = $matches [2];
	//$end_day = $matches [3];
	//$localised_end_date = str_replace ( "yy", $end_year, str_replace ( "mm", $end_month, str_replace ( "dd", $end_day, $localised_date_format ) ) );
	$localised_end_date = date($localised_date_format, $event['recurrence_end_date']);
} 
// if($event[$pref.'rsvp'])
// 	echo (dbem_bookings_table($event[$pref.'id']));      


$freq_options = array (
	"daily" => __ ( 'Daily', 'dbem' ), 
	"weekly" => __ ( 'Weekly', 'dbem' ), 
	"monthly" => __ ( 'Monthly', 'dbem' ) 
);
$days_names = array (1 => __ ( 'Mon' ), 2 => __ ( 'Tue' ), 3 => __ ( 'Wed' ), 4 => __ ( 'Thu' ), 5 => __ ( 'Fri' ), 6 => __ ( 'Sat' ), 7 => __ ( 'Sun' ) );
$saved_bydays = explode ( ",", $event ['recurrence_byday'] );
$weekno_options = array ("1" => __ ( 'first', 'dbem' ), '2' => __ ( 'second', 'dbem' ), '3' => __ ( 'third', 'dbem' ), '4' => __ ( 'fourth', 'dbem' ), '-1' => __ ( 'last', 'dbem' ) );

$event ['event_rsvp'] ? $event_RSVP_checked = "checked='checked'" : $event_RSVP_checked = '';

?>
<form id="eventForm" method="post" 	action="<?php echo $form_destination; ?>">
	<div class="wrap">
		<div id="icon-events" class="icon32"><br /></div>
		<h2><?php echo $pagetitle; ?></h2>
		<?php if (isset($event['recurrence_id'])) { ?>
		<p id='recurrence_warning'>
			<?php if (isset ( $_GET ['action'] ) && ($_GET ['action'] == 'edit_recurrence')) {
					_e ( 'WARNING: This is a recurrence.', 'dbem' )?>
			<br />
			<?php _e ( 'Modifying these data all the events linked to this recurrence will be rescheduled', 'dbem' );
			} else {
				_e ( 'WARNING: This is a recurring event.', 'dbem' );
				_e ( 'If you change these data and save, this will become an independent event.', 'dbem' );
			} ?>
		</p>
		<?php } ?>
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<!-- SIDEBAR -->
			<div id="side-info-column" class='inner-sidebar'>
				<div id='side-sortables'>
					<!-- recurrence postbox -->
					<div class="postbox ">
						<div class="handlediv" title="Fare clic per cambiare."><br /></div>
						<h3 class='hndle'><span><?php _e ( "Recurrence", 'dbem' ); ?> </span></h3>
						<div class="inside">
							<?php
							if ($event ['recurrence_id'] != "")
									$recurrence_YES = "checked='checked'";
							?>
							<p>
								<input id="event-recurrence" type="checkbox" name="event[repeated]" value="1" <?php echo $recurrence_YES; ?> />
								<?php _e ( 'Repeated event', 'dbem' ); ?>
							</p>
							<div id="event_recurrence_pattern">
								<p>Frequency:
									<select id="recurrence-frequency" name="recurrence[recurrence_freq]">
										<?php dbem_option_items ( $freq_options, $event [$pref . 'freq'] );?>
									</select>
								</p>
								<p>
									<?php _e ( 'Every', 'dbem' )?>
									<input id="recurrence-interval" name='recurrence[recurrence_interval]' size='2' value='<?php echo $event ['recurrence_interval']; ?>'>
									</input>
									<span class='interval-desc' id="interval-daily-singular">
									<?php _e ( 'day', 'dbem' )?>
									</span> <span class='interval-desc' id="interval-daily-plural">
									<?php _e ( 'days', 'dbem' ) ?>
									</span> <span class='interval-desc' id="interval-weekly-singular">
									<?php _e ( 'week', 'dbem' )?>
									</span> <span class='interval-desc' id="interval-weekly-plural">
									<?php _e ( 'weeks', 'dbem' )?>
									</span> <span class='interval-desc' id="interval-monthly-singular">
									<?php _e ( 'month', 'dbem' )?>
									</span> <span class='interval-desc' id="interval-monthly-plural">
									<?php _e ( 'months', 'dbem' )?>
									</span> </p>
								<p class="alternate-selector" id="weekly-selector">
									<?php dbem_checkbox_items ( 'recurrence_bydays[]', $days_names, $saved_bydays ); ?>
								</p>
								<p class="alternate-selector" id="monthly-selector">
									<?php _e ( 'Every', 'dbem' )?>
									<select id="monthly-modifier" name="recurrence[recurrence_byweekno]">
										<?php dbem_option_items ( $weekno_options, $event ['recurrence_byweekno'] ); ?>
									</select>
									<select id="recurrence-weekday" name="recurrence[recurrence_byday]">
										<?php dbem_option_items ( $days_names, $event ['recurrence_byday'] ); ?>
									</select>
									&nbsp;
								</p>
							</div><!--/rec-patt-->
							<p id="recurrence-tip">
								<?php _e ( 'Check if your event happens more than once according to a regular pattern', 'dbem' )?>
							</p>
							<?php

		if (! $event ['recurrence_id']) {
			echo "<p>" . __ ( 'This is\'t a recurrent event', 'dbem' ) . ".</p>";
		} else {
			$recurrence = dbem_get_recurrence ( $event ['recurrence_id'] ); ?>
							<p> <?php echo $event['event_description']; ?>
								<br />
								<a href="<?php echo DBEM_ADMIN_MENU_URI ?>&amp;action=edit_event&amp;recurrence_id=<?php echo $event['recurrence_id']; ?>">
								<?php _e ( 'Reschedule', 'dbem' ); ?>
								</a>
							</p>
		<?php } ?>
						</div><!--/inside-->
					</div><!--/rec-postbox-->
					<div class="postbox ">
						<div class="handlediv" title="Fare clic per cambiare."><br />
						</div>
						<h3 class='hndle'>
							<span> <?php _e ( 'Contact Person', 'dbem' ); ?>
							</span>
						</h3>
						<div class="inside">
							<p>Contact:
								<?php wp_dropdown_users ( array ('name' => 'event_contactperson_id', 'show_option_none' => __ ( "Select...", 'dbem' ), 'selected' => $event ['event_contactperson_id'] ) ); ?>
							</p>
						</div>
					</div>
					<div class="postbox ">
						<div class="handlediv" title="Fare clic per cambiare."><br />
						</div>
						<h3 class='hndle'><span>RSVP</span></h3>
						<div class="inside">
							<p>
								<input id="rsvp-checkbox" name='event_rsvp' value='1' type='checkbox' <?php echo $event_RSVP_checked?> />
								<?php _e ( 'Enable registration for this event', 'dbem' )?>
							</p>
							<div id='rsvp-data'>
								<?php
								if ($event ['event_contactperson_id'] != NULL)
									$selected = $event ['event_contactperson_id'];
								else
									$selected = '0';
								?>
								<p>
									<?php _e ( 'Spaces' ); ?>:
									<input id="seats-input" type="text" name="event_seats" size='5' value="<?php echo $event['event_seats']?>" />
								</p>
								<?php if ($event['event_rsvp']) { ?>
								<?php dbem_bookings_compact_table ( $event [$pref . 'id'] ); ?>
								<?php } ?>
							</div>
						</div>
					</div>
					<? 
/* Marcus Begin Edit */
//adding the category selection box
?>
					<div class="postbox ">
						<div class="handlediv" title="Fare clic per cambiare."><br />
						</div>
						<h3 class='hndle'><span>
							<?php _e ( 'Category', 'dbem' ); ?>
							</span></h3>
						<div class="inside">
							<p>Category:
								<select name="event_category_id">
									<?php $categories = dbem_get_categories(); ?>
									<option value="">Select...</option>
									<?php
						foreach ( $categories as $category ){
							$event_category = dbem_get_event_category($event['event_id']);
							$selected = ($category['category_id'] == $event_category['category_id']) ? "selected='selected'": '';
							?>
									<option value="<?= $category['category_id'] ?>" <?= $selected ?>>
									<?= $category['category_name'] ?>
									</option>
									<?
						}
					?>
								</select>
							</p>
						</div>
					</div>
				</div>
				<? /* Marcus End Edit */ ?>
			</div>
			<!-- END OF SIDEBAR -->
			<div id="post-body">
				<div id="post-body-content">
		<? /* Marcus End Edit */ ?>
					<div id="event_name" class="stuffbox">
						<h3>
							<?php _e ( 'Name', 'dbem' ); ?>
						</h3>
						<div class="inside">
							<input type="text" name="event[event_name]" value="<?php echo $event['event_name']?>" size="75"/>
							<br />
							<?php _e ( 'The event name. Example: Birthday party', 'dbem' )?>
						</div>
					</div>
					<div id="event_start_date" class="stuffbox">
						<h3 id='event-date-title'>
							<?php _e ( 'Event date', 'dbem' ); ?>
						</h3>
						<h3 id='recurrence-dates-title'>
							<?php _e ( 'Recurrence dates', 'dbem' ); ?>
						</h3>
						<div class="inside">
							<input id="localised-date" type="text" name="localised_event_date" value="<?php echo $localised_date?>" style="display: none;" />
							<input id="date-to-submit" type="text" name="event[event_date]" value="<?php echo $event['event_start_date']?>" style="background: #FCFFAA" />
							<input id="localised-end-date" type="text" name="localised_event_end_date" value="<?php echo $localised_end_date?>" style="display: none;" />
							<input id="end-date-to-submit" type="text" name="event[event_end_date]" value="<?php echo $event['recurrence_end_date']?>" style="background: #FCFFAA" />
							<br />
							<span id='event-date-explanation'>
							<?php _e ( 'The event date.', 'dbem' );
								/* Marcus Begin Edit */
								echo " ";
								_e ( 'When not reoccurring, this event spans between the beginning and end date.', 'dbem' );
								/* Marcus End Edit */
							?>
							</span>
							<span id='recurrence-dates-explanation'>
							<?php _e ( 'The recurrence beginning and end date.', 'dbem' ); ?>
							</span> 
						</div>
					</div>
					<div id="event_end_day" class="stuffbox">
						<h3>
							<?php _e ( 'Event time', 'dbem' ); ?>
						</h3>
						<div class="inside">
							<input id="start-time" type="text" size="8" maxlength="8" name="event[event_start_time]" value="<?php echo $event ['event_start_' . $hours_locale . "h_time"]; ?>" />
							-
							<input id="end-time" type="text" size="8" maxlength="8" name="event[event_end_time]" value="<?php echo $event['event_end_' . $hours_locale . "h_time"]; ?>" />
							<br />
							<?php _e ( 'The time of the event beginning and end', 'dbem' )?>
							. 
						</div>
					</div>
					<div id="location_coordinates" class="stuffbox" style='display: none;'>
						<h3>
							<?php _e ( 'Coordinates', 'dbem' ); ?>
						</h3>
						<div class="inside">
							<input id='location-latitude' name='location[location_latitude]' type='text' value='<?php echo $event['location_latitude']; ?>' size='15' />
							-
							<input id='location-longitude' name='location[location_longitude]' type='text' value='<?php echo $event ['location_longitude']; ?>' size='15' />
						</div>
					</div>
					<div id="location_name" class="stuffbox">
						<h3>
							<?php _e ( 'Location', 'dbem' ); ?>
						</h3>
						<div class="inside">
							<table id="dbem-location-data">
								<tr>
									<th><?php _e ( 'Name:' )?> &nbsp;</th>
									<td>
										<input id="location-name" type="text" name="location[location_name]" value="<?php echo $event ['location_name']?>" />
									</td>
									<?php if ($opts['gmap_is_active']) { ?>
									<td rowspan='6'>
										<div id='map-not-found' style='width: 400px; font-size: 140%; text-align: center; margin-top: 100px; display: hide'>
											<p>
												<?php _e ( 'Map not found' ); ?>
											</p>
										</div>
										<div id='event-map' style='width: 400px; height: 300px; background: green; display: hide; margin-right: 8px'></div>
									</td>
									<?php } // end of IF_GMAP_ACTIVE	?>
								</tr>
								<tr>
									<td colspan='2'><p>
										<?php _e ( 'The name of the location where the event takes place. You can use the name of a venue, a square, etc', 'dbem' )?>
									</p></td>
								</tr>
								<tr>
									<th><?php _e ( 'Address:' )?> &nbsp;</th>
									<td><input id="location-address" type="text" name="location[location_address]" value="<?php echo $event ['location_address']; ?>" /></td>
								</tr>
								<tr>
									<td colspan='2'><p>
										<?php _e ( 'The address of the location where the event takes place. Example: 21, Dominick Street', 'dbem' )?>
									</p></td>
								</tr>
								<tr>
									<th><?php _e ( 'Town:' )?> &nbsp;</th>
									<td><input id="location-town" type="text" name="location[location_town]" value="<?php echo $event ['location_town']?>" /></td>
								</tr>
								<tr>
									<td colspan='2'><p>
										<?php _e ( 'The town where the location is located. If you\'re using the Google Map integration and want to avoid geotagging ambiguities include the country in the town field. Example: Verona, Italy.', 'dbem' )?>
									</p></td>
								</tr>
							</table>
						</div>
					</div>
					<div id="event_notes" class="postbox">
						<h3>
							<?php _e ( 'Details', 'dbem' ); ?>
						</h3>
						<div class="inside">
							<? /* Marcus Begin Edit */ ?>
							<!-- Currently deactivated for editor test
							<textarea name="event_notes" rows="8" cols="60">
								<?php
								echo $event [$pref . 'notes'];
								?>
							</textarea> 
							-->
							<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
								<?php the_editor($event['event_notes']); ?>
							</div>
							<? /* Marcus End Edit */ ?>
							<br />
							<?php _e ( 'Details about the event', 'dbem' )?>
						</div>
					</div>
				</div><!--/post-body-content-->
				<p class="submit">
					<input type="submit" name="events_update" value="<?php _e ( 'Submit Event', 'dbem' ); ?> &raquo;" />
				</p>
			</div>
		</div>
	</div>
</form>

