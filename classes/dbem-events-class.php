<?php
// Events Manager Class

class DBEM_Events {

	// Localised date formats as in the jquery UI datepicker plugin
	public $loc_date_form = array(
		"am" => "d.m.y", "ar" => "d/m/y", "bg" => "d.m.y", "ca" => "m/d/y", "cs" => "d.m.y", 
		"da" => "d-m-y", "de" =>"d.m.y", "es" => "d/m/y", "en" => "m/d/y", "fi" => "d.m.y", 
		"fr" => "d/m/y", "he" => "d/m/y", "hu" => "y-m-d", "hy" => "d.m.y", "id" => "d/m/y", 
		"is" => "d/m/y", "it" => "d/m/y", "ja" => "y/m/d", "ko" => "y-m-d", "lt" => "y-m-d", 
		"lv" => "d-m-y", "nl" => "d.m.y", "no" => "y-m-d", "pl" => "y-m-d", "pt" => "d/m/y", 
		"ro" => "m/d/y", "ru" => "d.m.y", "sk" => "d.m.y", "sv" => "y-m-d", "th" => "d/m/y", 
		"tr" => "d.m.y", "ua" => "d.m.y", "uk" => "d.m.y", "us" => "m/d/y", "CN" => "y-m-d", 
		"TW" => "y/m/d");

	protected $tables;

	public $opts; 
	
	function __construct($doi = false) {
		// some variables that needs to be instatiated
		// new FrontUsers(blah, blah, blah
		// __construct(blah = default, blah = default, blah = default
		
		$this->opts = get_option('dbem');
		$this->tables = array(
			'events' 		=> 'dbem_events',
			'recurrence'	=> 'dbem_recurrence',  
			'locations'		=> 'dbem_locations',  
			'bookings'		=> 'dbem_bookings',
			'people'		=> 'dbem_people',  
			'booking_ppl' 	=> 'dbem_bookings_people'
		);
		
		if ($doi) 
			return;

	}
	
	public function get_events($limit = false, $scope = "future", $order = "ASC", $offset = '', $location_id = false, $category = false) {
	/* Marcus End Edit */
		global $wpdb;
		$events_table = $wpdb->prefix . $this->tables['events'];
		if ($limit && intval($limit) < 200)
			$limit = "LIMIT ". intval($limit);
		if ($offset != '' && intval($offset) < 200)
			$offset = "OFFSET " . intval($offset);
		$order = ($order != 'ASC') ? "DESC" : "ASC"; 
		
		$today = date( 'Y-m-d');
		
		$conditions = array ();
		if (preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $scope )) {
			$conditions [] = " ev.start_date LIKE '$scope'";
		} else {
			switch ($scope) {
				case "past" :
					$conditions[] = " ev.start_date < '$today' ";
				case "future" :
					$conditions [] = " (ev.start_date >= '$today' OR (rec.end_date >= '$today' AND rec.end_date != '0000-00-00'))";
			}
		}
		
		if ($location_id && $location_id > 0)
			$conditions [] = " ev.location_id = $location_id";
			
		/* Marcus Begin Edit */
		if ($category && $category > 0){
			$conditions [] = " ev.category_id = $category";
		}
		/* Marcus End Edit */
		
		$where = implode ( " AND ", $conditions );
		if ($where != "")
			$where = " WHERE " . $where;

		$sql = "SELECT ev.event_id, 
					ev.name,
					ev.start_date,
					rec.end_date,
					ev.start_time,
					ev.end_time,
					ev.notes, 
					ev.rsvp,
					ev.location_id, 
					ev.contactperson_id,
					loc.location_name,
					loc.address,
					loc.town,
					rec.interval,
					rec.byweekno,
					rec.freq,
					rec.byday,
					rec.recurrence_id					
					FROM " . $wpdb->prefix . $this->tables['events'] . " AS ev
					LEFT JOIN " . $wpdb->prefix . $this->tables['recurrence'] . " AS rec 
					ON ev.event_id = rec.event_id
					LEFT JOIN " . $wpdb->prefix . $this->tables['locations'] . " AS loc
					ON ev.location_id = loc.location_id
					$where
					ORDER BY ev.start_date $order, ev.start_time $order
					$limit 
					$offset";
		
		$events = $wpdb->get_results ( $sql, ARRAY_A );
		//echo "<pre>";
		//print_r( $events );
		//echo "</pre>";
		if ($events) {
			return $events;
		} else {
			return false;
		}
	}
	
	public function get_event($event_id) {
		global $wpdb;
		
		// For recurrence
		// TODO Figure out why the table ids are not returned with 
		// SELECT *  Whackadoodle.  Especially since entering the query
		// directly into mysql returns everything.  does wordpress strip ids?
		$events_table = $wpdb->prefix . $this->tables['events'];
		$sql = "SELECT ev.event_id, 
					ev.name,
					ev.start_date,
					rec.end_date,
					ev.start_time,
					ev.end_time,
					ev.notes, 
					ev.rsvp,
					ev.location_id, 
					ev.contactperson_id,
					loc.location_name,
					loc.address,
					loc.town,
					loc.latitude,
					loc.longitude,
					rec.interval,
					rec.byweekno,
					rec.freq,
					rec.byday,
					rec.recurrence_id			
				FROM $events_table AS ev
				LEFT JOIN " . $wpdb->prefix . $this->tables['recurrence'] . " AS rec
				ON ev.event_id = rec.event_id
				LEFT JOIN " . $wpdb->prefix . $this->tables['locations'] . " AS loc
				ON ev.location_id = loc.location_id
				WHERE ev.event_id = $event_id";

		$event = $wpdb->get_row ( $sql, ARRAY_A );
		if ($event) {
			//echo "<pre>";
			//print_r($event);
			//echo "</pre>";
			return $event;
		} else {
			return false;
		}
	}
	
	//==========================================================
	// Locations
	//==========================================================
		 
	
	public function get_locations($eventful = false) { 
		global $wpdb;
		$locations_table = $wpdb->prefix . $this->tables['locations']; 
		$events_table = $wpdb->prefix . $this->tables['events'];
		if ($eventful == 'true') {
			$sql = "SELECT * from $locations_table NATURAL JOIN $events_table";
		} else {
			$sql = "SELECT * FROM $locations_table ORDER BY location_name";   
		}
	
		return $wpdb->get_results($sql, ARRAY_A); 
	
	}
	
	public function get_location($location_id) { 
		global $wpdb;
		$locations_table = $wpdb->prefix . $this->tables['locations']; 
		$sql = $wpdb->prepare(
			"SELECT * FROM $locations_table WHERE location_id=%d",
			$location_id);   
	  	$location = $wpdb->get_row($sql, ARRAY_A);
		//$location['location_image_url'] = dbem_image_url_for_location_id($location['location_id']);
		return $location;  
	
	}
	
	public function get_events_by_location($location, $scope = "") {
		return $this->get_events("",$scope,"","",$location['location_id']);
	}
	
	// TODO Test images
	protected function image_url_for_location_id($location_id) {
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
	
  	// TODO What's this for?	
	public function location_has_events($location_id) {
		global $wpdb;	
		$events_table = $wpdb->prefix . $this->tables['events'];
		$sql = "SELECT event_id FROM $events_table WHERE location_id = $location_id";   
		$affected_events = $wpdb->get_results($sql);
		return (count($affected_events) > 0);
	}             
  
  	// TODO er....
	public function get_events_page($justurl = false, $echo = true, $text = '') {
		if (strpos ( $justurl, "=" )) {
			// allows the use of arguments without breaking the legacy code
			$defaults = array ('justurl' => 0, 'text' => '', 'echo' => 1 );
			
			$r = wp_parse_args ( $justurl, $defaults );
			extract ( $r, EXTR_SKIP );
			$justurl = $r ['justurl'];
			$text = $r ['text'];
			$echo = $r ['echo'];
		}
		
		$page_link = get_permalink ( $this->opts['dbem_events_page'] );
		if ($justurl) {
			$result = $page_link;
		} else {
			if ($text == '')
				$text = $this->opts['dbem_events_page_title'];
				$result = "<a href='$page_link' title='$text'>$text</a>";
		}
		if ($echo)
			echo $result;
		else
			return $result;

	}
	

	public function get_global_map($atts) {  
		if ($this->opts['gmap_is_active'] == '1') {
			extract(shortcode_atts(array(
					'eventful' => "false",
					'scope' => 'all',
					'width' => 450,
					'height' => 300
				), $atts));                                  
			$events_page = $this->get_events_page(true, false);
			$gmaps_key = $this->opts['gmap_key'];
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
			$result .= "<script src='". DBEM_URI . "dbem_global_map.js' type='text/javascript'></script>";
			$result .= "<ol id='dbem_locations_list'></ol>"; 
		
		} else {
			$result = "";
		}
		return $result;
	}
	
	public function get_single_location_map($location) {
		$gmap_is_active = $this->opts['gmap_is_active']; 
		$map_text = addslashes($this->replace_locations_placeholders($this->opts['location_baloon_format'], $location));
		if ($gmap_is_active) {  
			$gmaps_key = $this->opts['gmap_key'];
			$map_div = '<div id="dbem-location-map" style="background: green; width: 400px; height: 300px"></div>' ;
			$map_div .= '<script type="text/javascript">
				<!--// 
			latitude = parseFloat("' . $location['latitude'] . '");
			longitude = parseFloat("' . $location['longitude'] . '");
			GMapsKey = "' . $gmaps_key . '";
			map_text = "' . $map_text . '";
			//-->
			</script>';
			$map_div .= '<script src="'. DBEM_URI . 'js/dbem_single_location_map.js" type="text/javascript"></script>';
		} else {
			$map_div = "";
		}
		return $map_div;
	}
	
	public function get_events_in_location_list($location, $scope = "") {
		$events = $this->get_events("",$scope,"","",$location['location_id']);
		$list = "";
		if (count($events) > 0) {
			foreach($events as $event)
				$list .= dbem_replace_placeholders($this->opts['location_event_list_item_format'], $event);
		} else {
			$list = $this->opts['dbem_location_no_events_message'];
		}
		return $list;
	}
	
 
	
	public function locations_autocomplete() {     
		if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_event')) { 	 
			?>
			<link rel="stylesheet" href="<?php echo DBEM_URI ?>js/jquery-autocomplete/jquery.autocomplete.css" type="text/css"/>
		
	
			<script src="<?php echo DBEM_URI ?>js/jquery-autocomplete/lib/jquery.bgiframe.min.js" type="text/javascript"></script>
			<script src="<?php echo DBEM_URI ?>js/jquery-autocomplete/lib/jquery.ajaxQueue.js" type="text/javascript"></script> 
	
			<script src="<?php echo DBEM_URI ?>js/jquery-autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
	
			<script type="text/javascript">
			//<![CDATA[
			$j=jQuery.noConflict();
	
	
			$j(document).ready(function() {
				var gmap_enabled = <?php echo $this->opts['dbem_gmap_is_active']; ?>; 
			 
				$j("input#location-name").autocomplete(DBEM_URI . "locations-search.php", {
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
	
	
	public function dbem_cache_location($event){
		$related_location = dbem_get_location_by_name($event['location_name']);  
		if (!$related_location) {
			dbem_insert_location_from_event($event);
			return;
		} 
		if ($related_location->location_address != $event['address'] || $related_location->town != $event['town']  ) {
			dbem_insert_location_from_event($event);
		}      
	
	}     
	
	// needs to be more flexible.
	public function dbem_get_location_by_name($name) {
		global $wpdb;	
		$sql = "SELECT location_id, 
		location_name, 
		address,
		town
		FROM ".$wpdb->prefix . $this->tables['locations'] .  
		" WHERE location_name = '$name'";   
		$event = $wpdb->get_row($sql);	
	
		return $event;
	}   
	
	public function dbem_insert_location_from_event($event) {
		global $wpdb;	
		$table_name = $wpdb->prefix . $this->tables['locations'];
		$wpdb->query("INSERT INTO ".$table_name." (location_name, address, town)
		VALUES ('".$event['location_name']."', '".$event['address']."','".$event['town']."')");
	
	}
	
	protected function replace_locations_placeholders($format, $location, $target="html") {
	$location_string = $format;
	preg_match_all("/#@?_?[A-Za-z]+/", $format, $placeholders);
	foreach($placeholders[0] as $result) {    
		// echo "RESULT: $result <br>";
		// matches alla fields placeholder
		if (preg_match('/#_MAP/', $result)) {
		 	$map_div = $this->get_single_location_map($location);
		 	$location_string = str_replace($result, $map_div , $location_string ); 
		 
		}
		if (preg_match('/#_PASTEVENTS/', $result)) {
		 	$list = $this->get_events_by_location($location, "past");
		 	$location_string = str_replace($result, $list , $location_string ); 
		}
		if (preg_match('/#_NEXTEVENTS/', $result)) {
		 	$list = $this->get_events_by_location($location);
		 	$location_string = str_replace($result, $list , $location_string ); 
		}
		if (preg_match('/#_ALLEVENTS/', $result)) {
		 	$list = $this->get_events_by_location($location, "all");
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
	      $events_page_link = $this->get_events_page(true, false);
		  if (stristr($events_page_link, "?"))
		  	$joiner = "&amp;";
		  else
		  	$joiner = "?";
		  $venue_page_link = $this->get_events_page(true, false).$joiner."location_id=".$location['location_id'];
		  $location_string = str_replace($result, $venue_page_link , $location_string ); 
	 }
			
	}
	return $location_string;	
	
}

}

?>