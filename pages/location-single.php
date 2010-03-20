
<table id="events-table">
	<col id="col120" />
	<col id="col190" />
	<thead>
		<tr>
			<th colspan="3"><?php echo $location['location_name'] ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="text-left">Date</th>
			<th class="text-left">Time</th>
			<th class="text-left">Name</th>
		</tr>
		<?php foreach ($events as $event) { ?>
		<tr>
			<td><?php echo date("F j, Y", strtotime($event['start_date'])); 
				if ( isset($event['end_date']) && $event['end_date'] != '0000-00-00' )
					echo ' - ' . $event['end_date'];
			?></td>
			<td><?php echo date("g:i a", strtotime($event['start_date'] .' '. $event['start_time'])) 
		. ' - ' . date("g:i a", strtotime($event['start_date'] .' '. $event['end_time']));
		?></td> 
			<td><a href="<?php echo get_bloginfo('url') . '/events/?event_id=' . $event['event_id']; ?>">
				<?php echo $event['name']; ?>
			</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<pre>
<?php echo $map; ?>