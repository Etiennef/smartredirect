<?php
include ("../../../inc/includes.php");
require_once("../inc/preference.class.php");

Session::checkRight('config', 'r');

$plugin = new Plugin();
if ($plugin->isActivated("smartredirect")) {
	if (isset($_POST['update'])) {
		Session::checkRight('config', 'w');
		$config = new PluginSmartredirectConfig();
		$config->getFromDB(0);
		$config->update($_POST);
		Html::back();
	} else {
		Html::header('SmartRedirect', $_SERVER["PHP_SELF"], "config", "plugins");
		PluginSmartredirectConfig::$config->showForm();
		Html::footer();
	}
} else {
	Html::header('configuration', '', "config", "plugins");
	echo "<div class='center'><br><br>".
			"<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
	echo "<b>".__('Please activate the plugin', 'smartredirect')."</b></div>";
	Html::footer();
}