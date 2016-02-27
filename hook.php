<?php

/**
 * Fonction d'installation du plugin
 * @return boolean
 */
function plugin_smartredirect_install() {
	include 'inc/pluginconfig.class.php';
	PluginSmartredirectPluginconfig::install();
	
	include 'inc/ticketrule.class.php';
	PluginSmartredirectTicketrule::install();
	
	return true;
}


/**
 * Fonction de désinstallation du plugin
 * @return boolean
 */
function plugin_smartredirect_uninstall() {
	include 'inc/pluginconfig.class.php';
	PluginSmartredirectPluginconfig::uninstall();

	include 'inc/ticketrule.class.php';
	PluginSmartredirectTicketrule::uninstall();
	
	return true;
}


