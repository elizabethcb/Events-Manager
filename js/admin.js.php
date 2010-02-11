   // TODO: make more general, to support also latitude and longitude (when added)
$j=jQuery.noConflict();   

function updateIntervalDescriptor () { 
	$j(".interval-desc").hide();
	var number = "-plural";
	if ($j('input#recurrence-interval').val() == 1 || $j('input#recurrence-interval').val() == "")
	number = "-singular"
	var descriptor = "span#interval-"+$j("select#recurrence-frequency").val()+number;
	$j(descriptor).show();
}
function updateIntervalSelectors () {
	$j('p.alternate-selector').hide();   
	$j('p#'+ $j('select#recurrence-frequency').val() + "-selector").show();
	//$j('p.recurrence-tip').hide();
	//$j('p#'+ $j(this).val() + "-tip").show();
}
function updateShowHideRecurrence () {
	if($j('input#event-recurrence').attr("checked")) {
		$j("#event_recurrence_pattern").fadeIn();
		/* Marcus Begin Edit */
		//Edited this and the one below so dates always can have an end date
		//$j("input#localised-end-date").fadeIn();
		/* Marcus End Edit */ 
		$j("#event-date-explanation").hide();
		$j("#recurrence-dates-explanation").show();
		$j("h3#recurrence-dates-title").show();
		$j("h3#event-date-title").hide();     
	} else {
		$j("#event_recurrence_pattern").hide();
		/* Marcus Begin Edit */
		//$j("input#localised-end-date").hide();
		/* Marcus End Edit */ 
		$j("#recurrence-dates-explanation").hide();
		$j("#event-date-explanation").show();
		$j("h3#recurrence-dates-title").hide();
		$j("h3#event-date-title").show();   
	}
}

function updateShowHideRsvp () {
	if($j('input#rsvp-checkbox').attr("checked")) {
		$j("div#rsvp-data").fadeIn();
	} else {
		$j("div#rsvp-data").hide();
	}
}

$j(document).ready( function() {
	locale_format = "ciao";
 
	$j("#recurrence-dates-explanation").hide();
	$j("#localised-date").show();
	$j("#localised-end-date").show();

	$j("#date-to-submit").hide();
	$j("#end-date-to-submit").hide(); 
	$j("#localised-date").datepicker($j.extend({},
		($j.datepicker.regional["it"], 
		{altField: "#date-to-submit", 
		altFormat: "yy-mm-dd"})));
	$j("#localised-end-date").datepicker($j.extend({},
		($j.datepicker.regional["it"], 
		{altField: "#end-date-to-submit", 
		altFormat: "yy-mm-dd"})));

 	//$j("#start-time").timeEntry({spinnerImage: '', show24Hours: ''});
  	//$j("#end-time").timeEntry({spinnerImage: '', show24Hours:''});


	$j('input.select-all').change(function(){
	 	if($j(this).is(':checked'))
	 	$j('input.row-selector').attr('checked', true);
	 	else
	 	$j('input.row-selector').attr('checked', false);
	}); 

	 updateIntervalDescriptor(); 
	 updateIntervalSelectors();
	 updateShowHideRecurrence();  
	 updateShowHideRsvp();
	 $j('input#event-recurrence').change(updateShowHideRecurrence);  
	 $j('input#rsvp-checkbox').change(updateShowHideRsvp);   
	 // recurrency elements   
	 $j('input#recurrence-interval').keyup(updateIntervalDescriptor);
	 $j('select#recurrence-frequency').change(updateIntervalDescriptor);
	 $j('select#recurrence-frequency').change(updateIntervalSelectors);
    
	 // hiding or showing notes according to their content	
	 $j('.postbox h3').prepend('<a class="togbox">+</a> ');
	 // if($j("textarea[@name=event_notes]").val()!="") {
	 	//    $j("textarea[@name=event_notes]").parent().parent().removeClass('closed');
	 	// }
	$j('#event_notes h3').click( function() {
		   $j($j(this).parent().get(0)).toggleClass('closed');
    });

   // users cannot submit the event form unless some fields are filled
   	function validateEventForm(){
   		errors = "";
		var recurring = $j("input[@name=repeated_event]:checked").val();
		requiredFields= new Array('event_name', 'localised_event_date', 'location_name','location_address','location_town');
		var localisedRequiredFields = {
			'event_name'			:'Name', 
			'localised_event_date'	:'Date', 
			'location_name'			:'Location',
			'location_address'		:'Address',
			'location_town'			:'Town'
		};
		
		missingFields = new Array;
		for (var i in requiredFields) {
			if ($j("input[@name=" + requiredFields[i]+ "]").val() == 0) {
				missingFields.push(localisedRequiredFields[requiredFields[i]]);
				$j("input[@name=" + requiredFields[i]+ "]").css('border','2px solid red');
			} else {
				$j("input[@name=" + requiredFields[i]+ "]").css('border','1px solid #DFDFDF');
				
			}
				
	   	}
	
		// 	alert('ciao ' + recurring+ " end: " + $j("input[@name=localised_event_end_date]").val());     
	   	if (missingFields.length > 0) {
	
		    errors = 'Some required fields are missing:' + missingFields.join(", ") + ".\n";
		}
		if(recurring && $j("input[@name=localised_event_end_date]").val() == "") {
			errors = errors +  "Since the event is repeated, you must specify an end date"; 
			$j("input[@name=localised_event_end_date]").css('border','2px solid red');
		} else {
			$j("input[@name=localised_event_end_date]").css('border','1px solid #DFDFDF');
		}
		if(errors != "") {
			alert(errors);
			return false;
		}
		return true; 
   }
   
   $j('#eventForm').bind("submit", validateEventForm);
	eventLocation = $j("input#location-name").val(); 
	eventTown = $j("input#location-town").val(); 
	eventAddress = $j("input#location-address").val();


	$j("input#location-name").blur(function(){
			newEventLocation = $j("input#location-name").val();  
			if (newEventLocation !=eventLocation) {                
				loadMap(newEventLocation, eventTown, eventAddress); 
				eventLocation = newEventLocation;
		   
			}
	});
	$j("input#location-town").blur(function(){
			newEventTown = $j("input#location-town").val(); 
			if (newEventTown !=eventTown) {  
				loadMap(eventLocation, newEventTown, eventAddress); 
				eventTown = newEventTown;
				} 
	});
	$j("input#location-address").blur(function(){
			newEventAddress = $j("input#location-address").val(); 
			if (newEventAddress != eventAddress) {
				loadMap(eventLocation, eventTown, newEventAddress);
				eventAddress = newEventAddress; 
			}
	});
  
   	
  
});