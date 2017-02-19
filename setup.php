<?php

/**
 * Fonction de définition de la version du plugin
 * @return type
 */
function plugin_version_smartredirect() {
   return array('name'      => "SmartRedirect",
         'version'        => '1.0.0',
         'author'         => 'Etiennef',
         'license'        => 'GPLv2+',
         'homepage'       => 'https://github.com/Etiennef/smartredirect',
         'minGlpiVersion' => '0.84.8');
}

/**
 * Fonction de vérification des prérequis
 * @return le plugin peut s'exécuter sur ce GLPI
 */
function plugin_smartredirect_check_prerequisites() {
   if(version_compare(GLPI_VERSION, '0.84.8', 'lt') || version_compare(GLPI_VERSION, '0.85', 'ge')) {
      echo __("Plugin has been tested only for GLPI 0.84.8", 'smartredirect');
      return false;
   }
   
   //Vérifie la présence de ConfigManager
   if(!(new Plugin())->isActivated('configmanager')) {
      echo __("Plugin requires ConfigManager 1.x.x", 'smartredirect');
      return false;
   }
   $configmanager_version = Plugin::getInfo('configmanager', 'version');
   if(version_compare($configmanager_version, '1.0.0', 'lt') || version_compare($configmanager_version, '2.0.0', 'ge')) {
      echo __("Plugin requires ConfigManager 1.x.x", 'smartredirect');
      return false;
   }
   
   return true;
}

/**
 * Fonction de vérification de la configuration initiale
 * @param type $verbose
 * @return boolean
 */
function plugin_smartredirect_check_config($verbose=false) {
   return true;
}

/**
 * Fonction d'initialisation du plugin
 * @global array $PLUGIN_HOOKS
 */
function plugin_init_smartredirect() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['smartredirect'] = true;
   
   Plugin::registerClass('PluginSmartredirectTicket');
   
   Plugin::registerClass('PluginSmartredirectPluginconfig');
   Plugin::registerClass('PluginSmartredirectTicketrule');
   Plugin::registerClass('PluginSmartredirectTabmerger', array(
         'addtabon' => array(
               'User',
               'Preference',
               'Config'
         )));
   
   if((new Plugin())->isActivated('smartredirect')) {
      $PLUGIN_HOOKS['config_page']['smartredirect'] = '../../front/config.form.php?forcetab=PluginSmartredirectTabmerger%241';
   }
   
   // Ajoute des données pour les templates de notifications
   $PLUGIN_HOOKS['item_get_datas']['smartredirect'] = array(
      'NotificationTargetTicket' => array('PluginSmartredirectTicket', 'getDatas')
   );
}
