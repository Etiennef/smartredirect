<?php
include ("../../../inc/includes.php");

if(!(new Plugin())->isActivated('smartredirect')) {
	Html::header('configuration', '', "config", "plugins");
	echo "<div class='center'><br><br>" . "<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='warning'><br><br>";
	echo "<b>".__('Please activate the plugin', 'smartredirect')."</b></div>";
	Html::footer();
} else if(isset($_POST['update'])) {
	$config = new PluginSmartredirectConfig();
	$config->check($_POST['id'],'w');
	$config->update($_POST);
	Html::back();
} else {
	Html::redirect($CFG_GLPI["root_doc"]."/front/config.form.php?forcetab=".urlencode('PluginSmartredirectConfig$0'));
}