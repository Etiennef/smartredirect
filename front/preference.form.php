<?php
include ("../../../inc/includes.php");
require_once("../inc/preference.class.php");

$plugin = new Plugin();
if ($plugin->isActivated("smartredirect")) {

	if (isset($_POST['update_pref'])) {
		if($_POST['id'] != Session::getLoginUserID() and
				($_POST['id']!='0' or !Session::haveRight("config","w"))) {
			Html::displayRightError();
		}
	
		$pref = new PluginSmartredirectPreference();
		if(!$pref->getFromDB($_POST['id'])) {
			$pref->add($_POST);
		} else {
			$pref->update($_POST);
		}
		Html::back();
	} else {
		Html::header('SmartRedirect', $_SERVER["PHP_SELF"], "config", "plugins");
		$config = new PluginSmartredirectPreference();
		$config->showConfigForm();
		Html::footer();
	}
} else {
	Html::header('configuration', '', "config", "plugins");
	echo "<div class='center'><br><br>".
			"<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
	echo "<b>".__('Please activate the plugin', 'smartredirect')."</b></div>";
	Html::footer();
}