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
			$target->datas['##ticket.smartredirect.url##'] = urldecode($CFG_GLPI["url_base"]."/index.php". "?redirect=plugin_smartredirect_ticket_".$id);
			$target->datas['##ticket.smartredirect.urlapprove##'] = urldecode($CFG_GLPI["url_base"]."/index.php". "?redirect=plugin_smartredirect_ticket_".$id."_Ticket$2");
			$target->datas['##ticket.smartredirect.urlvalidation##'] = urldecode($CFG_GLPI["url_base"]."/index.php". "?redirect=plugin_smartredirect_ticket_".$id."_TicketValidation$1");
			$target->datas['##ticket.smartredirect.urldocument##'] = urldecode($CFG_GLPI["url_base"]."/index.php". "?redirect=plugin_smartredirect_ticket_".$id."_DocumentItem$1");
		}
		
		
	}
}
?>