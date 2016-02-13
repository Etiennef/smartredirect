<?php

/**
 * Objet décrivant la configuration du plugin
 * @author Etiennef
 */
class PluginSmartredirectConfig extends PluginConfigmanagerConfig {
	function getName($options = array()) {
		return __("SmartRedirect", 'smartredirect');
	}
	
	static function makeConfigParams() {
		$myProfiles = Dropdown::getDropdownArrayNames(Profile::getTable(), array_keys($_SESSION['glpiprofiles']));
		$myProfiles['nochange'] = __('Keep my default profile', 'smartredirect');
		
		return array(
			'is_activated' => array(
				'text' => __('Activate smart redirections', 'smartredirect'),
				'values' => array(
					'1' => Dropdown::getYesNo('1'),
					'0' => Dropdown::getYesNo('0')
				),
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => '0'
			),
			'requester' => array(
				'text' => __('Requester', 'smartredirect'),
				'values' => $myProfiles,
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => 'nochange'
			),
			'observer' => array(
				'text' => __('Observer', 'smartredirect'),
				'values' => $myProfiles,
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => 'nochange'
			),
			'assigned' => array(
				'text' => __('Assigned to', 'smartredirect'),
				'values' => $myProfiles,
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => 'nochange'
			),
			'requester_and_assigned_solved' => array( //TODO nom trompeur, à vérifier
				'text' => __('Requester <strong>and</strong> Assigned to (unresolved ticket)', 'smartredirect'),
				'values' => $myProfiles,
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => 'nochange'
			),
			'requester_and_assigned_others' => array( //TODO nom trompeur, à vérifier
				'text' => __('Requester <strong>and</strong> Assigned to (resolved ticket)', 'smartredirect'),
				'values' => $myProfiles,
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => 'nochange'
			),
			'norole' => array(
				'text' => __('No role on this ticket', 'smartredirect'),
				'values' => $myProfiles,
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => 'nochange'
			)
		);
	}
}


























