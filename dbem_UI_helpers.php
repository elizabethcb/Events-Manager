<?php 
function dbem_option_items( $array, $saved_value) {
	$output = "";
	foreach($array as $key => $item) {    
		$selected ='';
		if ($key == $saved_value)
			$selected = "selected='selected'";
		$output .= "<option value='$key' $selected >$item</option>\n";
	
	} 
	echo $output;
}

function dbem_checkbox_items($name, $array, $saved_values, $horizontal = true) { 
	$output = "";
	foreach($array as $key => $item) {
		
		$checked = "";
		if (in_array($key, $saved_values))
			$checked = "checked='checked'";  
		$output .=  "<input type='checkbox' name='$name' value='$key' $checked /> $item ";
		if(!$horizontal)	
			$output .= "<br/>\n";
	}
	echo $output;
	
}

function dbem_options_input_text($opts, $title, $name, $description) {
	?>
	<tr valign="top" id="<?php echo $name;?>_row">
		<th scope="row"><?php _e($title, 'dbem') ?></th>
	    <td>
			<input name="<?php echo $name ?>" type="text" id="<?php echo $title ?>" style="width: 95%" value="<?php echo $opts[$name]; ?>" size="45" /><br />
						<?php _e($description, 'dbem') ?>
			</td>
		</tr>
	<?php
}
function dbem_options_input_password($opts, $title, $name, $description) {
	?>
	<tr valign="top" id="<?php echo $name;?>_row">
		<th scope="row"><?php _e($title, 'dbem') ?></th>
	    <td>
			<input name="<?php echo $name ?>" type="password" id="<?php echo $title ?>" style="width: 95%" value="<?php echo $opts[$name]; ?>" size="45" /><br />
						<?php echo $description; ?>
			</td>
		</tr>
	<?php
}

function dbem_options_textarea($opts, $title, $name, $description) {
	?>
	<tr valign="top" id="<?php echo $name;?>_row">
		<th scope="row"><?php _e($title,'dbem')?></th>
			<td><textarea name="<?php echo $name ?>" id="<?php echo $name ?>" rows="6" cols="60"><?php echo $opts[$name];?></textarea><br/>
				<?php echo $description; ?></td>
		</tr>
	<?php
}

function dbem_options_radio_binary($opts, $title, $name, $description) {
		$list_events_page = $opts[$name]; ?>
		 
	   	<tr valign="top" id="<?php echo $name;?>_row">
	   		<th scope="row"><?php _e($title,'dbem'); ?></th>
	   		<td>   
				<input id="<?php echo $name ?>_yes" name="<?php echo $name ?>" type="radio" value="1" <?php if($list_events_page) echo "checked='checked'"; ?> /><?php _e('Yes'); ?> <br />
				<input  id="<?php echo $name ?>_no" name="<?php echo $name ?>" type="radio" value="0" <?php if(!$list_events_page) echo "checked='checked'"; ?> /><?php _e('No'); ?> <br />
				<?php echo $description; ?>
			</td>
	   	</tr>
<?php	
}  
function dbem_options_select($opts, $title, $name, $list, $description) {
		$option_value = $opts[$name]; ?>
	 
	   	<tr valign="top" id="<?php echo $name;?>_row">
	   		<th scope="row"><?php _e($title,'dbem'); ?></th>
	   		<td>   
				<select name="<?php echo $name; ?>" > 
					<?php foreach($list as $key => $value) {   
	 					"$key" == $option_value ? $selected = "selected='selected' " : $selected = '';       
	          echo "<option value='$key' $selected>$value</option>";
				  } ?>
				</select> <br/>
				<?php echo $description; ?>
			</td>
	   	</tr>
<?php	
}

?>