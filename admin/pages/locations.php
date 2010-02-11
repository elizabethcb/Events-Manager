<?php

$new_location = (is_array($new_location)) ? $new_location : array();
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
		<?php if($error != "") : ?>
		<div id='message' class='updated fade below-h2' style='background-color: rgb(200, 251, 204);'>
			<p><?php echo $error ?></p>
		</div>
	<?php endif; ?>
	<div id='col-container'>
		<div id='col-right'>
		 <div class='col-wrap'>       
			 <form id='bookings-filter' method='get' action='<?php echo DBEM_ADMIN_MENU_URI ?>'>
				<input type='hidden' name='page' value='locations'/>
				<input type='hidden' name='action' value='delete-location'/>
				<input type='hidden' name='event_id' value='<?php echo $event_id ?>'/>
				
				<?php if (count($locations)>0) : ?>
				<table class='widefat'>
					<thead>
						<tr>
							<th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='select-all'/></th>
							<th><?php echo __('Name', 'dbem') ?></th>
							<th><?php echo __('Address', 'dbem') ?></th>
							<th><?php echo __('Town', 'dbem') ?></th>                
						</tr> 
					</thead>
					<tfoot>
						<tr>
							<th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='select-all'/></th>
							<th><?php echo __('Name', 'dbem') ?></th>
							<th><?php echo __('Address', 'dbem') ?></th>
							<th><?php echo __('Town', 'dbem') ?></th>      
						</tr>             
					</tfoot>
					<tbody>
						<?php foreach ($locations as $this_location) : ?>	
						<tr>
							<td><input type='checkbox' class ='row-selector' value='<?php echo $this_location['location_id'] ?>' name='locations[]'/></td>
							<td><a href='<?php echo DBEM_ADMIN_MENU_URI ?>&amp;action=edit-location&amp;location_id=<?php echo $this_location['location_id'] ?>'><?php echo $this_location['location_name'] ?></a></td>
							<td><?php echo $this_location['address'] ?></td>
							<td><?php echo $this_location['town'] ?></td>                         
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
					<div id='ajax-response'></div>
				<h3><?php echo __('Add location', 'dbem') ?></h3>
					 <form enctype='multipart/form-data' name='addlocation' id='addlocation' method='post' action='<?php DBEM_ADMIN_MENU_URI ?>' class='add:the-list: validate'>
							
							<input type='hidden' name='action' value='update-location' />
						<div class='form-field form-required'>
						  <label for='location-name'><?php echo __('Location name', 'dbem') ?></label>
							<input id='location-name' name='location[location_name]' type='text' value='<?php echo $new_location['location_name'] ?>' size='40' />
							<p><?php echo __('The name of the location', 'dbem') ?>.</p>
						 </div>
	   
						 <div class='form-field'>
						   <label for='location-address'><?php echo __('Location address', 'dbem') ?></label>
							<input id='location-address' name='location[address]' type='text' value='<?php echo $new_location['address'] ?>' size='40'  />
							<p><?php echo __('The address of the location', 'dbem') ?>.</p>
						 </div>
	   
						 <div class='form-field '>
						   <label for='location-town'><?php echo __('Location town', 'dbem') ?></label>
							<input id='location-town' name='location[town]' type='text' value='<?php echo $new_location['town'] ?>' size='40'  />
							<p><?php echo __('The town of the location', 'dbem') ?>.</p>
						 </div>   
						
						 <div class='form-field' style='display:none;'>
						   <label for='location-latitude'>LAT</label>
							<input id='location-latitude' name='location[latitude]' type='text' value='<?php echo $new_location['latitude'] ?>' size='40'  />
						 </div>
						 <div class='form-field' style='display:none;'>
						   <label for='location-longitude'>LONG</label>
							<input id='location-longitude' name='location[longitude]' type='text' value='<?php echo $new_location['longitude'] ?>' size='40'  />
						 </div>
						
						 <div class='form-field'>
						   <label for='location-image'><?php echo __('Location image', 'dbem') ?></label>
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
								<label for='location-description'><?php _e('Location description', 'dbem') ?></label>
								<div class="inside">
									<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
										<?php the_editor($new_location['description']); ?>
									</div>
									<?php _e('A description of the Location. You may include any kind of info here.', 'dbem') ?>
								</div>
							</div>               
						 <p class='submit'><input type='submit' class='button' name='submit' value='<?php echo __('Add location', 'dbem') ?>' /></p>
					 </form>   

			  </div>
			</div> 
		</div>  <!--//end col-left -->   
	</div> 
</div>
