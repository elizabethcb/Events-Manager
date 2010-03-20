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
	<div class="right">
		<div class="ev-timedate">
			Start Date: <input type="text" size="10" class="ev-datetime" name="event[start_date]" /> 
			End Date: <input type="text" size="10" name="event[end_date]" /> <br />
			<b style="display:block; text-align:right; margin-right:20px;">(end date optional)</b><br />
			Start Time: <input type="text" size="10" class="ev-datetime" name="event[start_time]" /> 
			End Time: <input type="text" size="10" name="event[end_time]" /><br />
			
			<br />
		</div>
		<div class="ev-messages">
		
			Description:
			<textarea>
			</textarea>
		</div>
	</div>
	<div class="left">

	Name of Event:
	<input type="text" name="event[name]" /> 
	<br />
	
	Location: 
	<input type="text" name="location[location_name]" /> <br />
	
	Address: 
	<input type="text" name="location[address]" /> <br />
	
	City: <br />
	<input type="text" name="location[city]" /> <br />
	
	State: 
	<input type="text" name="location[state]" /> <br />
	
	
	Event Host's Name: 
	<input type="text" /> <br />
	
	Event Phone: 
	<input type="text" /> <br />
	
	Event Email: 
	<input type="text" /> <br />
	
	Event Website: 
	<input type="text" /> <br />
	
<div class="event-form-buttons"><input type="button" name="submit" value="submit this" /></div>
</form>
</div>
<br class="clear" />
</div>