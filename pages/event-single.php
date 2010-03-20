
<h2 class="single-event-title"><?php echo $event['name']; ?></h2>
<div class="single-event-stuff">
	<div class="single-event-left">
		<span class="single-event-date"><?php echo date("l F j, Y", strtotime($event['start_date'])); ?></span>
		<br />
		<?php echo date("g:i a", strtotime($event['start_date'] .' '. $event['start_time'])) 
		. ' - ' . date("g:i a", strtotime($event['start_date'] .' '. $event['end_time']));
		?><br />
		<div class="single-event-location">
			<div class="single-event-morestuff">Location: <a href="#">Map</a> <a href="#">Weather</a></div>
			<?php echo $event['location_name']. '<br />'.$event['address'].'<br />'.$event['town']; ?>
		</div>
		Email: nobody@fake.edu<br />
		<br />
		Phone: 555.123.4567<br />
		Website: <a href="#">Click to Visit</a>
		<br /><br />
		<a href="<?php get_bloginfo('siteurl'); ?>/events/?location_id=<?php echo $event['location_id'] ?>">
			Browse Events at This Location</a>
		<br />
		<br />
		<strong>Categories:</strong><br />
		<a href="#">Baseball</a><br />
		<a href="#">Eating donuts</a><br />
		<a href="#">Theatre</a><br />
	</div>
	<div class="single-event-right">
		<?php echo $event['notes']; ?>
	</div>
</div>
<br class="clear" />
<?php echo $map  ?>
