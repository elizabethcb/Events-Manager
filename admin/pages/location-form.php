<div class="wrap">
	<div id="poststuff">
		<div id="icon-edit" class="icon32">
			<br/>
		</div>
			
		<h2><?php echo __("Edit location", "dbem") ?></h2>   
		
		<?php if($message != "") : ?>
			<div id="message" class="updated fade below-h2" style="background-color: rgb(255, 251, 204);">
				<p><?php  echo $message ?></p>
			</div>
		<?php endif; ?>
		<div id="ajax-response"></div>

		<form enctype="multipart/form-data" name="editcat" id="editcat" method="post" action="<?php echo DBEM_ADMIN_MENU_URI ?>" class="validate">
		<input type="hidden" name="action" value="editedlocation" />
		<input type="hidden" name="location_id" value="<?php echo $location["location_id"] ?>"/>
		
			<table class="form-table">
				<tr class="form-field form-required">
					<th scope="row" valign="top"><label for="location_name"><?php echo __("Location name", "dbem") ?></label></th>
					<td><input name="location[location_name]" id="location-name" type="text" value="<?php echo $location["location_name"] ?>" size="40"  /><br />
				   <?php echo __("The name of the location", "dbem") ?></td>
				</tr>

				<tr class="form-field">
					<th scope="row" valign="top"><label for="address"><?php echo __("Location address", "dbem") ?></label></th>
					<td><input name="location[address]" id="location-address" type="text" value="<?php echo $location["address"] ?>" size="40" /><br />
					<?php echo __("The address of the location", "dbem") ?>.</td>

				</tr>
				
				<tr class="form-field">
					<th scope="row" valign="top"> <label for="town"><?php echo __("Location town", "dbem") ?></label></th>
					<td><input name="location[town]" id="location-town" type="text" value="<?php echo $location["town"] ?>" size="40" /><br />
					<?php echo __("The town where the location is located", "dbem") ?>.</td>

				</tr>
				
				 <tr style="display:none;">
				  <td>Coordinates</td>
					<td><input id="location-latitude" name="location[latitude]" id="location_latitude" type="text" value="<?php echo $location["latitude"] ?>" size="15"  />
					<input id="location-longitude" name="locationlongitude]" id="location_longitude" type="text" value="<?php echo $location["longitude"] ?>" size="15"  /></td>
				 </tr>
				 
				 <?php
					$gmap_is_active = get_option("dbem_gmap_is_active");
					if ($gmap_is_active) {  
				 ?>
				<tr>
					<th scope="row" valign="top"><label for="location_map"><?php echo __("Location map", "dbem") ?></label></th>
					<td>
						<div id="map-not-found" style="width: 450px; font-size: 140%; text-align: center; margin-top: 100px; display: hide"><p><?php echo __("Map not found") ?></p></div>
						<div id="event-map" style="width: 450px; height: 300px; background: green; display: hide; margin-right:8px"></div>
					</td>
				</tr>
				<?php
					}
				?>
				<tr class="form-field">
					<th scope="row" valign="top"><label for="location_description"><?php _e("Location description", "dbem") ?></label></th>
					<td>
						<div class="inside">
							<div id="<?php echo user_can_richedit() ? "postdivrich" : "postdiv"; ?>" class="postarea">
								<?php the_editor($location["description"]); ?>
							</div>
							<?php _e("A description of the Location. You may include any kind of info here.", "dbem") ?>
						</div>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row" valign="top"><label for="location_picture"><?php echo __("Location image", "dbem") ?></label></th>
					<td>
						<?php if ($location["location_image_url"] != "") : ?> 
							<img src="<?php echo $location["location_image_url"] ?>" alt="<?php echo $location["location_name"] ?>"/>
						<?php else : ?> 
							<?php echo __("No image uploaded for this location yet", "debm") ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top"><label for="location_image"><?php echo __("Upload/change picture", "dbem") ?></label></th>
					<td><input id="location-image" name="location_image" id="location_image" type="file" size="40" /></td>
				</tr>
			</table>
			<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php echo __("Update location", "dbem") ?>" /></p>
		</form>
	</div>
</div>