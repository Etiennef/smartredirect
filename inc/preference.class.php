<?php
if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginSmartredirectPreference  extends CommonDBTM {
	
	function getTabNameForItem(CommonGLPI $item, $withtemplate=0)
	{
		if ($item->getType() == 'Preference' or 
				($item->getType() == 'User' and Session::haveRight('user', 'r'))) {
			return "SmartRedirect";
		}
		return '';
	}
	
	static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0)
	{
		$pref = new self();
		if ($item->getType() == 'Preference') {
			$pref->showForm(Session::getLoginUserID());  
		} elseif($item->getType() == 'User' and Session::haveRight('user', 'r')) {
			$pref->showForm($item->getField('id'));  
		}
		return true;
	}
	
	/**
	 * Réccupère les paramtères qui s'appliquent à l'utilisateur, en tenant compte soit de ses réglages perso, soit de la config par défaut.
	 * @param int $id id de l'utilisateur dont on souhaite lire les réglages
	 * @return bool vrai ssi l'utilisateur a des paramètres perso
	 */
	function getAppliedValues($id) {
		if($this->getFromDB($id)) {
			return true;
			$this->haspref = true;
		} else {
			$this->haspref = false;
			$this->fields = PluginSmartredirectConfig::getConfigValues();
			$this->fields['id'] = $id;
			return false;
		}
	}
	
	/**
	 * Fonction qui affiche le formulaire du plugin
	 * @param type $id id du profil pour lequel on affiche les droits
	 * @param type $options
	 * @return boolean
	 */
	function showForm($id, $options=array()) {
		$target = $this->getFormURL();
		if (isset($options['target'])) {
			$target = $options['target'];
		}
		
		// si les préférences ne dont pas définies, je charge les préférences par défaut (sauf l'id)
		$pref = new self();
		$haspref = $pref->getAppliedValues($id);
		$values = $pref->fields;
		
		/* on a le droit de faire des modifications si c'est nos préférences, ou si on a le droit d'écrire les préférences des utilisateurs */
		$canwrite = ($id == Session::getLoginUserID() or Session::haveRight("user","w"));
		
		echo "<form action='".$target."' method='post'>";
		
		echo "<table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='2' class='center b'>".__('Personnal configuration for SmartRedirect', 'smartredirect')."</th></tr>";
		
		// Gestion du bouton créer/effacer les préférences (pas affiché si config!) :
		if($haspref) {
			echo "<tr class='tab_bg_2'>";
			echo "<td colspan=2><strong>".__('Personalized preferences are used for SmartRedirect.', 'smartredirect')." : </strong></td>";
			echo "</tr>";
			
			if ($canwrite) {
				// N'affiche le bouton pour supprimer les préférences que si l'utilisateur a le droit de l'utiliser
				echo "<tr class='tab_bg_2'>";
				echo "<td>".__('Do you want to delete personalized parameters?', 'smartredirect')." : </td>";
				echo "<td>";
				echo "<input type='submit' name='delete_pref' value='".__('Delete personnal parameters', 'smartredirect')."' class='submit'>";
				echo "</td>";
				echo "</tr>";
			}
		} else  {
			echo "<tr class='tab_bg_2'>";
			echo "<td colspan=2><strong>".__('No personalized preferences for SmartRedirect. Rules defined by the administrator are applied', 'smartredirect')." : </strong></td>";
			echo "</tr>";
			
			if ($canwrite) {
				// N'affiche le bouton pour créer les préférences que si l'utilisateur a le droit de l'utiliser
				echo "<tr class='tab_bg_2'>";
				echo "<td>".__('Do you want to create personalized parameters?', 'smartredirect')." : </td>";
				echo "<td>";
				echo "<input type='submit' name='create_pref' value='".__('Create personnal parameters', 'smartredirect')."' class='submit'>";
				echo "</td>";
				echo "</tr>";
			}
		}
		
		
		// Gestion de l'affichage des réglages. Read-only si réglages par défauts, ou si pas les droits pour les modifier
		if($haspref and $canwrite) {
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
			echo "<input type='submit' name='update_pref' value='"._sx('button', 'Save')."' class='submit'>";
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
		echo "<input type='hidden' name='id' value=$id>";
		Html::closeForm();
	}
	
	
}