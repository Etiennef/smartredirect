<?php

/**
 * Objet décrivant la configuration du plugin
 * @author Etiennef
 */
class PluginSmartredirectConfig extends PluginConfigmanagerConfig {
	static function makeConfigParams() {
		return array(
			'_title' => array(
				'type' => 'readonly text',
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'text' => self::makeHeaderLine(__('Configuration for SmartRedirect', 'smartredirect'))
			),
			'is_activated' => array(
				'type' => 'dropdown',
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'maxlength' => 25,
				'text' => __('Activate smart redirections for tickets', 'smartredirect'),
				'values' => array(
					'1' => Dropdown::getYesNo('1'),
					'0' => Dropdown::getYesNo('0')
				),
				'default' => '0'
			)
		);
	}
}


























