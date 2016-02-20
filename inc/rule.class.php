<?php

class PluginSmartredirectRule extends PluginConfigmanagerRule {
	protected static $inherit_order = array(self::TYPE_USER, self::TYPE_GLOBAL);
	
	static function makeConfigParams() {
		$myProfiles = Dropdown::getDropdownArrayNames(Profile::getTable(), array_keys($_SESSION['glpiprofiles']));
		
		$myEntities = Dropdown::getDropdownArrayNames(Entity::getTable(), $_SESSION['glpiactiveentities']);
		asort($myEntities);
		
		return array(
			'linktypes' => array(
				'type' => 'dropdown',
				'text' => __('Link type', 'smartredirect'),
				'tooltip' => __('Rule is applied only for selected links', 'smartredirect'),
				'values' => PluginSmartredirectTicket::getLinkTypeDescriptions(),
				'dbtype' => 'varchar(500)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>5
				)
			),
			'entities' => array(
				'type' => 'dropdown',
				'text' => __('Entities'),
				'tooltip' => __('Rule is applied only when ticket in in these entities (not recurrsive)', 'smartredirect'),
				'values' => $myEntities,
				'dbtype' => 'varchar(1000)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>5
				)
			),
			'roles' => array(
				'type' => 'dropdown',
				'text' => __('Role on ticket', 'smartredirect'),
				'tooltip' => __('Rule is applied only when you play one of these roles on the ticket', 'smartredirect'),
				'values' => PluginSmartredirectTicket::getRoleDescriptions(),
				'dbtype' => 'varchar(250)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>5
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
					'size'=>5
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