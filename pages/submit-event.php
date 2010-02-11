<div class="event-sub-form">
<?php if ($error != '') { ?>
	<div class="event-error-message">
		<?php echo $error; ?>
	</div>
<?php }
	if ($message != '') { ?>
	<div class="event-message">
		<?php echo $message; ?>
	</div>
<?php } ?>
<form action="<?php echo get_bloginfo('url') . '/events/?submitevent=1' ?>">
	<input type="hidden" name="front-action" value="submit-event" />
	<div class="ev-messages right">
		Description:
		<textarea cols="32" rows="14">
		Ok.  So Chrome shows this much narrower and shorter than Firefox.
		</textarea>
	</div>
	<div class="left">

	Name of Event: <br />
	<input type="text" name="event[name]" /> 
	<br /><br />
	Start Time: <input type="text" size="10" class="ev-datetime" name="event[start_time]" /> 
	End Time: <input type="text" size="10" name="event[end_time]" /><br />
	<br />
	Start Date: <input type="text" size="10" class="ev-datetime" name="event[start_date]" /> 
	End Date: <input type="text" size="10" name="event[end_date]" /> <br />
	(end date optional)<br />
	<br />
	Location: <br />
	(copy the code from the admin section)<br />
	<input type="text" name="location[location_name]" /> <br />
	Add fields for address etc if new.
	<br />
	Event Host's Name: <br />
	(Provide dropdown/AJAX list again)<br />
	<input type="text" /> <br />
	<br />
	Event Phone: <br />
	<input type="text" /> <br />
	<br />
	Event Email: <br />
	<input type="text" /> <br />
	<br />
	Event Website: <br />
	<input type="text" /> <br />
	<br />
<div class="event-form-buttons right">Yeah.  There's no submit button.</div>
</form>
</div>
<br class="clear" />
</div>