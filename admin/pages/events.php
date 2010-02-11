<?php 
/* DBEM_DOCROOT . admin/pages/events.php */
?>

<div id="icon-events" class="icon32"><br />
</div>
<h3><?php echo $pagetitle ?></h3>

<?php
$events_count = count ( $events );

$use_events_end = get_option ( 'dbem_use_event_end' );

$say_hello = get_option ( 'dbem_hello_to_user' );
if ($say_hello == 1)
	dbem_hello_to_new_user ();
	
?>   
  	<!--<div id='new-event' class='switch-tab'><a href="<?php
	bloginfo ( 'wpurl' )?>/wp-admin/admin.php?page=events-manager2/events-manager2.php&amp;action=edit_event"><?php
	_e ( 'New Event ...', 'dbem' );
	?></a></div>-->  
<?php
$scopes = array (
	'past' 	 => __ ( 'Past events', 'dbem' ),
	'all' 	 => __ ( 'All events', 'dbem' ),
	'future' => __ ( 'Future events', 'dbem' )
);	
$link = array (
	'past' 	 => "<a href='" . DBEM_ADMIN_MENU_URI ."&amp;scope=past&amp;order=desc'>" .$scopes['past'] . "</a>",
	'all' 	 => "<a href='" . DBEM_ADMIN_MENU_URI . "&amp;scope=all&amp;order=desc'>" . $scopes['all'] . "</a>",
	'future' => "<a href='" . DBEM_ADMIN_MENU_URI. "&amp;scope=future'>" . $scopes['future'] . "</a>"
);
if (is_string($message) or is_string($error)) {
?> 
<div id="message" class="updated fade">
	<p><?php echo $message; ?></p>
	<p><?php echo $error; ?></p>
</div>
<?php } ?>
<form id="posts-filter" action="<?php echo DBEM_ADMIN_MENU_URI ?>" method="post">
	<input type='hidden' name='action' value='misc-location'/>
	<ul class="subsubsub">
		<li>
			<a href='admin.php' class="current"><?php _e ( 'Total', 'dbem' ); ?> 
			<span class="count">(<?php echo $events_count; ?>) </span>
			</a> Why is there a link to main WP admin here?
		</li>
	</ul>


	<div class="tablenav">

	<div class="alignleft actions">
		<select name="action">
			<option value="-1" selected="selected"><?php _e ( 'Bulk Actions' ); ?></option>
			<option value="delete_events"><?php _e ( 'Delete selected' ); ?></option>
		</select> 
		<input type="submit" value="<?php _e ( 'Apply' ); ?>" name="doaction2" id="doaction2" class="button-secondary action" />
		<select name="scope">
			<?php
			foreach ( $scopes as $key => $value ) {
				$selected = "";
				if ($key == $scope)
					$selected = "selected='selected'";
				echo "<option value='$key' $selected>$value</option>";
			} ?>
		</select> 
		<input id="post-query-submit" class="button-secondary" type="submit" value="<?php _e ( 'Filter' )?>" />
	</div>
	</div>
	<div class="clear"></div>
	<?php
	if (empty ( $events )) {
		// TODO localize
		echo "No Events";
		return;
	}?>
	<pre>
	<?php //print_r($events); ?>
	</pre>
	<table class="widefat">
	<thead>
		<tr>
			<th class='manage-column column-cb check-column' scope='col'><input
				class='select-all' type="checkbox" value='1' /></th>
			<th><?php _e ( 'Name', 'dbem' ); ?></th>
	  	    <th></th>
	  	   	<th><?php _e ( 'Location', 'dbem' ); ?></th>
			<th colspan="2"><?php _e ( 'Date and time', 'dbem' ); ?></th>
		</tr>
	</thead>
	<tbody>
  	  <?php
		$i = 1;
		foreach ( $events as $event ) {
			$class = ($i % 2) ? ' class="alternate"' : '';
			// FIXME use localisation
			$localised_date = mysql2date ( __ ( 'D d M Y' ), $event['start_date'] );
			if ( isset($event['end_date']) ) {
				$local_end_date = mysql2date( __ ( ' D d M Y' ), $event['end_date'] );
			}
			$style = "";
			$today = date ( "Y-m-d" );
			
			$location_summary = "<b>" . $event['location_name'] . "</b><br/>" 
				. $event['address'] . " - " . $event['town'];
			
			if ($event['start_date'] < $today)
				$style = "style ='background-color: #FADDB7;'";
			?>
		<tr <?php echo "$class $style"; ?>>
			<td><input type='checkbox' class='row-selector' value='<?php echo $event['event_id']; ?>' name='events[]' /></td>
			<td><strong>
				<a class="row-title" href="<?php echo DBEM_ADMIN_MENU_URI ?>&amp;action=edit_event&amp;event_id=<?php echo $event['event_id']; ?>">
					<?php echo $event['name']; ?>
				</a>
			</strong></td>
			<td>
 	    		<a href="<?php echo DBEM_ADMIN_MENU_URL ?>&amp;action=duplicate_event&amp;event_id=<?php echo $event ['event_id']; ?>"
				title="<?php _e ( 'Duplicate this event', 'dbem' ); ?>"><strong>+</strong>
				</a>
			</td>
			<td> <?php echo $location_summary; ?></td>
			<td> 
				<?php echo $localised_date; ?><br />
  	    		<?php echo substr ( $event ['start_time'], 0, 5 ) . " - " 
  	    			. substr ( $event ['end_time'], 0, 5 );
				?>
			</td>
			<td>

			</td>
		</tr>	
	<?php } //end foreach event ?>
	</tbody>
	</table>  


</form>

	<div class='tablenav'>
		<div class="alignleft actions">
			<br class='clear' />
		</div>

<?php if ($events_count > $limit) {
		$backward = $offset + $limit;
		$forward = $offset - $limit;
		if (DEBUG)
			echo "COUNT = $count BACKWARD = $backward  FORWARD = $forward<br> -- OFFSET = $offset";
		echo "<div id='events-pagination'> ";
		if ($backward < $events_count)
			echo "<a style='float: left' href='" . DBEM_ADMIN_MENU_URI . "&scope=$scope&offset=$backward'>&lt;&lt;</a>";
		if ($forward >= 0)
			echo "<a style='float: right' href='" . DBEM_ADMIN_MENU_URI . "&scope=$scope&offset=$forward'>&gt;&gt;</a>";
		echo "</div>";
} ?>
		<br class='clear' />
	</div>	</div>

