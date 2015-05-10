<?php
include ("../../../inc/includes.php");
require_once("../inc/preference.class.php");

$plugin = new Plugin();


if (!$plugin->isActivated("smartredirect")) {
	Session::
	
	Html::header('configuration', '', "config", "plugins");
	echo "<div class='center'><br><br>".
			"<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
	echo "<b>".__('Please activate the plugin', 'smartredirect')."</b></div>";
	Html::footer();
}

// N'autorise à modifier que son propre profil, sauf si on a des droits étendus
if($_POST['id'] != Session::getLoginUserID()) {
	Session::checkRight('user', 'w');
}

$pref = new PluginSmartredirectPreference();
if(isset($_POST['create'])) {
	// crée des préférences perso à partir des préférences par défaut
	if(!$pref->getFromDB(0)) {
		// cas où le plugin est activé, mais où la config n'existe pas (ne devrait jamais arriver)
		Session::addMessageAfterRedirect(__('Something went really wrong\n Unless you tried something exotic, please contact your admin','smartredirect'));
	}
	$input = $pref->fields;
	$input['id'] = $_POST['id'];
	$pref->add($input);
	Html::back();
} elseif(isset($_POST['update'])) {
	// mise à jour des préférences perso (si elles existent)
	if(!$pref->getFromDB($_POST['id'])) {
		// cas où les préférences perso n'existent pas (n'arrivera que si deux personnes jouent avec les même paramèters en même temps)
		Session::addMessageAfterRedirect(__('No personnalized settings detected, could not update them, please try again','smartredirect'));
	}
	$pref->update($_POST);
	Html::back();
} else {
	Session::addMessageAfterRedirect(__('Something went really wrong\n Unless you tried something exotic, please contact your admin','smartredirect'));
	Html::back();
}

