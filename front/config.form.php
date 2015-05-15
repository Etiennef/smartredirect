<?php
include ("../../../inc/includes.php");
require_once("../inc/config.class.php");

Session::checkRight('config', 'r');

$plugin = new Plugin();
if ($plugin->isActivated("smartredirect")) {
	if (isset($_POST['update_config']) && $_POST['id']==0) {
		Session::checkRight('config', 'w');
		$config = new PluginSmartredirectConfig();
		$config->update($_POST);
		Html::back();
	} else {
		Html::header('SmartRedirect', $_SERVER["PHP_SELF"], "config", "plugins");
		PluginSmartredirectConfig::showForm();
		Html::footer();
	}
} else {
	Html::header('configuration', '', "config", "plugins");
	echo "<div class='center'><br><br>".
			"<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
	echo "<b>".__('Please activate the plugin', 'smartredirect')."</b></div>";
	Html::footer();
}