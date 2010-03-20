<?# LOCATIONS LIST ?>
<pre>
<?php //print_r($locations); ?>
</pre>
<table id="events-table">
	<col id="col120" />
    <col id="col190" />
	<?php

 		foreach ($locations as $listing) { 
 			if ( !is_array($listing['events']) )
 				continue;
 			?>
			<tbody>
				<tr>
					<th colspan="2" class="text-left"><h4 class="event-date">
						<a href="<?php get_bloginfo("siteurl"); ?>/events/?location_id=<?php echo $listing['location_id']; ?>">
							<?php echo $listing['location_name'] ?>
						</a>
					</h4></th>
					<th class="text-right"><?php echo $listing['address'].', '.$listing['town'];?>
					</th>
				</tr>
         	<?php
         	//int mktime  ([ int $hour = date("H")  [, int $minute = date("i")  
		    //[, int $second = date("s")  [, int $month = date("n")  [, int $day = date("j")  
		    //[, int $year = date("Y")  [, int $is_dst = -1  ]]]]]]] )
		   foreach ($listing['events'] as $event ) {
		    list($year, $month, $day) = explode('-', $event['start_date']);
		    list($shour, $smin, $ssec) = explode(':', $event['start_time']);
		    list($ehour, $emin, $esec) = explode(':', $event['end_time']);
		    $timestamp = mktime($shour, $smin, $ssec, $month, $day, $year);
		    $date = date('j F, Y', $timestamp);
		    $time = date('g:i a', $timestamp);
		    $edate = date('g:i a', mktime($ehour, $emin, $esec, $month, $day, $year));
		    
 		?>
 			
     <tr>
         <td><?php echo $date ?></td>
         <td><?php echo $time. ' - '. $edate?></td>
         <td><a href="<?php get_bloginfo('siteurl'); ?>/events/?event_id=<?php echo $event["event_id"] ?>">
         <?php echo $event['name'] ?></a></td>
     </tr>
     <?php }
     	echo '</tbody>';
    } ?> 
</table>