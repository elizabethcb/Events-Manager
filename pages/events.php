
<?php foreach ($events as $event) { ?>
	<div class="event-listing" style="width:300px;">	
		<span class="event-link">
			<a href="<?php echo get_bloginfo('url') . '/events/?event_id=' . $event['event_id'] ?>" title="<?php echo $event['name']; ?>" />
				<?php echo $event['name']; ?>
			</a>
		</span><br />
		<span class="event-date"><?php echo $event['start_date']; 
			// TODO don't hard set the format test here.  use variable.
			if ( isset($event['end_time']) && $event['end_date'] != '0000-00-00' )
				echo ' - ' . $event['end_date']; ?>
		</span><br />
		<span class="event-time"><?php echo $event['start_time'] . '-' . $event['end_time']; ?></span>
		<br />
		<span class="event-town"><?php echo $event['town']; ?></span><br />
		<span style="font-size:xx-small">Date and time to be fixed</span>
	</div>
<?php } ?>
<pre>
<?php print_r($events) ?>
</pre>
