<?php

include("../../../inc/includes.php");
require_once("../inc/ticketredir.class.php");

if(!isset($_GET['id'])) {
	if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk') {
		Html::redirect($CFG_GLPI["root_doc"]."/front/helpdesk.public.php");
	} else {
		Html::redirect($CFG_GLPI["root_doc"]."/front/central.php");
	}
}

$user_id = Session::getLoginUserID();
$ticket_id = $_GET['id'];

$config = PluginSmartredirectConfig::getConfigValues();

// calcule et redirige, puis si nécessaire élargit le champ des entités
if($config['is_activated']) {
	$roles = PluginSmartredirectTicketredir::getRoles($user_id, $ticket_id);
	$profile_id = $config[$roles];
	
	if($profile_id != $_SESSION['glpiactiveprofile']['id']) {
		Session::changeProfile($profile_id);
	}
	
	$ticket = new Ticket();
	$ticket->getFromDB($ticket_id);
	if (!Session::haveAccessToEntity($ticket->getEntityID())) {
		Session::changeActiveEntities("all");
	}
}

// renvoi vers le ticket lui-même
if(!isset($_GET['forcetab'])) {
	Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php?id=".$ticket_id);
} else if ($_GET['forcetab'] == 'DocumentItem$1') { // Gestion du cas particulier du document (qui ne passe pas tel quel car contient un _)
	Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php?id=".$ticket_id."&forcetab=Document_Item$1");
} else {
	Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php?id=".$ticket_id."&forcetab=".$_GET['forcetab']);
}



