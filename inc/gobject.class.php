<?php
if (! defined ( 'GLPI_ROOT' )) {
	die ( "Sorry. You can't access directly to this file" );
}


abstract class PluginSmartredirectGobject {
	/**
	 * Code le type d'un objet, et un paramètre forcetab arbitraire pour pouvoir faire considérer l'ensemble comme un forcetab valide au mécanisme de redirection vers un plugin.
	 * @param string $type
	 * @param string $forcetab
	 * @return string $type.'$$$'.$forcetab (avec les éventuels _ de forcetab remplacés par $$)
	 * @see forcetabDecode
	 */
	static function forcetabEncode($type, $forcetab) {
		return $type.'$$$'.preg_replace('@_@', '\\$\\$', $forcetab);
	}
	
	/**
	 * Décode le forcetab encodé par forcetabEncode pour réccupérer le type de l'objet et un paramètre forcetab originel.
	 * @param string $encoded le forctab truqué
	 * @param array $output un tableau dans lequel seront écrits type et forcetab après décodage (peut être $_GET)
	 * @return boolean if the syntax was comptible
	 * @see forcetabEncode
	 */
	static function forcetabDecode($encoded, &$output) {
		$matches = array();
		if(preg_match('@(\w*)(?:\\$\\$\\$(.*))?@', $encoded, $matches)) {
			$output['type'] = $matches[1];
			if(isset($matches[2]))
				$output['forcetab'] = preg_replace('@\\$\\$@', '_', $matches[2]);
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Function that transforms the current URL into a smart-redirect-compatible URL, alowing it to pass through authentication if necessary
	 * This function aims to replace the redirect used in Session when a direct link to an objet is used, but the user is not authenticated.
	 */
	static function redirectSmartly() {
		global $CFG_GLPI;
		$matches = array();
	
		if(preg_match('@front\\/(\w+)\\.form\\.php\\?id=(\d+)(&forcetab=(.*))?@', $_SERVER['REQUEST_URI'], $matches)) {
			Html::redirect("$CFG_GLPI[root_doc]/index.php?redirect=plugin_smartredirect_gobject_$matches[2]_".self::forcetabEncode($matches[1], $matches[4]));
		}
	
		Html::redirect($CFG_GLPI["root_doc"] . "/index.php");
	}
	
	
	/**
	 * Fonction gérant une redirection intelligente vers un objet.
	 * @param array $input données d'entrée pour la redirection. Contient le type de l'objet, son id et un forcetab s'il est défini.
	 */
	abstract function manageRedirect($input);
	
}