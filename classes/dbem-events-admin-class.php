<?php
/* Admin Class */

class DBEM_Events_Admin extends DBEM_Events {
	
	public $template;
	
	// To add error messages to templates
	protected $error = false;
	
	// To add messages to templates
	protected $message = false;
	
	protected $location_required_fields;
	
	private $runaction = true;
			
	private $sub_pages = array(
			'Add New' 			=> 'edit-event',
			'Locations' 		=> 'locations',
			'People' 			=> 'people',
			'Event Categories'	=> 'categories',
			'Options'			=> 'options'
		);
		
	protected $page;
	
	protected $admin = 'admin/';
	
	// For image support
	private $tested = false;
	
	public function __construct($doi = false) {
		parent::__construct($doi);
		// Do I just construct and return or initialize the template too?
		if ($doi)
			return;
			
		$this->location_required_fields =  array(
			"location_name" => __('The location name', 'dbem'), 
			"address" 		=> __('The location address', 'dbem'), 
			"town" 			=> __('The location town', 'dbem')
		);
		

		$this->template = new Events_Template(DBEM_DOCROOT . 'admin/layout.php');
		$this->template->content = '';

		$this->page = $_GET['page'];
		if ( empty($this->page) )
			$this->page = 'admin/events-admin.php';
		
		// Strange occurence with options page where the post and get are conflicting.
		// Ah...had not noticed a hidden input field with action.
		if ( preg_match('/^update$/', trim($_POST['action']) ) && isset($_GET['action']) ) {
			$action = 'action_' . $_GET['action'];
		} else {
			$action = 'action_' . ( empty($_REQUEST['action'])  ? 'events' : $_REQUEST['action'] );
		}
		$action = preg_replace( '/-/', '_', $action);

		//echo $action;
		if ( in_array( $action, get_class_methods($this) ) && ('admin/events-admin.php' == $this->page ) ) {
			return $this->$action();
		}
		
			
		return $this;
	}
	
	public function hello_world() {
		$text = "Eh.  What did you say?";
		return $text . "<br />" . $this->opts['dbem_events_page_title'];
	}
	
	public function create_events_menus () {
		$admin_page = 'admin/events-admin.php';
		$tab_title = "Events Manager";
		$func = 'admin_loader';
		$access_level = 'manage_options';

		add_menu_page($tab_title, $tab_title, $access_level, $admin_page, array(&$this, $func));
		
		
		foreach ($this->sub_pages as $title=>$page) {
			add_submenu_page($admin_page, $title, $title, $access_level, $admin_page . "&action=" . $page, array(&$this, $func));
		}

	}
	
	public function admin_loader() {
		//$page = trim($_GET['page']);
		//if ('admin/events-admin.php' == $page ) 
			require_once(DBEM_DOCROOT . 'admin/events-admin.php');
		//} else {
		//	require_once(DBEM_DOCROOT . 'admin/pages/' . $page . '.php');
		//}
	}
	
	// Show events page  Also the default page.
	public function action_events() {
		global $wpdb;
		//$action = $_GET ['action'];
		//$action2 = $_GET ['action2'];
		//$event_id = $_GET ['event_id'];
		//$recurrence_id = $_GET ['recurrence_id'];
		$scope = $_GET ['scope'];
		$offset = $_GET ['offset'];
		$order = $_GET ['order'];

		$content = new Events_Template(DBEM_DOCROOT . $this->admin . 'pages/events.php');


		// Disable Hello to new user if requested
		if (isset ( $_GET ['disable_hello_to_user'] ) && $_GET ['disable_hello_to_user'] == 'true') {
			update_option ( 'dbem_hello_to_user', 0 );
			$this->opts['dbem_hello_to_user'] = 0;
		}
		if ($order == "")
			$order = "ASC";
		if ($offset == "")
			$offset = "0";
	
	// Debug code, to make sure I get the correct page
		switch ($scope) {
			case "past" :
				$content->pagetitle = __ ( 'Past Events', 'dbem' );
				break;
			case "all" :
				$content->pagetitle = __ ( 'All Events', 'dbem' );
				break;
			default :
				$content->pagetitle = __ ( 'Future Events', 'dbem' );
				$scope = "future";
		}
		$limit = 20;
		$content->scope = $scope;
		$content->offset = $offset;
		$content->limit = $limit;
		$content->events = $this->get_events ( $limit, $scope, $order, $offset );
		$content->opts = $this->opts;

		if($this->error)
			$content->error = $this->error;
		if($this->message)
			$content->message = $this->message;
		
		$this->template->content = $content;

	}
	
	private function delete_event($event_id = 0) {
		if (0 == $event_id)
			return;
		global $wpdb;
		$sql = "DELETE FROM " . $wpdb->prefix . $this->tables['events'] . " WHERE event_id='%d'";
		if ( !$wpdb->query ($wpdb->prepare( $sql, $event_id)) ) {
			$this->error .= "<br />There was a problem deleting the event: " . $event_id;
			return false;
		}
		return true;
	}
	
	private function action_delete_events() {	
		// DELETE action
		$selected_events = $_GET['events'];

		// TODO eventual error if ID in non-existant
		//$wpdb->query($sql);
		foreach ( $selected_events as $event_id ) {
			$test = $this->delete_event( $event_id );
		}
		
		$this->action_events();
	}
	
	// Show event form
	protected function action_edit_event() {
		$content = new Events_Template(DBEM_DOCROOT . $this->admin . 'pages/event-form.php');

		$event_id = $_REQUEST['event_id'];


		if (! $event_id) {
			$content->pagetitle = __ ( "Insert New Event", 'dbem' );
		} else {
			$content->event = $this->get_event( $event_id );
			$content->pagetitle = __ ( "Edit Event", 'dbem' ) . " '" . $content->event['name'] . "'";
		}
		
		$content->loc_date_form = $this->loc_date_form;
		$content->opts = $this->opts;

		if ($this->error)
			$content->error = $this->error;
		if ($this->message)
			$content->message = $this->message;

		$this->template->content = $content;

		return $this;
			
	}	
	
	// TODO test
	protected function action_update_event() {// UPDATE or CREATE action
		global $wpdb;
		echo "Post:<pre>";
		print_r($_POST);
		echo "</pre>";
		
		$this->runaction = false;
		$location_id = $this->action_update_location();
		$this->runaction = true;

		// unfiltered event
		$unevent = $_POST['event'];
		// filtered event
		$event = array();
		$event['location_id'] = $location_id;
		$event['name'] = stripslashes ( $unevent['name'] );
		$event['start_date'] = $unevent['start_date']; //strtotime( $unevent['start_date'] );
		
		
		$event ['start_time'] = date ( "G:i:00", strtotime ( $unevent['start_time'] ) );
		$event ['end_time'] = date ( "G:i:00", strtotime ( $unevent['end_time'] ) );
		
		if ( isset($unevent['repeated']) ) {
			$unrec = $_POST['recurrence'];
			$recurrence = array(); 
			$recurrence ['byday'] = $unrec['freq'] == 'weekly' ? 
				implode ( ",", $_POST['recurrence_bydays'] ) : 
				$unrec['byday'];
			$recurrence['freq'] = $unrec['freq'];
			
			$recurrence['interval'] = $unrec['interval'] == "" ?  1 : $unrec['interval'];
			$recurrence ['byweekno'] = $unrec['byweekno'];
			$recurrence['end_date'] = $unrec['end_date']; //date( $this->loc_date_form['en'], $unrec['end_date']);
			$repeated = true;
		} else {
			$repeated = false;
		}

		// Do something with int
		$event['seats'] = $unevent['seats'];
		$event['category_id'] = $unevent['category_id'];
		$event['contactperson_id'] = $unevent['contactperson_id'];

		$event ['notes'] = stripslashes ( $_POST['content'] );


		echo "Location id: $location_id <br /> 
			Unevent, event, unrec, recurrence<pre>";
		print_r($unevent);
		print_r($event);
		print_r($unrec);
		print_r($recurrence);
		echo "</pre><br />unevent, event, unrec recurrence";
		//return;

		//if (! _dbem_is_time_valid ( $event_end_time ))
		//	$event_end_time = $event_time;

		// Bare minimum
		//$validation_result = dbem_validate_event ( $event );
		$validation_result = true;
		
		if ($validation_result) {
			// validation successful  
			// New event or new recurrence
			if ( $event['event_id'] == 0 && !isset($unrec['recurrence_id']) ) {
				// not a repeated event
					//insert new recurrence
				// INSERT new event 
				$test = $wpdb->insert( $wpdb->prefix . $this->tables['events'], $event );
				$event_id = $wpdb->insert_id;
				if ( $test && $test >0 ) { 					
					$this->message = __ ( 'New event successfully inserted!', 'dbem' );
				} else {
					$this->error = "Event insert was unsuccessful";
					if ($this->admin != '') {
						$this->action_edit_event();
					}
					return false;
				}
				if ($repeated && $event_id ) {
					$recurrence['event_id'] = $event_id;
					$rec_id = $wpdb->insert( $wpdb->prefix . $this->tables['recurrence'], $recurrence );
					if ($rec_id && $rec_id > 0) { 
						$this->message = "<br />"  . __ ( 'New recurrent event inserted!', 'dbem' );
					} else {
						$this->error = "<br />" . "Uh oh, there was a problem";
						if ($this->admin != '') {
							$this->action_edit_event();
						}
						return false;
					}
				}				
			// Update of event or recurrence
			} else {
				// UPDATE old event
				// unlink from recurrence in case it was generated by one
				$where ['event_id'] = $event_id = $event['event_id'];
				$test = $wpdb->update ( $wpdb->prefix . $this->tables['events'], $event, $where );
				if ($test) {
					$this->message = "'" . $event ['name'] . "' " . __ ( 'updated', 'dbem' ) . "!" ;
				} else {
					$this->error = "Event wasn't updated as expected";
					if ($this->admin != '') {
						$this->action_edit_event();
					}
					return false;
				}
				
				if ( isset($unrec['recurrence_id']) && $unrec['recurrence_id'] > 0) {
				// Update recurrence
					// UPDATE old recurrence
					//print_r($recurrence);
					$recurrence['event_id'] = $event['event_id'];
					// TODO injection
					$where['recurrence_id'] = $unrec['recurrence_id'];
					$test = $wpdb->update($wpdb->prefix . $this->tables['recurrence'], $recurrence, $where);
					if ($test) {
						$this->message = __ ( 'Recurrence updated!', 'dbem' );
					} else {
						$this->error = __ ( 'Something went wrong with the recurrence update...', 'dbem' );
						if ($this->admin != '') {
							$this->action_edit_event();
						}
						return false;
					}
				} else {
					$this->error = "Was not given a recurrence id";
					if ($this->admin != '') {
						$this->action_edit_event();
					}
					return false;
				}
			}
	
		} else {
			// validation unsuccessful			
			$this->error = __ ( "Ach, there's a problem here:", "dbem" ) . $validation_result;
			if ($this->admin != '') {
				$this->action_edit_event();
			}
			return false;
		}
		// Oh...if there's an error go back to the evennt form.
		// And save the entered event information.
		if ($this->admin != '') {
			$this->action_events();
		}
		return $event_id;
	}
	
	protected function validate_event($event) {
		// Only for emergencies, when JS is disabled
		// TODO make it fully functional without JS
		global $required_fields;
		$errors = Array ();
		foreach ( $required_fields as $field ) {
			if ($event [$field] == "") {
				$errors [] = $field;
			}
		}
		$error_message = "";
		if (count ( $errors ) > 0)
			$error_message = __ ( 'Missing fields: ' ) . implode ( ", ", $errors ) . ". ";
		if ($_POST ['repeated_event'] == "1" && $_POST ['event_end_date'] == "")
			$error_message .= __ ( 'Since the event is repeated, you must specify an event date.', 'dbem' );
		if ($error_message != "")
			return $error_message;
		else
			return "OK";

	}
	


	private function action_options() {
		$content = new Events_Template(DBEM_DOCROOT . 'admin/pages/options.php');
		$content->pagetitle = 'Options';

		$content->opts = $this->opts;
		$this->template->content = $content;
		return $this;
	
	}
	
	private function action_options_update() {
		$mo = $_POST;
		unset($mo['Submit']);
		unset($mo['option_page']);
		unset($mo['action']);
		unset($mo['_wpnonce']);
		unset($mo['_wp_http_referer']);
		//echo "<pre>";
		//print_r($mo);
		//echo "</pre>";
		
		foreach ($mo as $key => $value) {
			$ro[$key] = stripslashes($value);
		}
		//echo "<pre>";
		//print_r($ro);
		//echo "</pre>";
		update_option('dbem', $ro);
		$this->action_options();
	}
	//==========================================================
	// Locations
	//==========================================================
	
	
	// Show all locations default location for locations...er
	protected function action_locations() {      
		$content = new Events_Template(DBEM_DOCROOT . $this->admin . 'pages/locations.php');
		if ($this->error )
			$content->error = $this->error;
			
		$content->locations = $this->get_locations();
		
		// Standard.
		$content->opts = $this->opts;
		$this->template->content = $content;
		return $this;

	}
	
	// Show edit page
	protected function action_edit_location() {
		$content = new Events_Template(DBEM_DOCROOT . $this->admin . 'pages/location-form.php');
		if ($this->error )
			$content->error = $this->error;
		if ($this->message)
			$content->message = $this->message;
		$location_id = $_GET['location_id'];
		$content->location = $this->get_location($location_id);
	
		$content->opts = $this->opts;
		$this->template->content = $content;
		return $this;

	}
	
	// Update or add new
	protected function action_update_location() {      

		$location = $_POST['location'];
		//echo "<pre>";
		//print_r($location);
		//echo "</pre>";
		if ( empty($location['latitude']) ) {
			$location['latitude']  = 0;
			$location['longitude'] = 0;
		}
		
		$validation_result = $this->validate_location($location);
		if ( $validation_result ) {
			$update = false;
			if ( isset($_POST['location_id']) ) {
				$update = true;
				$location['location_id'] = $_POST['location_id'];
				
				$this->update_location($location, $update); 
				
				if ( ($_FILES['location_image']['size'] > 0) && $this->tested ) {
					//dbem_upload_location_picture($location);
					$message = __('The location has been updated.', 'dbem');
				
					$locations = $this->get_locations();
					//dbem_locations_table_layout($locations, $message);
				} else {
					$message = $validation_result;   
					//dbem_locations_edit_layout($location, $message);
				}
				
			} else {    
				$ret = $this->get_identical_location($location);
				if ( isset($ret['location_id']) && $ret['location_id'] > 0 ) {
					$loc_id = $ret['location_id'];
					$this->message = "There was already a location like that";
				} else {
					$loc_id = $this->update_location($location, $update);
					$this->message = "Location updated";
				}
				// uploading the image
			 
				//if ($_FILES['image']['size'] > 0 && $this->tested )
					//dbem_upload_location_picture($new_location);		
			}
		} else {
				//TODO Some success or fail message.
				//$message = __('The location has been added.', 'dbem'); 
			$this->error = "Location not valid";
		}
		if ($this->runaction)
			$this->action_locations();
		if ($loc_id)
			return $loc_id;
		return;
	}
	
		// TODO TEST
	private function update_location($location, $update = false) {
		global $wpdb;
		if ( !is_array($location) ) {
			$this->error = "Was not given a location";
			return false;
		}
		//echo "<pre>";
		//print_r($location);
		//echo "</pre>";
 		$stuff = ' ';
		$list = array();
		if ( $update && isset($location['location_id']) ) {
			$sql = 'UPDATE ';
			foreach ($location as $key => $val ) {
				$list[] = $key . "='" . $val . "'";
			}
			$stuff = implode(',', $list);
			$where = " WHERE location_id=" . $location['location_id'];
			$sql .= $wpdb->prefix . $this->tables['locations'];
		
			$sql .= $stuff . $where;

			$return = $wpdb->query($sql);
		} else {
			// This might not be in the correct order
			// TODO sort
			$return = $wpdb->insert( $wpdb->prefix . $this->tables['locations'],
				$location, array( '%s','%s','%s','%f','%f') ); 
		}
		

		return $return;
	}
	
	// Test to see if location already in db.
	protected function get_identical_location($location) { 
		global $wpdb;
		
		$locations_table = $wpdb->prefix . $this->tables['locations']; 
		//$sql = "SELECT * FROM $locations_table WHERE location_name ='".$location['location_name']."' AND location_address ='".$location['location_address']."' AND location_town ='".$location['location_town']."';";   
	  	$prepared_sql=$wpdb->prepare("SELECT * FROM $locations_table WHERE (location_name = %s OR address = %s) AND town = %s", $location['location_name'], $location['address'], $location['town'] );
		//$wpdb->show_errors(true);
		//echo "<pre>";
		//print_r($prepared_sql);
		$cached_location = $wpdb->get_row($prepared_sql, ARRAY_A);
		//print_r($cached_location);
		//echo "</pre>";
		return $cached_location;  
	
	}
	
	// TODO More validation needed.
	protected function validate_location($location) {
		$troubles = '';
		foreach ($this->location_required_fields as $field => $description) {
			if ($location[$field] == "" ) {
				$troubles .= "<li>".$description.__(" is missing!", "dbem")."</li>";
			}       
		}
		
		if ($_FILES['location_image']['size'] > 0 && $this->tested ) { 
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
	
		if ( $troubles != "" ) {
			$this->error = __('Ach, some problems here:', 'dbem')."<ul>\n$troubles</ul>";
			return false; 
		}
		
		return true;
	}

	// TODO Test image stuff
	private function dbem_upload_location_picture($location) {
		if(!file_exists("../".IMAGE_UPLOAD_DIR))
					mkdir("../".IMAGE_UPLOAD_DIR, 0777);
		dbem_delete_image_files_for_location_id($location['location_id']);
		$mime_types = array(1 => 'gif', 2 => 'jpg', 3 => 'png');    
		list($width, $height, $type, $attr) = getimagesize($_FILES['location_image']['tmp_name']);
		$image_path = "../".IMAGE_UPLOAD_DIR."/location-".$location['location_id'].".".$mime_types[$type];
		if (!move_uploaded_file($_FILES['location_image']['tmp_name'], $image_path)) 
			$msg = "<p>".__('The image could not be loaded','dbem')."</p>";
	}  


	private function delete_location($location_id) {
		global $wpdb;	
		$table_name = $wpdb->prefix . $this->tables['locations'];
		$sql = $wpdb->prepare("DELETE FROM $table_name WHERE location_id = %d", $location_id);
		return $wpdb->query($sql);
		//$this->delete_image_files_for_location_id($location);
	}  
	
	// TODO Test image stuff
	private function delete_image_files_for_location_id($location_id) {
		$file_name= "../".IMAGE_UPLOAD_DIR."/location-".$location_id;
		$mime_types = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
		foreach($mime_types as $type) { 
			if (file_exists($file_name.".".$type))
			unlink($file_name.".".$type);
		}
	} 
	
	/***************************
	 * Other stuff
	*/
	public function feedme($data) {
		//echo "<pre>";
		//print_r($data);
		//echo "</pre>";
		return $data;
	}

	
} // End class

?>