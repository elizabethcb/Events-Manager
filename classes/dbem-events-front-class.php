<?php
/* Front Events Class */

class DBEM_Front_Events extends DBEM_Events_Admin {

	protected $admin = '';
	public $page_link;
	
	public function __construct($doi = false) {
		parent::__construct(true);
		if ($doi) 
			return;
		$this->page_link = get_permalink ( $this->opts["dbem_events_page"] );
		// For sub pages, I will end up here.  So I must call the appropriate function
		// event-single, locations list, location-single.
		// location=1, event_id=ev_id, location_id=loc_id

	}
	
	public function filter_events_page($data) {
		//echo "<pre>";
		//print_r($this);
		//echo "</pre>";
		$is_events_post = (get_the_ID () == $this->opts['events_page']);
		// Really ugly.
		// Really really ugly.
		if (is_page ( $this->opts['events_page'] ) && $is_events_post) {
			if( isset($_GET['locations']) && 1 == $_GET['locations'] ) {
				$this->get_locations_page();
			} elseif ( isset($_GET['event_id']) && $_GET['event_id'] > 0 ) {
				$this->get_single_event_page($_GET['event_id']);
			} elseif ( isset($_GET['location_id']) && $_GET['location_id'] > 0 ) {
				$this->get_single_location_page($_GET['location_id']);
			} elseif ( isset($_GET['submitevent']) && 1 == $_GET['submitevent'] ) {
				$this->get_submit_event_page();
			} elseif ( isset($_GET['submitevent']) && 1 == $_GET['submitevent']
				&& isset($_POST['front-action']) && 'submit-event' == $_POST['front-action']  ) {
				$this->submit_event_front();
			} else {
				$this->action_events();
			}
			return $this->template->render(true);
		} else {
			return $data;
		}
	}
	
	public function get_single_event_page($event_id = 0) {
		$content = new Events_Template(DBEM_DOCROOT . 'pages/event-single.php');
		$content->event = $this->get_event($event_id); // At the moment, will get all future events
		$location = array();
		foreach (array('location_id', 'location_name', 'longitude', 'latitude', 'address', 'town', 'province', 'description') as $key ) {
			$location[$key] = $content->event[$key];
		}
		$content->map = $this->get_single_location_map($location);
		// $location = '';
		$this->template->content = $content;
	}
	
	public function get_single_location_page($location_id = 0) {
		$content = new Events_Template(DBEM_DOCROOT . 'pages/location-single.php');
		$content->location = $this->get_location($location_id); // At the moment, will get all locations
		$content->events = $this->get_events_by_location($content->location);
		$content->map = $this->get_single_location_map($content->location);
		$this->template->content = $content;
	}
	
	public function get_locations_page() {
		$content = new Events_Template(DBEM_DOCROOT . 'pages/locations.php');


		$locations = $this->get_locations();
		for ($i = 0; $i < count($locations); $i++ ) {
			$locations[$i]['events'] = $this->get_events_by_location($locations[$i]);
		}
		//echo '<pre>';
		//print_r($locations);
		//echo '</pre>';
		$content->locations = $locations;
		// Standard.
		$content->opts = $this->opts;
		$this->template->content = $content;
	}
	
	public function get_submit_event_page() {
		$content = new Events_Template(DBEM_DOCROOT . 'pages/submit-event.php');
		if ($this->error)
			$content->error = $this->error;
		if ($this->message)
			$content->message = $this->message;
		$this->template->content = $content;
	}
	
	public function submit_event_front() {
		$event_id = $this->action_update_event();
		if ($event_id && $event_id > 0) {
			$this->get_single_event_page($event_id);
		} else {
			$this->get_submit_event_page();
		}
	}
	
}
?>