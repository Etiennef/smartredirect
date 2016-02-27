<?php

class PluginSmartredirectTabmerger extends PluginConfigmanagerTabmerger {
	protected static function getTabsConfig() {
		return array(
			// '__.*' => 'html code',
			// CommonGLPI => tabnum|'all',
			'PluginSmartredirectPluginconfig' => 'all',
			'PluginSmartredirectTicketrule' => 'all'
		);
	}
}