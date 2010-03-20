<table id="events-table">
  <col id="col120" />
  <col id="col340" />
<?php $seendate = '';
	$echoend = false;
	foreach ($events as $event) {
		if ($seendate != $event['start_date']) {
       		echo '<tbody>';
          	echo '<tr><th colspan="3" class="text-left"><h4 class="event-date">'.date("l F j, Y", strtotime($event['start_date'])).'</h4></th></tr>';
          	$seendate = $event['start_date'];
          	
      	} else {
      		$echoend = true;
      	}
    ?>
      <tr>
          <td><?php echo date("g:i a", strtotime($event['start_date'] .' '. $event['start_time'] ))
          	. ' - ' . date("g:i a", strtotime($event['start_date'] .' '. $event['end_time'])) ?></td>
          <td><a href="<?php bloginfo('url') ?>/events?event_id=<?php echo $event['event_id'] ?>" >
          	<?php echo $event['name'] ?>
          	</a></td>
          <td><a href="<?php bloginfo('url') ?>/events?location_id=<?php echo $event['location_id']; ?>" >
          	<?php echo $event['location_name'] ?>
          	</a></td>
      </tr>
      <?php if ($echoend) {
      		echo '</tbody>';
      		$echoend = false;
  		}

	} 
?>
</table>

<pre>
<?php //print_r($events) ?>
</pre>
