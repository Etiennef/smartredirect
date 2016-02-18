<?php

include("../../../inc/includes.php");

PluginSmartredirectGobject::forcetabDecode($_GET['forcetab'], $_GET);

$className = "PluginSmartredirect".ucfirst(strtolower($_GET['type']));
if(class_exists($className)) {
	$instance = new $className();
	$instance->manageRedirect($_GET);
} else {
	PluginSmartredirectGobject::redirectToObject($_GET);
}




