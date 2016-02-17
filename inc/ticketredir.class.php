<?php
if (! defined ( 'GLPI_ROOT' )) {
	die ( "Sorry. You can't access directly to this file" );
}
class PluginSmartredirectTicketredir {
	private static $allLinkTypes = array(
	//		'linktype' => 'glpi forcetab',
			'Ticket$2' => 'Ticket$2',
			'Ticket$3' => 'Ticket$3',
			'Ticket$4' => 'Ticket$4',
			'DocumentItem$1' => 'Document_Item$1',
			'TicketValidation$1' => 'TicketValidation$1',
			'' => ''
			);
	
	static function getDefaultLinkType() {
		return '';
	}
	
	static function getGLPIForcetabForLinkType($linktype) {
		if(isset(self::$allLinkTypes[$linktype])) {
			return self::$allLinkTypes[$linktype];
		} else {
			return '';
		}
	}
	
	static function getLinkTypeDescriptions() {
		return array(
			'Ticket$2' => __('Display solution','tickettransfer'),
			'Ticket$3' => __('Display satisfaction survey','tickettransfer'),
			'Ticket$4' => __('Display ticket stats','tickettransfer'),
			'DocumentItem$1' => __('Display linked documents','tickettransfer'),
			'TicketValidation$1' => __('Display validation requests','tickettransfer'),
			'' => __('Others', 'tickettransfer')
			);
	}
	
	static function getRoleDescriptions() {
		return array(
			'requester' => __('Requester'),
			'observer' => __('Watcher'),
			'assign' => __('Assigned to'),
			'approver' => __('Approver'),
			'none' => __('No role','tickettransfer')
			);
	}
	
	static function getRoles($ticket) {
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
	
	function makeSmartRedirUrl($input) {
		if(!isset($input['forcetab']) || !$linktype = array_search($input['forcetab'], self::$allLinkTypes)) {
			$linktype = self::getDefaultLinkType();
		}
		
		return $CFG_GLPI["root_doc"] . "/index.php?redirect=plugin_smartredirect_ticket_$_GET[id]_$linktype";
	}
}