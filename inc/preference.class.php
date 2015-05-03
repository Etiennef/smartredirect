<?php
if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginSmartredirectPreference  extends CommonDBTM {
	function getTabNameForItem(CommonGLPI $item, $withtemplate=0)
	{
		if ($item->getType() == 'Preference' or $item->getType() == 'User') {

			return "SmartRedirect";
		}
		return '';
	}
	
	static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0)
	{
		$pref = new self();
		if ($item->getType() == 'Preference') {
			$pref->showForm(Session::getLoginUserID());  
		} elseif($item->getType() == 'User') {
			$pref->showForm($item->getField('id'));  
		}
		return true;
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
		if ($pref->GetfromDB($id)) {
			$values = $pref->fields;
		} else {			
			$defaultpref = new self();
			$defaultpref->getFromDB(0);
			$values = $defaultpref->fields;
			$values['id'] = $id;
		}
		
		echo "<form action='".$target."' method='post'>";
		echo "<table class='tab_cadre_fixe'>";
		
		if($id=='0') {
			echo "<tr><th colspan='2' class='center b'> Configuration par défaut du plugin SmartRedirect</th></tr>";
		} else {
			echo "<tr><th colspan='2' class='center b'> Configuration personelle du plugin SmartRedirect</th></tr>";
		}
		
		echo "<tr class='tab_bg_2'>";
		echo "<td>Activer les redirections : </td><td>";
		Dropdown::showYesNo("is_activated", $values["is_activated"]);
		echo "</td></tr>";
		
		echo "<tr class='tab_bg_2'><td>Demandeur : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'requester',
				'value'     => $values['requester'],
		));
		echo "</td></tr>";
		
		echo "<tr class='tab_bg_2'><td>Observateur : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'observer',
				'value'     => $values['observer'],
		));
		echo "</td></tr>";
		
		echo "<tr class='tab_bg_2'><td>Attribué à : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'assigned',
				'value'     => $values['assigned'],
		));
		echo "</td></tr>";

		echo "<tr class='tab_bg_2'><td>Demandeur <strong>et</strong> attribué à (ticket non résolu) : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'requester_and_assigned_solved',
				'value'     => $values['requester_and_assigned_solved'],
		));
		echo "</td></tr>";

		echo "<tr class='tab_bg_2'><td>Demandeur <strong>et</strong> attribué à (ticket résolu) : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'requester_and_assigned_others',
				'value'     => $values['requester_and_assigned_others'],
		));
		echo "</td></tr>";
		
		echo "<tr class='tab_bg_2'><td>Aucun rôle sur le ticket : </td><td>";
		Profile::dropdown(array(
				'name'		=> 'norole',
				'value'     => $values['norole'],
		));
		echo "</td></tr>";
		
		//TODO => possibilité d'effacer ses préférences perso
		
		// droit de modifier si c'est les siennes, ou si on a le droit de modifier les params
		if ($values['id'] == Session::getLoginUserID() or
				($values['id']==0 and Session::haveRight("config","w"))) {
			echo "<tr class='tab_bg_1'>";
			echo "<td class='center' colspan='2'>";
			echo "<input type='hidden' name='id' value=$id>";
			echo "<input type='submit' name='update_pref' value='Mettre à jour' class='submit'>";
			echo "</td></tr>";
		}
		echo "</table>";
		
		Html::closeForm();
	}
	
	

	static function canCreate() {
		return Session::haveRight('config', 'w');
	}
	
	static function canView() {
		return Session::haveRight('config', 'r');
	}
	
	function showConfigForm() {
		if (!Session::haveRight("config","r")) {
			return false;
		}
		$this->showForm(0);
	}
	
}