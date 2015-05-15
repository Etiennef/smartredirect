<?php

/**
 * Fonction d'installation du plugin
 * @return boolean
 */
function plugin_smartredirect_install() {
	global $DB;
	
	if (! TableExists ( "glpi_plugin_smartredirect_configs" )) {
		/*	table pour stoquer la config par défaut du plugin
			id => 0, forcement
			is_activated => les redirections sont activées
			requester => profiles_id pour les tickets dont on est demandeur
			observer => profiles_id pour les tickets dont on est observateur
			assigned => profiles_id pour les tickets dont on est technicien (via groupe ou non)
			requester_and_assigned_solved => profiles_id pour les tickets résolus dont on est à la fois demandeur et technicien
			requester_and_assigned_others => profiles_id pour les tickets non résolus (ou clos) dont on est à la fois demandeur et technicien
			norole => profiles_id pour les tickets sur lesquels on n'a pas de rôle
		*/
		$query = "CREATE TABLE `glpi_plugin_smartredirect_configs` (
                	`id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_users (id)',
					`is_activated` tinyint(1) collate utf8_unicode_ci default 0,
                    `requester` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`observer` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`assigned` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`requester_and_assigned_solved` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`requester_and_assigned_others` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`norole` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		
		$DB->query ( $query ) or die ( $DB->error () );

		$query = "INSERT INTO `glpi_plugin_smartredirect_configs`
					(`id`)
                	VALUES (0)";
		$DB->query ( $query ) or die ( $DB->error () );
	}
	
	if (! TableExists ( "glpi_plugin_smartredirect_preferences" )) {
		/*	table pour stoquer la config par défaut du plugin
		 id => id de l'utilisateur dont ce sont les réglages, 0 pour la config par défaut
		 le rest => comme config
		 */
		
		$query = "CREATE TABLE `glpi_plugin_smartredirect_preferences` (
                	`id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_users (id)',
					`is_activated` tinyint(1) collate utf8_unicode_ci default 0,
                    `requester` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`observer` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`assigned` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`requester_and_assigned_solved` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`requester_and_assigned_others` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					`norole` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
					PRIMARY KEY  (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		
		$DB->query ( $query ) or die ( $DB->error () );
	}
	
	return true;
}


/**
 * Fonction de désinstallation du plugin
 * @return boolean
 */
function plugin_smartredirect_uninstall()
{
	global $DB;

	$tables = array("glpi_plugin_smartredirect_preferences", "glpi_plugin_smartredirect_configs");

	foreach($tables as $table) {
		$DB->query("DROP TABLE IF EXISTS `$table`;");
	}
	
	return true;
}

/**
 * Hook vers la définition de nouvelle données dans les notifications
 * @param NotificationTargetTicket $target
 */
function plugin_smartredirect_get_datas(NotificationTargetTicket $target) {
	PluginSmartredirectNotificationData::getDatasForTemplate($target);
}
