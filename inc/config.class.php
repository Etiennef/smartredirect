<?php
if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginSmartredirectConfig  extends CommonDBTM {
	/**
	 * Fonction qui affiche le formulaire du plugin
	 * @param type $id id du profil pour lequel on affiche les droits
	 * @param type $options
	 * @return boolean
	 */
	static function showForm() {
		$target = self::getFormURL();
		
		// si les préférences ne dont pas définies, je charge les préférences par défaut (sauf l'id)
		$config = new self();
		$config->GetfromDB(0);
		
		echo "<form action='".$target."' method='post'>";
		echo "<table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='2' class='center b'>".__('Default configuration for SmartRedirect', 'smartredirect')."</th></tr>";
				
		echo "<tr class='tab_bg_2'>";
		echo "<td>".__('Activate smart redirections', 'smartredirect')." : </td><td>";
		Dropdown::showYesNo("is_activated", $values["is_activated"]);
		echo "</td></tr>";
		
		echo "<tr class='tab_bg_2'><td>".__('Requester', 'smartredirect')." : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'requester',
				'value'     => $config->fields['requester'],
		));
		echo "</td></tr>";
		
		echo "<tr class='tab_bg_2'><td>".__('Observer', 'smartredirect')." : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'observer',
				'value'     => $config->fields['observer'],
		));
		echo "</td></tr>";
		
		echo "<tr class='tab_bg_2'><td>".__('Assigned to', 'smartredirect')." : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'assigned',
				'value'     => $config->fields['assigned'],
		));
		echo "</td></tr>";

		echo "<tr class='tab_bg_2'><td>".__('Requester <strong>and</strong> Assigned to (unresolved ticket)', 'smartredirect').
				" : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'requester_and_assigned_solved',
				'value'     => $config->fields['requester_and_assigned_solved'],
		));
		echo "</td></tr>";

		echo "<tr class='tab_bg_2'><td>".__('Requester <strong>and</strong> Assigned to (resolved ticket)', 'smartredirect').
				" : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'requester_and_assigned_others',
				'value'     => $config->fields['requester_and_assigned_others'],
		));
		echo "</td></tr>";
		
		echo "<tr class='tab_bg_2'><td>".__('No role on this ticket', 'smartredirect').
				" : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'norole',
				'value'     => $config->fields['norole'],
		));
		echo "</td></tr>";
		
		if (Session::haveRight("config","w")) {
			echo "<tr class='tab_bg_1'>";
			echo "<td class='center' colspan='2'>";
			echo "<input type='submit' name='update_pref' value='"._sx('button', 'Save')."' class='submit'>";
			echo "</td></tr>";
		}
		echo "</table>";
		
		Html::closeForm();
	}
}