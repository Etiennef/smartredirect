<?php
if (! defined ( 'GLPI_ROOT' )) {
	die ( "Sorry. You can't access directly to this file" );
}


class PluginSmartredirectGobject {
	
	static function forcetabEncode($type, $forcetab) {
		return $type.'$$$'.preg_replace('/_/', '\\$\\$', $forcetab);
	}
	
	static function forcetabDecode($encoded, &$output) {
		$matches = array();
		if(preg_match('/(\w*)(?:\\$\\$\\$(.*))?/', $encoded, $matches)) {
			$output['type'] = $matches[1];
			if(isset($matches[2]))
				$output['forcetab'] = preg_replace('/\\$\\$/', '_', $matches[2]);
			return true;
		} else {
			return false;
		}
	}
	
	static function redirectSmartly() {
		global $CFG_GLPI;
		$matches = array();
	
		if(preg_match('/front\\/(\w+)\\.form\\.php\\?id=(\d+)(&forcetab=(.*))?/', $_SERVER['REQUEST_URI'], $matches)) {
			Html::redirect("$CFG_GLPI[root_doc]/index.php?redirect=plugin_smartredirect_gobject_$matches[2]_".self::forcetabEncode($matches[1], $matches[4]));
		}
	
		Html::redirect($CFG_GLPI["root_doc"] . "/index.php");
	}
	
	static function redirectToObject($input) {
		global $CFG_GLPI;
		Html::redirect($CFG_GLPI["root_doc"]."/front/$input[type].form.php?id=$input[id]&forcetab=$input[forcetab]");
	}
}