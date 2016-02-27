<?php
include_once 'ticket.class.php';

class PluginSmartredirectTicketrule extends PluginConfigmanagerRule {
	protected static $inherit_order = array(self::TYPE_USER, self::TYPE_GLOBAL);
	
	static function makeConfigParams() {
		$myProfiles = Dropdown::getDropdownArrayNames(Profile::getTable(), array_keys($_SESSION['glpiprofiles']));
		
		$myEntities = Dropdown::getDropdownArrayNames(Entity::getTable(), $_SESSION['glpiactiveentities']);
		asort($myEntities);
		
		return array(
			'_header' => array(
				'type' => 'readonly text',
				'text' => self::makeHeaderLine(__('Redirection rules', 'smartredirect'))
			),
			'linktypes' => array(
				'type' => 'dropdown',
				'maxlength' => 500,
				'text' => __('Link type', 'smartredirect'),
				'tooltip' => __('Rule is applied only for selected links', 'smartredirect'),
				'values' => PluginSmartredirectTicket::getLinkTypeDescriptions(),
				'default' => '[]',
				'multiple'=>true,
				'size'=>5
			),
			'entities' => array(
				'type' => 'dropdown',
				'maxlength' => 60000,
				'text' => __('Entities'),
				'tooltip' => __('Rule is applied only when ticket in in these entities (not recurrsive)', 'smartredirect'),
				'values' => $myEntities,
				'default' => '[]',
				'multiple'=>true,
				'size'=>5
			),
			'roles' => array(
				'type' => 'dropdown',
				'maxlength' => 250,
				'text' => __('Role on ticket', 'smartredirect'),
				'tooltip' => __('Rule is applied only when you play one of these roles on the ticket', 'smartredirect'),
				'values' => PluginSmartredirectTicket::getRoleDescriptions(),
				'default' => '[]',
				'multiple'=>true,
				'size'=>5
			),
			'status' => array(
				'type' => 'dropdown',
				'maxlength' => 250,
				'text' => __('Status'),
				'tooltip' => __('Rule is applied only for tickets having one of these status', 'smartredirect'),
				'values' => Ticket::getAllStatusArray(),
				'default' => '[]',
				'multiple'=>true,
				'size'=>5
			),
			'readonly1' => array(
				'type' => 'readonly text',
				'text' => '=>'
			),
			'profile' => array(
				'type' => 'dropdown',
				'maxlength' => 25,
				'text' => __('Profile'),
				'tooltip' => __('Profile to select when all the conditions are met', 'smartredirect'),
				'values' => $myProfiles,
				'default' => '[]'
			),
		);
	}
	
	
}