<?php
	
	//require_once(DBEM_DOCROOT . 'classes/dbem-events-admin-class.php');
	$dbemadmin = new DBEM_Events_Admin;

	//echo $dbemadmin->template->content;
	$dbemadmin->template->render();
	
?>