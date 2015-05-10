<?php

/**
 * Fonction de définition de la version du plugin
 * @return type
 */
function plugin_version_smartredirect()
{
	return array('name'      => "SmartRedirect",
			'version'        => '0.0.1',
			'author'         => 'Etienne',
			'license'        => 'GPLv2+',
			'homepage'       => 'http://lmgtfy.com/?q=Etienne',
			'minGlpiVersion' => '0.84');
}

/**
 * Fonction de vérification des prérequis
 * @return le plugin peut s'exécuter sur ce GLPI
 */
function plugin_smartredirect_check_prerequisites()
{
	if (GLPI_VERSION >= 0.84 and GLPI_VERSION <= 0.85)
		return true;
	echo "Never tested for anything else than 0.84.8";
	return false;
}


/**
 * Fonction de vérification de la configuration initiale
 * @param type $verbose
 * @return boolean
 */
function plugin_smartredirect_check_config($verbose=false)
{
	if (true)
	{ // Your configuration check
		return true;
	}
	if ($verbose)
	{
		echo 'Installed / not configured';
	}
	return false;
}


/**
 * Fonction d'initialisation du plugin
 * @global array $PLUGIN_HOOKS
 */
function plugin_init_smartredirect()
{
	global $PLUGIN_HOOKS;

	$PLUGIN_HOOKS['csrf_compliant']['smartredirect'] = true;
	
	Plugin::registerClass('PluginSmartredirectPreference', array('addtabon' => array('User', 'Preference')));
	
	if (Session::haveRight("config", "w")) {
		$PLUGIN_HOOKS['config_page']['smartredirect'] = 'front/config.form.php';
	}
	
	// déclare la redirection spécifique au plugin
	$PLUGIN_HOOKS['redirect_page']['smartredirect']['ticket'] = 'front/ticketredir.form.php';
	
	// Ajoute des données pour les templates de notifications
	$PLUGIN_HOOKS['item_get_datas']['smartredirect'] = array('NotificationTargetTicket' => 'plugin_smartredirect_get_datas');
}
















