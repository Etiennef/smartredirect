<?php

include("../../../inc/includes.php");
global $CFG_GLPI;

Session::checkLoginUser();
PluginSmartredirectTicket::manageRedirect($_GET);

