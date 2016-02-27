<?php

include("../../../inc/includes.php");
global $CFG_GLPI;

if(!PluginSmartredirectGobject::forcetabDecode($_GET['forcetab'], $_GET)) {
	// si la syntaxe du lien n'est pas reconnue, fallback sur l'index
	Html::redirect($CFG_GLPI["root_doc"] . "/index.php");
}

//Si on a un objet décrivant une redirection intelligente, on l'utilise. Sinon, on redirige vers l'objet sans rien changer
if(($instance = getItemForItemtype("PluginSmartredirect".ucfirst(strtolower($_GET['type']))))
		&& ($instance instanceof PluginSmartredirectGobject)) {
	$instance->manageRedirect($_GET);
} else {
	Html::redirect($CFG_GLPI["root_doc"]."/front/$_GET[type].form.php?id=$_GET[id]&forcetab=$_GET[forcetab]");
}


