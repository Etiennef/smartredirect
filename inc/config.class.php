<?php

/**
 * Objet décrivant la configuration du plugin
 * @author Etiennef
 */
class PluginSmartredirectConfig extends PluginConfigmanagerConfig {
	static function getPluginName() {
		return __("SmartRedirect", 'smartredirect');
	}
	
	static function makeConfigParams() {
		return array(
			'is_activated' => array(
				'type' => 'dropdown',
				'text' => __('Activate smart redirections', 'smartredirect'),
				'values' => array(
					'1' => Dropdown::getYesNo('1'),
					'0' => Dropdown::getYesNo('0')
				),
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => '0'
			)
		);
	}
}


























