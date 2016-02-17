<?php

include("../../../inc/includes.php");
require_once("../inc/ticketredir.class.php");

$ticket = new Ticket();
if(!isset($_GET['id']) || !$ticket->getFromDB($_GET['id'])) {
	if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk') {
		Html::redirect($CFG_GLPI["root_doc"]."/front/helpdesk.public.php");
	} else {
		Html::redirect($CFG_GLPI["root_doc"]."/front/central.php");
	}
}


var_dump($_GET);

$forcetab = PluginSmartredirectTicketredir::getGLPIForcetabForLinkType(isset($_GET['forcetab']) ? $_GET['forcetab'] : '');
$linktype = $forcetab==='' ? '' : $_GET['forcetab'];


$config = PluginSmartredirectConfig::getConfigValues();
$rules = PluginSmartredirectRule::getRulesValues();



// calcule et redirige, puis si nécessaire élargit le champ des entités
if($config['is_activated']) {
	$user_id = Session::getLoginUserID();
	$roles = PluginSmartredirectTicketredir::getRoles($ticket);
	
	
	
	foreach($rules as $rule) {
		var_dump($rule);
		
		var_dump($linktype);
		//Vérifie le type de lien
		if(!in_array($linktype, $rule['linktypes']))
			continue;
		
		var_dump($ticket->getEntityID());
		//Vérifie le processus
		if(!in_array($ticket->getEntityID(), $rule['entities']))
			continue;
		
		var_dump($roles);
		//Vérifie le rôle
		if(!count(array_intersect($roles, $rule['roles'])))
			continue;
		
		var_dump($ticket->fields['status']);
		//Vérifie le statut
		if(!in_array($ticket->fields['status'], $rule['status']))
			continue;
		
		//Si on arrive ici, c'est que toutes les conditions sont vérifiées, donc on applique la règle
		$profile_id = $rule['profile'];
		var_dump($profile_id);
		if($profile_id != $_SESSION['glpiactiveprofile']['id']) {
			Session::changeProfile($profile_id);
		}
		if (!Session::haveAccessToEntity($ticket->getEntityID())) {
			Session::changeActiveEntities("all");
		}
		
		//Seule la première règle s'applique
		break;
	}
}

// renvoi vers le ticket lui-même
Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php?id=".$ticket->getId()."&forcetab=".$_GET['forcetab']);



