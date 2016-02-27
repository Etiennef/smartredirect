<?php
if (! defined ( 'GLPI_ROOT' )) {
	die ( "Sorry. You can't access directly to this file" );
}

/**
 * Gestion des redirections intelligentes en fonction du rôle de l'utilisateur sur le ticket
 * @author Etiennef
 */
class PluginSmartredirectTicket extends PluginSmartredirectGobject {
	/**
	 * Décrit les types de liens envisageables
	 * @return array(string=>string) tableau sous la forme forcetab => texte à afficher
	 */
	static function getLinkTypeDescriptions() {
		return array(
			// forcetab => texte à afficher dans la config
			'Ticket$2' => __('Display solution','tickettransfer'),
			'Ticket$3' => __('Display satisfaction survey','tickettransfer'),
			'Ticket$4' => __('Display ticket stats','tickettransfer'),
			'Document_Item$1' => __('Display linked documents','tickettransfer'),
			'TicketValidation$1' => __('Display validation requests','tickettransfer'),
			'' => __('Others', 'tickettransfer')
			);
	}
	
	/**
	 * Décrit les rôles gérés par le plugin, et la façon dont on les affiche
	 * @return array(string=>string) tableau sous la forme rôle => texte à afficher
	 */
	static function getRoleDescriptions() {
		return array(
			'requester' => __('Requester'),
			'observer' => __('Watcher'),
			'assign' => __('Assigned to'),
			'approver' => __('Approver'),
			'none' => __('No role','tickettransfer')
			);
	}
	
	/**
	 * Calcule les rôles qu'a l'utilisateur courant sur ce ticket
	 * @param Ticket $ticket
	 * @return array(string) tableau des rôles actuels (il peut y en avoir plusieurs)
	 */
	static function getRoles(Ticket $ticket) {
		$user_id = Session::getLoginUserID();
		
		$roles = array();
		
		if($ticket->isUser(CommonITILActor::REQUESTER, $user_id))
			$roles[] = 'requester';
		
		if($ticket->isUser(CommonITILActor::OBSERVER, $user_id) || 
				$ticket->haveAGroup(CommonITILActor::OBSERVER, $_SESSION["glpigroups"]))
			$roles[] = 'observer';
		
		if($ticket->isUser(CommonITILActor::ASSIGN, $user_id) or 
				$ticket->haveAGroup(CommonITILActor::ASSIGN, $_SESSION["glpigroups"]))
			$roles[] = 'assign';
		
		if(TicketValidation::canValidate($ticket->getId()))
			$roles[] = 'approver';
			
		if(empty($roles))
			$roles[] = 'none';
		
		return $roles;
	}
	
	
	function manageRedirect($input) {
		global $CFG_GLPI;
		$ticket = new Ticket();
		if(!isset($input['id']) || !$ticket->getFromDB($input['id'])) {
			if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk') {
				Html::redirect($CFG_GLPI["root_doc"]."/front/helpdesk.public.php");
			} else {
				Html::redirect($CFG_GLPI["root_doc"]."/front/central.php");
			}
		}
		
		$config = PluginSmartredirectPluginconfig::getConfigValues();
		
		
		// calcule et redirige, puis si nécessaire élargit le champ des entités
		if($config['is_activated']) {
			$rules = PluginSmartredirectTicketrule::getRulesValues();
		
			$user_id = Session::getLoginUserID();
			$roles = self::getRoles($ticket);
		
			foreach($rules as $rule) {
				//Vérifie le type de lien
				$linktype = isset(self::getLinkTypeDescriptions()[$input['forcetab']]) ? $input['forcetab'] : '';
				if(!in_array($linktype, $rule['linktypes']))
					continue;
		
				//Vérifie le processus
				if(!in_array($ticket->getEntityID(), $rule['entities']))
					continue;
		
				//Vérifie le rôle
				if(!count(array_intersect($roles, $rule['roles'])))
					continue;
		
				//Vérifie le statut
				if(!in_array($ticket->fields['status'], $rule['status']))
					continue;
		
				//Si on arrive ici, c'est que toutes les conditions sont vérifiées, donc on applique la règle
				$profile_id = $rule['profile'];
				if($profile_id != $_SESSION['glpiactiveprofile']['id']) {
					Session::changeProfile($profile_id);
				}
				if (!Session::haveAccessToEntity($ticket->getEntityID())) {
					Session::changeActiveEntities("all");
				}
		
				//Seule la première règle s'applique
				break;
			}
		}
		
		Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php?id=".$ticket->getId()."&forcetab=".$input['forcetab']);
	}
	
	
	static function getDatas(NotificationTargetTicket $target) {
		global $CFG_GLPI;
		
		if(get_class($target->obj) == 'Ticket' && ($id = $target->obj->getField('id'))) {
			$baseStr = $CFG_GLPI["url_base"]."/index.php?redirect=plugin_smartredirect_gobject_${id}_ticket$$$";
		
			$target->datas['##ticket.smartredirect.url##'] = urldecode($baseStr);
			$target->datas['##ticket.smartredirect.urlapprove##'] = urldecode($baseStr.'Ticket$2');
			$target->datas['##ticket.smartredirect.urlvalidation##'] = urldecode($baseStr.'TicketValidation$1');
			$target->datas['##ticket.smartredirect.urldocument##'] = urldecode($baseStr.'Document$$Item$1');
		}
	}
}
























