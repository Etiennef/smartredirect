<?php

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

// Class NotificationTarget
class PluginSmartredirectNotificationData {
	static function getDatasForTemplate($target) {
		global $CFG_GLPI;
		
		//var_dump($target->obj);
		
		if(get_class($target->obj) == 'Ticket' and ($id = $target->obj->getField('id'))) {
			$baseStr = $CFG_GLPI["url_base"]."/index.php". "?redirect=plugin_smartredirect_ticket_".$id;
			
			$target->datas['##ticket.smartredirect.url##'] = urldecode($baseStr);
			$target->datas['##ticket.smartredirect.urlapprove##'] = urldecode($baseStr."_Ticket$2");
			$target->datas['##ticket.smartredirect.urlvalidation##'] = urldecode($baseStr."_TicketValidation$1");
			$target->datas['##ticket.smartredirect.urldocument##'] = urldecode($baseStr."_DocumentItem$1");
		}
		
		
	}
}
?>