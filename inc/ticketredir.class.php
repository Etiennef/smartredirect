<?php
if (! defined ( 'GLPI_ROOT' )) {
	die ( "Sorry. You can't access directly to this file" );
}
class PluginSmartredirectTicketredir {
	static function getRoles($users_id, $tickets_id) {
		global $DB;
		
		$ticket = new Ticket();
		$ticket->getFromDB($tickets_id);
		
		$roles = array (
				'requester' => $ticket->isUser(CommonITILActor::REQUESTER, Session::getLoginUserID()),
				'observer' => $ticket->isUser(CommonITILActor::OBSERVER, Session::getLoginUserID()) or 
					$ticket->haveAGroup(CommonITILActor::OBSERVER, $_SESSION["glpigroups"]),
				'assign' => $ticket->isUser(CommonITILActor::ASSIGN, Session::getLoginUserID()) or 
					$ticket->haveAGroup(CommonITILActor::ASSIGN, $_SESSION["glpigroups"])
		);
		
		if($roles['requester'] && $roles['assign']) {
			if($ticket->getField('status')==CommonITILObject::SOLVED) {
				return 'requester_and_assigned_solved';
			} else {
				return 'requester_and_assigned_others';
			}
		} elseif($roles['assign']) {
			return 'assigned';
		} elseif($roles['requester']) {
			return 'requester';
		} elseif($roles['observer']) {
			return 'observer';
		} else {
			return 'norole';
		}
	}
}