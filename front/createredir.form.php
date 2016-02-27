<?php

include("../../../inc/includes.php");
$config = PluginSmartredirectPluginconfig::getConfigValues();

if(isset($_GET['id']) && preg_match('@^1(p([\d]+))?e([\d]+)?t([12])c([\d]+)$@', $_GET['id'], $matches)) {
	
	// Changement du profil
	if(isset($matches[2]) && $matches[2]!='' &&  $matches[2]!= $_SESSION['glpiactiveprofile']['id']) {
		Session::changeProfile($matches[2]);
		if($_SESSION['glpiactiveprofile']['id'] != $matches[2]) {
			Session::addMessageAfterRedirect(__('Unable to select the right profile. Either this link is poorly deseigned, either it was not meant for you!', 'smartredirect'), true, ERROR);
			redirect($config['url_profile_error']);
		}
	}
	
	// Changement de l'entité
	if(isset($matches[3]) && $matches[3]!='' &&  $matches[3]!= $_SESSION['glpiactive_entity']) {
		Session::changeActiveEntities($matches[3]);
		
		if($_SESSION['glpiactive_entity'] != $matches[3]) {
			Session::addMessageAfterRedirect(__('Unable to select the right entity. Either this link is poorly deseigned, either it was not meant for you!', 'smartredirect'), true, ERROR);
			redirect($config['url_entity_error']);
		}
	}
	
	// Réglage du type et de la catégorie
	$input = array();
	if(isset($matches[4]) && $matches[4]!='' && isset($matches[5]) && $matches[5]!='') {
		$input['type'] = $matches[4];
	
		if(checkCategory($matches[5], $input)) {
			$input['itilcategories_id'] = $matches[5];
		} else {
			Session::addMessageAfterRedirect(__('Category have not been selected, please correct it...', 'smartredirect'), true, ERROR);
			redirect($config['url_category_error']);
		}
	}
	
	$_SESSION['saveInput']['Ticket'] = $input;
	redirect($config['url_success']);
} else {
	Session::addMessageAfterRedirect(__('Error parsing redirect information', 'smartredirect').'. '.__('Please inform the one who gave you this link that it is badly formed', 'smartredirect'), true, ERROR);
	redirect($config['url_syntax_error']);
}



function redirect($url) {
	global $CFG_GLPI;
	
	if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk') {
		$redir = "/front/helpdesk.public.php?create_ticket=1";
	} else {
		$redir = "/front/ticket.form.php";
	}
	
	if(!empty($url) && $url[0]!='&') {
		Html::redirect($url);
	} else {
		if(!empty($url) && !preg_match('@\?@', $redir))
			$url[0] = '?'; // transformation du & en ? si nécessaire
		Html::redirect($CFG_GLPI["root_doc"].$redir.$url);
	}
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
		
		// vérification de la visibilité de l'entité en mode helpdesk si celui-ci est sélectionné
		if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk' && !$category->fields['is_helpdeskvisible'])
			return false;
		
		return true;
	} else return false;
}





















