<?php

include_once 'ticketredir.class.php';

class PluginSmartredirectRule extends PluginConfigmanagerRule {
	protected static $inherit_order = array(self::TYPE_USER, self::TYPE_GLOBAL);
	
	static function getPluginName() {
		return __("SmartRedirect rules", 'smartredirect');
	}
	
	static function makeConfigParams() {
		$myProfiles = Dropdown::getDropdownArrayNames(Profile::getTable(), array_keys($_SESSION['glpiprofiles']));
		
		$myEntities = Dropdown::getDropdownArrayNames(Entity::getTable(), $_SESSION['glpiactiveentities']);
		asort($myEntities);
		
		return array(
			'linktypes' => array(
				'type' => 'dropdown',
				'text' => __('Link type', 'smartredirect'),
				'tooltip' => __('Rule is applied only for selected links', 'smartredirect'),
				'values' => PluginSmartredirectTicketredir::getLinkTypeDescriptions(),
				'dbtype' => 'varchar(250)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>5,
			 		'mark_unmark_all' => true
				)
			),
			'entities' => array(
				'type' => 'dropdown',
				'text' => __('Entités'),
				'tooltip' => __('Rule is applied only when ticket in in these entities (not recurrsive)', 'smartredirect'),
				'values' => $myEntities,
				'dbtype' => 'varchar(250)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>5,
			 		'mark_unmark_all' => true
				)
			),
			'roles' => array(
				'type' => 'dropdown',
				'text' => __('Role on ticket', 'smartredirect'),
				'tooltip' => __('Rule is applied only when you play one of these roles on the ticket', 'smartredirect'),
				'values' => PluginSmartredirectTicketredir::getRoleDescriptions(),
				'dbtype' => 'varchar(250)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>5,
			 		'mark_unmark_all' => true
				)
			),
			'status' => array(
				'type' => 'dropdown',
				'text' => __('Status'),
				'tooltip' => __('Rule is applied only for tickets having one of these status', 'smartredirect'),
				'values' => Ticket::getAllStatusArray(),
				'dbtype' => 'varchar(250)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>5,
			 		'mark_unmark_all' => true
				)
			),
			'readonly1' => array(
				'type' => 'readonly text',
				'text' => '=>'
			),
			'profile' => array(
				'type' => 'dropdown',
				'text' => __('Profile'),
				'tooltip' => __('Profile to select when all the conditions are met', 'smartredirect'),
				'values' => $myProfiles,
				'dbtype' => 'varchar(25)',
				'default' => '[]'
			),
		);
	}
	
	
}