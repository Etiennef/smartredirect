<?php
if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginSmartredirectConfig  extends CommonDBTM {
	/**
	 * Lit les valeurs des paramètres appliqués par défaut
	 * @return array paramètres par défaut
	 */
	static function getConfigValues() {
		$config = new self();
		$config->getFromDB(0);
		$values = $config->fields;
		unset($values['id']);
		return $values;
	}
	
	/**
	 * Fonction qui affiche le formulaire du plugin
	 * @param type $id id du profil pour lequel on affiche les droits
	 * @param type $options
	 * @return boolean
	 */
	static function showForm() {
		$target = self::getFormURL();
		if (isset($options['target'])) {
			$target = $options['target'];
		}
		
		// si les préférences ne dont pas définies, je charge les préférences par défaut (sauf l'id)
		$config = new self();
		$config->getFromDB(0);
		$values = $config->fields;
		
		$canwrite = Session::haveRight("config","w");
		
		echo "<form action='".$target."' method='post'>";
		
		echo "<table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='2' class='center b'>".__('Default configuration for SmartRedirect', 'smartredirect')."</th></tr>";
		
		// Gestion de l'affichage des réglages. Read-only si réglages par défauts, ou si pas les droits pour les modifier
		if($canwrite) {
			// Affichage modifiable
			echo "<tr class='tab_bg_2'>";
			echo "<td>".__('Activate smart redirections', 'smartredirect')." : </td><td>";
			Dropdown::showYesNo("is_activated", $values["is_activated"]);
			echo "</td></tr>";
				
			echo "<tr class='tab_bg_2'><td>".__('Requester', 'smartredirect')." : </td><td>";
			Profile::dropdown(array(
					'name'		=> 'requester',
					'value'     => $values['requester'],
			));
			echo "</td></tr>";
				
			echo "<tr class='tab_bg_2'><td>".__('Observer', 'smartredirect')." : </td><td>";
			Profile::dropdown(array(
					'name'		=> 'observer',
					'value'     => $values['observer'],
			));
			echo "</td></tr>";
				
			echo "<tr class='tab_bg_2'><td>".__('Assigned to', 'smartredirect')." : </td><td>";
			Profile::dropdown(array(
					'name'		=> 'assigned',
					'value'     => $values['assigned'],
			));
			echo "</td></tr>";
				
			echo "<tr class='tab_bg_2'><td>".__('Requester <strong>and</strong> Assigned to (unresolved ticket)', 'smartredirect').
			" : </td><td>";
			Profile::dropdown(array(
					'name'		=> 'requester_and_assigned_solved',
					'value'     => $values['requester_and_assigned_solved'],
			));
			echo "</td></tr>";
				
			echo "<tr class='tab_bg_2'><td>".__('Requester <strong>and</strong> Assigned to (resolved ticket)', 'smartredirect').
			" : </td><td>";
			Profile::dropdown(array(
					'name'		=> 'requester_and_assigned_others',
					'value'     => $values['requester_and_assigned_others'],
			));
			echo "</td></tr>";
				
			echo "<tr class='tab_bg_2'><td>".__('No role on this ticket', 'smartredirect').
			" : </td><td>";
			Profile::dropdown(array(
					'name'		=> 'norole',
					'value'     => $values['norole'],
			));
			echo "</td></tr>";
				
			echo "<tr class='tab_bg_1'>";
			echo "<td class='center' colspan='2'>";
			echo "<input type='submit' name='update_config' value='"._sx('button', 'Save')."' class='submit'>";
			echo "</td></tr>";
		} else {
			// Affichage en read-only		
			echo "<tr class='tab_bg_2'>";
			echo "<td>".__('Activate smart redirections', 'smartredirect')." : </td>";
			echo "<td>".Dropdown::getYesNo($values['is_activated'])."</td>";
			echo "</tr>";
			
			echo "<tr class='tab_bg_2'>";
			echo "<td>".__('Requester', 'smartredirect')." : </td>";
			echo "<td>".Dropdown::getDropdownName('glpi_profiles', $values['requester'])."</td>";
			echo "</tr>";

			echo "<tr class='tab_bg_2'>";
			echo "<td>".__('Observer', 'smartredirect')." : </td>";
			echo "<td>".Dropdown::getDropdownName('glpi_profiles', $values['observer'])."</td>";
			echo "</tr>";
			
			echo "<tr class='tab_bg_2'>";
			echo "<td>".__('Assigned to', 'smartredirect')." : </td>";
			echo "<td>".Dropdown::getDropdownName('glpi_profiles', $values['assigned'])."</td>";
			echo "</tr>";
			
			echo "<tr class='tab_bg_2'>";
			echo "<td>".__('Requester <strong>and</strong> Assigned to (unresolved ticket)', 'smartredirect')." : </td>";
			echo "<td>".Dropdown::getDropdownName('glpi_profiles', $values['requester_and_assigned_solved'])."</td>";
			echo "</tr>";
			
			echo "<tr class='tab_bg_2'>";
			echo "<td>".__('Requester <strong>and</strong> Assigned to (resolved ticket)', 'smartredirect')." : </td>";
			echo "<td>".Dropdown::getDropdownName('glpi_profiles', $values['requester_and_assigned_others'])."</td>";
			echo "</tr>";

			echo "<tr class='tab_bg_2'>";
			echo "<td>".__('No role on this ticket', 'smartredirect')." : </td>";
			echo "<td>".Dropdown::getDropdownName('glpi_profiles', $values['norole'])."</td>";
			echo "</tr>";
		}
		
		echo "</table>";
		echo "<input type='hidden' name='id' value='0'>";
		Html::closeForm();
	}
	
	
}