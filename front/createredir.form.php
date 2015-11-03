<?php

include("../../../inc/includes.php");
require_once("../inc/ticketredir.class.php");


if(isset($_GET['id']) && 
		preg_match('/^([\d]+)-([\d]+)-([\d]+)-([\d]+)$/', $_GET['id'], $res)) {
	
	$profile = $res[1];
	$entity = $res[2];
	$type = $res[3];
	$category = $res[4];
	
	if($profile != $_SESSION['glpiactiveprofile']['id']) {
		Session::changeProfile($profile);
	}
	if($entity != $_SESSION['glpiactive_entity']['id']) {
		Session::changeActiveEntities($entity);
	}
	
	$_SESSION['saveInput']['Ticket'] = array(
			'itilcategories_id'   => $category,
			'urgency'             => 1,
			'entities_id'         => $_SESSION['glpiactive_entity'],
			'type'                => $type);
}

if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk') {
	Html::redirect($CFG_GLPI["root_doc"]."/front/helpdesk.public.php?create_ticket=1");
} else {
	Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php");
}
