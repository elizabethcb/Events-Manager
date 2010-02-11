<div class="event-header">
	<h2 class="event-title">
		<?php 
			$pattern = '/,\s?[A-Z]+/';
			$name = preg_replace($pattern, '', get_bloginfo('name'));
			echo $name;  
			echo ' Events';
		?>
	</h2>
	<?php //dbem_get_events_page() ?>
	<ul id="events-navigation">
		<li><a href="<?php echo get_bloginfo('url') . '/events'; ?>" >Events</a></li>
		<?php 
		// Do something about if the request as a matching dohicky add active class
		foreach (array('Locations','Submit Event') as $item) {
			$pattern = '/\s+/';
			$edited = preg_replace($pattern, '', $item);
		?>
			<li><a href="<?php echo get_bloginfo('url'); ?>/events/?<?php echo strtolower($edited) ?>=1" >
				<?php echo $item ?></a></li>
		<?php } ?>
		<li><span id="search-events" class="event-nav-link">Search</span></li>
		<li><?php dbem_rss_link() ?></li>
	</ul>
</div>
<div id="events-page" class="events-page-stuff">

	<?php echo $content ?>
</div><!---eventspage -->