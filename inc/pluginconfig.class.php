<?php
/**
 * Objet décrivant la configuration du plugin
 * @author Etiennef
 */
class PluginSmartredirectPluginconfig extends PluginConfigmanagerConfig {
	static function makeConfigParams() {
		$tooltip_syntax_url = __('You can either use a full URL or just add GET parmameters to the build-in value. To add GET parameters, use this syntax : &parama=value&paramb=toto. Intitial & will be converted into ? if necessary. Any value not sarting with & will be considered as full URL.', 'smartredirect');
		
		return array(
			'_title' => array(
				'type' => 'readonly text',
				'types' => array(self::TYPE_USER, self::TYPE_GLOBAL),
				'text' => self::makeHeaderLine(__('Configuration for ticket redirections', 'smartredirect'))
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
			),
			
			
			'_title2' => array(
				'type' => 'readonly text',
				'types' => array(self::TYPE_GLOBAL),
				'text' => self::makeHeaderLine(__('Configuration for ticket creation rediretion', 'smartredirect'))
			),
			'url_success' => array(
				'type' => 'text input',
				'types' => array(self::TYPE_GLOBAL),
				'maxlength' => 250,
				'text' => __('URL used to redirect in case of success', 'smartredirect'),
				'tooltip' => $tooltip_syntax_url,
				'default' => ''
			),
			'url_profile_error' => array(
				'type' => 'text input',
				'types' => array(self::TYPE_GLOBAL),
				'maxlength' => 250,
				'text' => __('URL used to redirect when profile is not available', 'smartredirect'),
				'tooltip' => $tooltip_syntax_url,
				'default' => ''
			),
			'url_entity_error' => array(
				'type' => 'text input',
				'types' => array(self::TYPE_GLOBAL),
				'maxlength' => 250,
				'text' => __('URL used to redirect when profile is not reachable', 'smartredirect'),
				'tooltip' => $tooltip_syntax_url,
				'default' => ''
			),
			'url_category_error' => array(
				'type' => 'text input',
				'types' => array(self::TYPE_GLOBAL),
				'maxlength' => 250,
				'text' => __('URL used to redirect when category is not valid (wrong type, wrong entity, does not exist at all...)', 'smartredirect'),
				'tooltip' => $tooltip_syntax_url,
				'default' => ''
			),
			'url_syntax_error' => array(
				'type' => 'text input',
				'types' => array(self::TYPE_GLOBAL),
				'maxlength' => 250,
				'text' => __('URL used to redirect when redirect link is badly formed', 'smartredirect'),
				'tooltip' => $tooltip_syntax_url,
				'default' => ''
			),
			
		);
	}
}


























