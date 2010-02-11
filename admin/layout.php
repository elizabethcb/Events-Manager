<h1>Events Administration</h1>

<?php if ( ! empty($error)) { ?>
	<p class="dbem-alert">
		<strong>
			<?php echo htmlspecialchars($error) ?>
			<a class="dbem-cancel" href="#">cancel</a>
		</strong>
	</p>
<?php } ?>

<div class="events-content">
	<?php echo $content ?>
</div>

<?php
//date_default_timezone_set('Europe/London');

//if (date_default_timezone_get()) {
//    echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
//}

//if (ini_get('date.timezone')) {
//    echo 'date.timezone: ' . ini_get('date.timezone');
//}
?>