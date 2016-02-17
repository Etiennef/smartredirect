<?php

/**
 * Fonction d'installation du plugin
 * @return boolean
 */
function plugin_smartredirect_install() {
	include 'inc/config.class.php';
	PluginSmartredirectConfig::install();

	include 'inc/rule.class.php';
	PluginSmartredirectRule::install();
	
	return true;
}


/**
 * Fonction de désinstallation du plugin
 * @return boolean
 */
function plugin_smartredirect_uninstall() {
	include 'inc/config.class.php';
	PluginSmartredirectConfig::uninstall();

	include 'inc/rule.class.php';
	PluginSmartredirectRule::uninstall();
	
	return true;
}

/**
 * Hook vers la définition de nouvelle données dans les notifications
 * @param NotificationTargetTicket $target
 */
function plugin_smartredirect_get_datas(NotificationTargetTicket $target) {
	PluginSmartredirectNotificationData::getDatasForTemplate($target);
}
