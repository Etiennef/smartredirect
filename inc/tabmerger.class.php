<?php

class PluginSmartredirectTabmerger extends PluginConfigmanagerTabmerger {
	protected static function getTabsConfig() {
		return array(
			// '__.*' => 'html code',
			// CommonGLPI => tabnum|'all',
			'PluginSmartredirectConfig' => 0,
			'PluginSmartredirectRule' => 0
		);
	}
}