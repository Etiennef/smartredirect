<?php

include("../../../inc/includes.php");

$isValid = false;

if(isset($_GET['id'])) {
	if(preg_match('/^(p([\d]+))?(e([\d]+))?(t([\d]+)(c([\d]+))?)?$/', $_GET['id'], $res)) {
		$isValid = true;
		
		$profile = isset($res[2]) && $res[2]!=''?$res[2]:$_SESSION['glpiactiveprofile']['id'];
		$entity = isset($res[2]) && $res[4]!=''?$res[4]:$_SESSION['glpiactive_entity'];
		
		if(isset($res[6]) && $res[6]!='') {
			$type = $res[6];
		}
		if(isset($res[8]) && $res[8]!='') {
			$category = $res[8];
		}
	}
	
}

if($isValid) {
	if($profile != $_SESSION['glpiactiveprofile']['id']) {
		Session::changeProfile($profile);
	}
	if($entity != $_SESSION['glpiactive_entity']) {
		Session::changeActiveEntities($entity);
	}
	
	$input = array();
	
	if(isset($type)) {
		$input['type'] = $type;
	}
	
	if(isset($category)) {
		$input['itilcategories_id'] = $category;
	}
	
	$_SESSION['saveInput']['Ticket'] = $input;
}

if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk') {
	Html::redirect($CFG_GLPI["root_doc"]."/front/helpdesk.public.php?create_ticket=1");
} else {
	Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php");
}
