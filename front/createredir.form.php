<?php

include("../../../inc/includes.php");


if(isset($_GET['id']) && preg_match('/^1(p([\d]+))?(e([\d]+))?(t([12])(c([\d]+))?)?$/', $_GET['id'], $matches)) {
	if(isset($matches[2]) && $matches[2]!='' &&  $matches[2]!= $_SESSION['glpiactiveprofile']['id']) {
		Session::changeProfile($matches[2]);
		
		if($_SESSION['glpiactiveprofile']['id'] != $matches[2]) {
			Session::addMessageAfterRedirect(__('Unable to select the right profile. Either this link is poorly deseigned, either it was not meant for you!', 'smartredirect'), true, ERROR);
			redirect();
		}
	}
	
	if(isset($matches[4]) && $matches[4]!='' &&  $matches[4]!= $_SESSION['glpiactive_entity']) {
		Session::changeActiveEntities($matches[4]);
		
		if($_SESSION['glpiactive_entity'] != $matches[4]) {
			Session::addMessageAfterRedirect(__('Unable to select the right entity. Either this link is poorly deseigned, either it was not meant for you!', 'smartredirect'), true, ERROR);
			redirect();
		}
	}
	
	$input = array();
	if(isset($matches[6]) && $matches[6]!='') {
		$input['type'] = $matches[6];
	}
	
	if(isset($matches[8]) && $matches[8]!='') {
		if(checkCategory($matches[8], $input)) {
		$input['itilcategories_id'] = $matches[8];
		} else {
			Session::addMessageAfterRedirect(__('Category have not been selected, please correct it...', 'smartredirect'), true, ERROR);
		}
	}
	
	$_SESSION['saveInput']['Ticket'] = $input;
} else {
	Session::addMessageAfterRedirect(__('Error parsing redirect information', 'smartredirect').'. '.__('Please inform the one who gave you this link that it is badly formed', 'smartredirect'), true, ERROR);
}

redirect();





function redirect() {
	global $CFG_GLPI;
	if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk') {
		Html::redirect($CFG_GLPI["root_doc"]."/front/helpdesk.public.php?create_ticket=1");
	} else {
		Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php");
	}
	die();
}

function checkCategory($id, &$input) {
	$category = new ITILCategory();
	if($category->getFromDB($id)) {
		
		// Vérification de la compatibilité de la catégorie avec l'entité courante
		if($category->isRecursive()) {
			if(!in_array($category->getEntityID(), getAncestorsOf("glpi_entities", $_SESSION['glpiactive_entity'])))
				return false;
		} else {
			if($category->getEntityID() != $_SESSION['glpiactive_entity'])
				return false;
		}
		
		// vérification de la compatibilité de la catégorie avec le type sélectionné
		if($input['type']==Ticket::INCIDENT_TYPE && !$category->fields['is_incident'])
			return false;
		if($input['type']==Ticket::DEMAND_TYPE && !$category->fields['is_request'])
			return false;
		
		if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk' && !$category->fields['is_helpdeskvisible'])
			return false;
		
		return true;
	} else return false;
}





















