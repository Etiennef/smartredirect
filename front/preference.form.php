<?php
include ("../../../inc/includes.php");
require_once("../inc/preference.class.php");

// N'autorise à modifier que son propre profil, sauf si on a des droits étendus
if($_POST['id'] != Session::getLoginUserID()) {
	Session::checkRight('user', 'w');
}

$pref = new PluginSmartredirectPreference();

if(isset($_POST['create_pref'])) {
	// crée des préférences perso à partir des préférences par défaut
	$input = PluginSmartredirectConfig::getConfigValues();
	$input['id'] = $_POST['id'];
	$pref->add($input);
	Html::back();
} elseif(isset($_POST['delete_pref'])) {
	$pref->delete($_POST);
	Html::back();
} elseif(isset($_POST['update_pref'])) {
	// mise à jour des préférences perso (si elles existent)
	if(!$pref->getFromDB($_POST['id'])) {
		// cas où les préférences perso n'existent pas (n'arrivera que si deux personnes jouent avec les même paramèters en même temps)
		Session::addMessageAfterRedirect(__('No personnalized settings detected, could not update them, please try again','smartredirect'));
	}
	$pref->update($_POST);
	Html::back();
} else {
	// cas d'un déclenchement par un évènement non prévu : on ignore
	Html::back();
}

