<?php  
$feedback_message = "";
 
$location_required_fields = array("location_name" => __('The location name', 'dbem'), "location_address" => __('The location address', 'dbem'), "location_town" => __('The location town', 'dbem'));


function dbem_intercept_locations_actions() {
	if(isset($_GET['page']) && $_GET['page'] == "locations") {
  	if(isset($_GET['doaction2']) && $_GET['doaction2'] == "Delete") {
	  	if(isset($_GET['action2']) && $_GET['action2'] == "delete") {
				$locations = $_GET['locations'];
				foreach($locations as $location_ID) {
				 	dbem_delete_location($location_ID);
				}
			}
		}
	}
}

// Moved to admin
function dbem_locations_page() {      

	if(isset($_GET['action']) && $_GET['action'] == "edit") { 
		// edit location  
		$location_id = $_GET['location_ID'];
		$location = dbem_get_location($location_id);
		dbem_locations_edit_layout($location);
  } else { 
    if(isset($_POST['action']) && $_POST['action'] == "editedlocation") { 
		
			// location update required  
			$location = array();
			$location['location_id'] = $_POST['location_ID'];
			$location['location_name'] = $_POST['location_name'];
			$location['location_address'] = $_POST['location_address']; 
			$location['location_town'] = $_POST['location_town']; 
			$location['location_latitude'] = $_POST['location_latitude'];
			$location['location_longitude'] = $_POST['location_longitude'];
			$location['location_description'] = $_POST['content'];
			
			if(empty($location['location_latitude'])) {
				$location['location_latitude']  = 0;
				$location['location_longitude'] = 0;
			}
			
			$validation_result = dbem_validate_location($location);
			if ($validation_result == "OK") {
				  
				dbem_update_location($location); 
				    
			  if ($_FILES['location_image']['size'] > 0 )
					dbem_upload_location_picture($location);
				$message = __('The location has been updated.', 'dbem');
				
				$locations = dbem_get_locations();
				dbem_locations_table_layout($locations, $message);
			} else {
				$message = $validation_result;   
				dbem_locations_edit_layout($location, $message);
			}
		} elseif(isset($_POST['action']) && $_POST['action'] == "addlocation") {    
				$location = array();
				$location['location_name'] = $_POST['location_name'];
				$location['location_address'] = $_POST['location_address'];
				$location['location_town'] = $_POST['location_town']; 
				$location['location_latitude'] = $_POST['location_latitude'];
				$location['location_longitude'] = $_POST['location_longitude'];
				$location['location_description'] = $_POST['content'];
				$validation_result = dbem_validate_location($location);
				if ($validation_result == "OK") {   
					$new_location = dbem_insert_location($location);   
		 			// uploading the image
				 
					if ($_FILES['location_image']['size'] > 0 ) {
						dbem_upload_location_picture($new_location);
			    }
					
					 
					
					
					
					
					// -------------
					
					//RESETME $message = __('The location has been added.', 'dbem'); 
					$locations = dbem_get_locations();
					dbem_locations_table_layout($locations, null,$message);
				} else {
					$message = $validation_result;
					$locations = dbem_get_locations();
					   
					dbem_locations_table_layout($locations, $location, $message);
				}
				
				
				
			} else {  
			// no action, just a locations list
			$locations = dbem_get_locations();
			dbem_locations_table_layout($locations, $message);
  	}
	} 
}  

// Moved to admin
function dbem_locations_edit_layout($location, $message = "") {
	?>
	<div class='wrap'>
		<div id="poststuff">
			<div id='icon-edit' class='icon32'>
				<br/>
			</div>
				
			<h2><?php echo __('Edit location', 'dbem') ?></h2>   
	 		
			<?php if($message != "") : ?>
				<div id='message' class='updated fade below-h2' style='background-color: rgb(255, 251, 204);'>
					<p><?php  echo $message ?></p>
				</div>
			<?php endif; ?>
			<div id='ajax-response'></div>
	
			<form enctype='multipart/form-data' name='editcat' id='editcat' method='post' action='admin.php?page=locations' class='validate'>
			<input type='hidden' name='action' value='editedlocation' />
			<input type='hidden' name='location_ID' value='<?php echo $location['location_id'] ?>'/>
			
				<table class='form-table'>
					<tr class='form-field form-required'>
						<th scope='row' valign='top'><label for='location_name'><?php echo __('Location name', 'dbem') ?></label></th>
						<td><input name='location_name' id='location-name' type='text' value='<?php echo $location['location_name'] ?>' size='40'  /><br />
			           <?php echo __('The name of the location', 'dbem') ?></td>
					</tr>
	
					<tr class='form-field'>
						<th scope='row' valign='top'><label for='location_address'><?php echo __('Location address', 'dbem') ?></label></th>
						<td><input name='location_address' id='location-address' type='text' value='<?php echo $location['location_address'] ?>' size='40' /><br />
			            <?php echo __('The address of the location', 'dbem') ?>.</td>
	
					</tr>
					
					<tr class='form-field'>
						<th scope='row' valign='top'> <label for='location_town'><?php echo __('Location town', 'dbem') ?></label></th>
						<td><input name='location_town' id='location-town' type='text' value='<?php echo $location['location_town'] ?>' size='40' /><br />
			            <?php echo __('The town where the location is located', 'dbem') ?>.</td>
	
					</tr>
				    
					 <tr style='display:none;'>
					  <td>Coordinates</td>
						<td><input id='location-latitude' name='location_latitude' id='location_latitude' type='text' value='<?php echo $location['location_latitude'] ?>' size='15'  />
						<input id='location-longitude' name='location_longitude' id='location_longitude' type='text' value='<?php echo $location['location_longitude'] ?>' size='15'  /></td>
					 </tr>
					 
					 <?php
						$gmap_is_active = get_option('dbem_gmap_is_active');
						if ($gmap_is_active) {  
					 ?>
					<tr>
				 		<th scope='row' valign='top'><label for='location_map'><?php echo __('Location map', 'dbem') ?></label></th>
						<td>
							<div id='map-not-found' style='width: 450px; font-size: 140%; text-align: center; margin-top: 100px; display: hide'><p><?php echo __('Map not found') ?></p></div>
		 					<div id='event-map' style='width: 450px; height: 300px; background: green; display: hide; margin-right:8px'></div>
		 				</td>
		 			</tr>
		 			<?php
						}
					?>
					<tr class='form-field'>
						<th scope='row' valign='top'><label for='location_description'><?php _e('Location description', 'dbem') ?></label></th>
						<td>
							<div class="inside">
								<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
									<?php the_editor($location['location_description']); ?>
								</div>
								<?php _e('A description of the Location. You may include any kind of info here.', 'dbem') ?>
							</div>
						</td>
					</tr>
					<tr class='form-field'>
						<th scope='row' valign='top'><label for='location_picture'><?php echo __('Location image', 'dbem') ?></label></th>
						<td>
							<?php if ($location['location_image_url'] != '') : ?> 
								<img src='<?php echo $location['location_image_url'] ?>' alt='<?php echo $location['location_name'] ?>'/>
							<?php else : ?> 
								<?php echo __('No image uploaded for this location yet', 'debm') ?>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope='row' valign='top'><label for='location_image'><?php echo __('Upload/change picture', 'dbem') ?></label></th>
						<td><input id='location-image' name='location_image' id='location_image' type='file' size='40' /></td>
					</tr>
				</table>
				<p class='submit'><input type='submit' class='button-primary' name='submit' value='<?php echo __('Update location', 'dbem') ?>' /></p>
			</form>
		</div>
	</div>
	<?php
}

// Moved to admin
function dbem_locations_table_layout($locations, $new_location, $message = "") {
	$destination = get_bloginfo('url')."/wp-admin/admin.php";
	$new_location = (is_array($new_location)) ? $new_location : array();
	ob_start();
	?>
		<div class='wrap nosubsub'>
			<div id='icon-edit' class='icon32'>
				<br/>
			</div>
 	 		<h2><?php echo __('Locations', 'dbem') ?></h2>  
	 		
			<?php if($message != "") : ?>
				<div id='message' class='updated fade below-h2' style='background-color: rgb(255, 251, 204);'>
					<p><?php echo $message ?></p>
				</div>
			<?php endif; ?>
			<div id='col-container'>
				<div id='col-right'>
			 	 <div class='col-wrap'>       
				 	 <form id='bookings-filter' method='get' action='<?php echo $destination ?>'>
						<input type='hidden' name='page' value='locations'/>
						<input type='hidden' name='action' value='edit_location'/>
						<input type='hidden' name='event_id' value='<?php echo $event_id ?>'/>
						
						<?php if (count($locations)>0) : ?>
						<table class='widefat'>
							<thead>
								<tr>
									<th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='1'/></th>
									<th><?php echo __('Name', 'dbem') ?></th>
									<th><?php echo __('Address', 'dbem') ?></th>
									<th><?php echo __('Town', 'dbem') ?></th>                
								</tr> 
							</thead>
							<tfoot>
								<tr>
									<th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='1'/></th>
									<th><?php echo __('Name', 'dbem') ?></th>
									<th><?php echo __('Address', 'dbem') ?></th>
									<th><?php echo __('Town', 'dbem') ?></th>      
								</tr>             
							</tfoot>
							<tbody>
								<?php foreach ($locations as $this_location) : ?>	
								<tr>
									<td><input type='checkbox' class ='row-selector' value='<?php echo $this_location['location_id'] ?>' name='locations[]'/></td>
									<td><a href='<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=locations&amp;action=edit&amp;location_ID=<?php echo $this_location['location_id'] ?>'><?php echo $this_location['location_name'] ?></a></td>
									<td><?php echo $this_location['location_address'] ?></td>
									<td><?php echo $this_location['location_town'] ?></td>                         
								</tr>
								<?php endforeach; ?>
							</tbody>

						</table>

						<div class='tablenav'>
							<div class='alignleft actions'>
							<input type='hidden' name='action2' value='delete'/>
						 	<input class='button-secondary action' type='submit' name='doaction2' value='Delete'/>
							<br class='clear'/> 
							</div>
							<br class='clear'/>
						</div>
						<?php else: ?>
							<p><?php echo __('No venues have been inserted yet!', 'dbem') ?></p>
						<?php endif; ?>
						</form>
					</div>
				</div>  <!-- end col-right -->     
				
				<div id='col-left'>
			  	<div class='col-wrap'>
						<div class='form-wrap'> 
							<div id='ajax-response'/>
					  	<h3><?php echo __('Add location', 'dbem') ?></h3>
							 <form enctype='multipart/form-data' name='addlocation' id='addlocation' method='post' action='admin.php?page=locations' class='add:the-list: validate'>
							 		
									<input type='hidden' name='action' value='addlocation' />
							    <div class='form-field form-required'>
							      <label for='location_name'><?php echo __('Location name', 'dbem') ?></label>
								 	<input id='location-name' name='location_name' id='location_name' type='text' value='<?php echo $new_location['location_name'] ?>' size='40' />
								    <p><?php echo __('The name of the location', 'dbem') ?>.</p>
								 </div>
               
								 <div class='form-field'>
								   <label for='location_address'><?php echo __('Location address', 'dbem') ?></label>
								 	<input id='location-address' name='location_address' id='location_address' type='text' value='<?php echo $new_location['location_address'] ?>' size='40'  />
								    <p><?php echo __('The address of the location', 'dbem') ?>.</p>
								 </div>
               
								 <div class='form-field '>
								   <label for='location_town'><?php echo __('Location town', 'dbem') ?></label>
								 	<input id='location-town' name='location_town' id='location_town' type='text' value='<?php echo $new_location['location_town'] ?>' size='40'  />
								    <p><?php echo __('The town of the location', 'dbem') ?>.</p>
								 </div>   
								
							     <div class='form-field' style='display:none;'>
								   <label for='location_latitude'>LAT</label>
								 	<input id='location-latitude' name='location_latitude' type='text' value='<?php echo $new_location['location_latitude'] ?>' size='40'  />
								 </div>
								 <div class='form-field' style='display:none;'>
								   <label for='location_longitude'>LONG</label>
								 	<input id='location-longitude' name='location_longitude' type='text' value='<?php echo $new_location['location_longitude'] ?>' size='40'  />
								 </div>
								
								 <div class='form-field'>
								   <label for='location_image'><?php echo __('Location image', 'dbem') ?></label>
								 	<input id='location-image' name='location_image' id='location_image' type='file' size='35' />
								    <p><?php echo __('Select an image to upload', 'dbem') ?>.</p>
								 </div>
								 <?php 
									$gmap_is_active = get_option('dbem_gmap_is_active');
                 					if ($gmap_is_active) :
								 ?>	
						 		 	<div id='map-not-found' style='width: 450px; font-size: 140%; text-align: center; margin-top: 20px; display: hide'><p><?php echo __('Map not found') ?></p></div>
							 		<div id='event-map' style='width: 450px; height: 300px; background: green; display: hide; margin-right:8px'></div>
							 		<br style='clear:both;' />   
								 <?php endif; ?>
									<div id="poststuff">
										<label for='location_description'><?php _e('Location description', 'dbem') ?></label>
										<div class="inside">
											<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
												<?php the_editor($new_location['location_description']); ?>
											</div>
											<?php _e('A description of the Location. You may include any kind of info here.', 'dbem') ?>
										</div>
									</div>               
								 <p class='submit'><input type='submit' class='button' name='submit' value='<?php echo __('Add location', 'dbem') ?>' /></p>
							 </form>   

					  </div>
					</div> 
				</div>  <!-- end col-left -->   
			</div> 
  	</div>
  	<?php
	echo ob_get_clean();  
}

	 
// Moved to events
function dbem_get_locations($eventful = false) { 
	global $wpdb;
	$locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
	$events_table = $wpdb->prefix.EVENTS_TBNAME;
	if ($eventful == 'true') {
		$sql = "SELECT * from $locations_table JOIN $events_table ON $locations_table.location_id = $events_table.location_id";
	} else {
		$sql = "SELECT location_id, location_address, location_name, location_town,location_latitude, location_longitude 
			FROM $locations_table ORDER BY location_name";   
	}

	$locations = $wpdb->get_results($sql, ARRAY_A); 
	return $locations;  

}

// Moved to events
function dbem_get_location($location_id) { 
	global $wpdb;
	$locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
	$sql = "SELECT * FROM $locations_table WHERE location_id ='$location_id'";   
  $location = $wpdb->get_row($sql, ARRAY_A);
	$location['location_image_url'] = dbem_image_url_for_location_id($location['location_id']);
	return $location;  

}

function dbem_image_url_for_location_id($location_id) {
	$file_name= ABSPATH.IMAGE_UPLOAD_DIR."/location-".$location_id;
  $mime_types = array('gif','jpg','png');foreach($mime_types as $type) { 
		$file_path = "$file_name.$type";
		if (file_exists($file_path)) {
			$result = get_bloginfo('url')."/".IMAGE_UPLOAD_DIR."/location-$location_id.$type";
  		return $result;
		}
	}
	return '';
}

// Moved to admin
function dbem_get_identical_location($location) { 
	global $wpdb;
	
	$locations_table = $wpdb->prefix.LOCATIONS_TBNAME; 
	//$sql = "SELECT * FROM $locations_table WHERE location_name ='".$location['location_name']."' AND location_address ='".$location['location_address']."' AND location_town ='".$location['location_town']."';";   
  $prepared_sql=$wpdb->prepare("SELECT * FROM $locations_table WHERE location_name = %s AND location_address = %s AND location_town = %s", $location['location_name'], $location['location_address'], $location['location_town'] );
	//$wpdb->show_errors(true);
	$cached_location = $wpdb->get_row($prepared_sql, ARRAY_A);
	return $cached_location;  

}

function dbem_validate_location($location) {
	global $location_required_fields;
	$troubles = "";
	foreach ($location_required_fields as $field => $description) {
		if ($location[$field] == "" ) {
		$troubles .= "<li>".$description.__(" is missing!", "dbem")."</li>";
		}       
	}
	if ($_FILES['location_image']['size'] > 0 ) { 
		if (is_uploaded_file($_FILES['location_image']['tmp_name'])) {
 	 		$mime_types = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
			$maximum_size = get_option('dbem_image_max_size'); 
			if ($_FILES['location_image']['size'] > $maximum_size) 
	     	$troubles = "<li>".__('The image file is too big! Maximum size:', 'dbem')." $maximum_size</li>";
	  	list($width, $height, $type, $attr) = getimagesize($_FILES['location_image']['tmp_name']);
			$maximum_width = get_option('dbem_image_max_width'); 
			$maximum_height = get_option('dbem_image_max_height'); 
	  	if (($width > $maximum_width) || ($height > $maximum_height)) 
	     	$troubles .= "<li>". __('The image is too big! Maximum size allowed:')." $maximum_width x $maximum_height</li>";
	  	if (($type!=1) && ($type!=2) && ($type!=3)) 
		      $troubles .= "<li>".__('The image is in a wrong format!')."</li>";
  	} 
	}

  if ($troubles == "")
		return "OK";
	else {
		$message = __('Ach, some problems here:', 'dbem')."<ul>\n$troubles</ul>";
		return $message; 
	}
}

// Moved to admin
function dbem_update_location($location) {
	global $wpdb;
	$locations_table = $wpdb->prefix.LOCATIONS_TBNAME;
	$sql="UPDATE ".$locations_table. 
	" SET location_name='".$location['location_name']."', ".
		"location_address='".$location['location_address']."',".
		"location_town='".$location['location_town']."', ".
		"location_latitude=".$location['location_latitude'].",". 
		"location_longitude=".$location['location_longitude'].",".
		"location_description='".$location['location_description']."' ". 
		"WHERE location_id='".$location['location_id']."';";  
	$wpdb->query($sql);      

}   

// Moved to admin
function dbem_insert_location($location) {    
 
		global $wpdb;	
		$table_name = $wpdb->prefix.LOCATIONS_TBNAME; 
		// if GMap is off the hidden fields are empty, so I add a custom value to make the query work
		if (empty($location['location_longitude'])) 
			$location['location_longitude'] = 0;
		if (empty($location['location_latitude'])) 
			$location['location_latitude'] = 0;
		$sql = "INSERT INTO ".$table_name." (location_name, location_address, location_town, location_latitude, location_longitude, location_description)
		VALUES ('".$location['location_name']."','".$location['location_address']."','".$location['location_town']."',".$location['location_latitude'].",".$location['location_longitude'].",'".$location['location_description']."')"; 
		$wpdb->query($sql);
    $new_location = dbem_get_location(mysql_insert_id());            

		return $new_location;
}

function dbem_delete_location($location) {
		global $wpdb;	
		$table_name = $wpdb->prefix.LOCATIONS_TBNAME;
		$sql = "DELETE FROM $table_name WHERE location_id = '$location';";
		$wpdb->query($sql);
    dbem_delete_image_files_for_location_id($location);
}          

function dbem_location_has_events($location_id) {
	global $wpdb;	
	$events_table = $wpdb->prefix.EVENTS_TBNAME;
	$sql = "SELECT event_id FROM $events_table WHERE location_id = $location_id";   
 	$affected_events = $wpdb->get_results($sql);
	return (count($affected_events) > 0);
}             

function dbem_upload_location_picture($location) {
  	if(!file_exists("../".IMAGE_UPLOAD_DIR))
				mkdir("../".IMAGE_UPLOAD_DIR, 0777);
	dbem_delete_image_files_for_location_id($location['location_id']);
	$mime_types = array(1 => 'gif', 2 => 'jpg', 3 => 'png');    
	list($width, $height, $type, $attr) = getimagesize($_FILES['location_image']['tmp_name']);
	$image_path = "../".IMAGE_UPLOAD_DIR."/location-".$location['location_id'].".".$mime_types[$type];
	if (!move_uploaded_file($_FILES['location_image']['tmp_name'], $image_path)) 
		$msg = "<p>".__('The image could not be loaded','dbem')."</p>";
}    
function dbem_delete_image_files_for_location_id($location_id) {
	$file_name= "../".IMAGE_UPLOAD_DIR."/location-".$location_id;
	$mime_types = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
	foreach($mime_types as $type) { 
		if (file_exists($file_name.".".$type))
  		unlink($file_name.".".$type);
	}
}          



function dbem_global_map($atts) {  
	if (get_option('dbem_gmap_is_active') == '1') {
	extract(shortcode_atts(array(
			'eventful' => "false",
			'scope' => 'all',
			'width' => 450,
			'height' => 300
		), $atts));                                  
	$events_page = dbem_get_events_page(true, false);
	$gmaps_key = get_option('dbem_gmap_key');
	$result = "";
	$result .= "<div id='dbem_global_map' style='width: {$width}px; height: {$height}px'>map</div>";
	$result .= "<script type='text/javascript'>
	<!--// 
	  eventful = $eventful;
	  scope = '$scope';
	  events_page = '$events_page';
	  GMapsKey = '$gmaps_key'; 
		location_infos = '$location_infos'
	//-->
	</script>";
	$result .= "<script src='".get_bloginfo('url')."/wp-content/plugins/events-manager/dbem_global_map.js' type='text/javascript'></script>";
	$result .= "<ol id='dbem_locations_list'></ol>"; 
	
	} else {
		$result = "";
	}
	return $result;
}
add_shortcode('locations_map', 'dbem_global_map'); 

function dbem_replace_locations_placeholders($format, $location, $target="html") {
	$location_string = $format;
	preg_match_all("/#@?_?[A-Za-z]+/", $format, $placeholders);
	foreach($placeholders[0] as $result) {    
		// echo "RESULT: $result <br>";
		// matches alla fields placeholder
		if (preg_match('/#_MAP/', $result)) {
		 	$map_div = dbem_single_location_map($location);
		 	$location_string = str_replace($result, $map_div , $location_string ); 
		 
		}
		if (preg_match('/#_PASTEVENTS/', $result)) {
		 	$list = dbem_events_in_location_list($location, "past");
		 	$location_string = str_replace($result, $list , $location_string ); 
		}
		if (preg_match('/#_NEXTEVENTS/', $result)) {
		 	$list = dbem_events_in_location_list($location);
		 	$location_string = str_replace($result, $list , $location_string ); 
		}
		if (preg_match('/#_ALLEVENTS/', $result)) {
		 	$list = dbem_events_in_location_list($location, "all");
		 	$location_string = str_replace($result, $list , $location_string ); 
		}
	  
		if (preg_match('/#_(NAME|ADDRESS|TOWN|PROVINCE|DESCRIPTION)/', $result)) {
			$field = "location_".ltrim(strtolower($result), "#_");
		 	$field_value = $location[$field];      
		
			if ($field == "location_description") {
				if ($target == "html")
					$field_value = apply_filters('dbem_notes', $field_value);
				else
				  if ($target == "map")
					$field_value = apply_filters('dbem_notes_map', $field_value);
				  else
				 	$field_value = apply_filters('dbem_notes_rss', $field_value);
		  	} else {
				if ($target == "html")    
					$field_value = apply_filters('dbem_general', $field_value); 
				else 
					$field_value = apply_filters('dbem_general_rss', $field_value); 
			}
			$location_string = str_replace($result, $field_value , $location_string ); 
	 	}
	  
		if (preg_match('/#_(IMAGE)/', $result)) {
				
        	if($location['location_image_url'] != '')
				  $location_image = "<img src='".$location['location_image_url']."' alt='".$location['location_name']."'/>";
				else
					$location_image = "";
			$location_string = str_replace($result, $location_image , $location_string ); 
		}
	 if (preg_match('/#_(LOCATIONPAGEURL)/', $result)) {
	      $events_page_link = dbem_get_events_page(true, false);
		  if (stristr($events_page_link, "?"))
		  	$joiner = "&amp;";
		  else
		  	$joiner = "?";
		  $venue_page_link = dbem_get_events_page(true, false).$joiner."location_id=".$location['location_id'];
		  $location_string = str_replace($result, $venue_page_link , $location_string ); 
	 }
			
	}
	return $location_string;	
	
}
function dbem_single_location_map($location) {
	$gmap_is_active = get_option('dbem_gmap_is_active'); 
	$map_text = addslashes(dbem_replace_locations_placeholders(get_option('dbem_location_baloon_format'), $location));
	if ($gmap_is_active) {  
   		$gmaps_key = get_option('dbem_gmap_key');
   		$map_div = "<div id='dbem-location-map' style=' background: green; width: 400px; height: 300px'></div>" ;
   		$map_div .= "<script type='text/javascript'>
  			<!--// 
  		latitude = parseFloat('".$location['latitude']."');
  		longitude = parseFloat('".$location['longitude']."');
  		GMapsKey = '$gmaps_key';
  		map_text = '$map_text';
		//-->
		</script>";
		$map_div .= "<script src='".get_bloginfo('url')."/wp-content/plugins/events-manager/dbem_single_location_map.js' type='text/javascript'></script>";
	} else {
		$map_div = "";
	}
	return $map_div;
}

function dbem_events_in_location_list($location, $scope = "") {
	$events = dbem_get_events("",$scope,"","",$location['location_id']);
	$list = "";
	if (count($events) > 0) {
		foreach($events as $event)
			$list .= dbem_replace_placeholders(get_option('dbem_location_event_list_item_format'), $event);
	} else {
		$list = get_option('dbem_location_no_events_message');
	}
	return $list;
}

add_action ('admin_head', 'dbem_locations_autocomplete');  

function dbem_locations_autocomplete() {     
	if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_event') || (isset($_GET['page']) && $_GET['page'] == 'new_event')) { 	 
		?>
		<link rel="stylesheet" href="../wp-content/plugins/events-manager/js/jquery-autocomplete/jquery.autocomplete.css" type="text/css"/>
    

		<script src="../wp-content/plugins/events-manager/js/jquery-autocomplete/lib/jquery.bgiframe.min.js" type="text/javascript"></script>
		<script src="../wp-content/plugins/events-manager/js/jquery-autocomplete/lib/jquery.ajaxQueue.js" type="text/javascript"></script> 

		<script src="../wp-content/plugins/events-manager/js/jquery-autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>

		<script type="text/javascript">
		//<![CDATA[
		$j=jQuery.noConflict();


		$j(document).ready(function() {
			var gmap_enabled = <?php echo get_option('dbem_gmap_is_active'); ?>; 
		 
			$j("input#location-name").autocomplete("../wp-content/plugins/events-manager/locations-search.php", {
				width: 260,
				selectFirst: false,
				formatItem: function(row) {
					item = eval("(" + row + ")");
					return item.name+'<br/><small>'+item.address+' - '+item.town+ '</small>';
				},
				formatResult: function(row) {
					item = eval("(" + row + ")");
					return item.name;
				} 

			});
			$j('input#location-name').result(function(event,data,formatted) {       
				item = eval("(" + data + ")"); 
				$j('input#location-address').val(item.address);
				$j('input#location-town').val(item.town);
				if(gmap_enabled) {   
					eventLocation = $j("input#location-name").val(); 
			    eventTown = $j("input#location-town").val(); 
					eventAddress = $j("input#location-address").val();
					
					loadMap(eventLocation, eventTown, eventAddress)
				} 
			});

		});	
		//]]> 

		</script>

		<?php

	}
}


function dbem_cache_location($event){
	$related_location = dbem_get_location_by_name($event['location_name']);  
	if (!$related_location) {
		dbem_insert_location_from_event($event);
		return;
	} 
	if ($related_location->location_address != $event['location_address'] || $related_location->location_town != $event['location_town']  ) {
		dbem_insert_location_from_event($event);
	}      

}     

function dbem_get_location_by_name($name) {
	global $wpdb;	
	$sql = "SELECT location_id, 
	location_name, 
	location_address,
	location_town
	FROM ".$wpdb->prefix.LOCATIONS_TBNAME.  
	" WHERE location_name = '$name'";   
	$event = $wpdb->get_row($sql);	

	return $event;
}   

function dbem_insert_location_from_event($event) {
	global $wpdb;	
	$table_name = $wpdb->prefix.LOCATIONS_TBNAME;
	$wpdb->query("INSERT INTO ".$table_name." (location_name, location_address, location_town)
	VALUES ('".$event['location_name']."', '".$event['location_address']."','".$event['location_town']."')");

}