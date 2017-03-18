<?php

include("../../../inc/includes.php");

Session::checkLoginUser();
$config = PluginSmartredirectPluginconfig::getConfigValues();

// Contrôle général de la syntaxe du lien
if((isset($_GET['profile']) && !preg_match('@^\d+$@', $_GET['profile'])) ||
   !isset($_GET['entity']) || !preg_match('@^\d+$@', $_GET['entity']) ||
   !isset($_GET['type']) || !preg_match('@^[12]$@', $_GET['type']) ||
   !isset($_GET['category']) || !preg_match('@^\d+$@', $_GET['category'])) {
   Session::addMessageAfterRedirect(__('Error parsing redirect information', 'smartredirect').'. '.__('Please inform the one who gave you this link that it is badly formed', 'smartredirect'), true, ERROR);
   redirect($config['url_syntax_error']);
}

// Changement de profil si nécessaire
if(isset($_GET['profile']) && $_GET['profile'] != $_SESSION['glpiactiveprofile']['id']) {
   Session::changeProfile($_GET['profile']);
   if($_SESSION['glpiactiveprofile']['id'] != $_GET['profile']) {
      Session::addMessageAfterRedirect(__('Unable to select the right profile. Either this link is poorly deseigned, either it was not meant for you!', 'smartredirect'), true, ERROR);
      redirect($config['url_profile_error']);
   }
}

// changement d'entité si nécessaire
if($_GET['entity'] != $_SESSION['glpiactive_entity'])
   Session::changeActiveEntities($_GET['entity']);
if($_SESSION['glpiactive_entity'] != $_GET['entity']) {
   Session::addMessageAfterRedirect(__('Unable to select the right entity. Either this link is poorly deseigned, either it was not meant for you!', 'smartredirect'), true, ERROR);
   redirect($config['url_entity_error']);
}

// présélection du type et de la catégorie
$input = array();
$input['type'] = $_GET['type'];

if(checkCategory($_GET['category'], $input)) {
   $input['itilcategories_id'] = $_GET['category'];
} else {
   Session::addMessageAfterRedirect(__('Category have not been selected, please correct it...', 'smartredirect'), true, ERROR);
   redirect($config['url_category_error']);
}

$_SESSION['saveInput']['Ticket'] = $input;
redirect($config['url_success']);


function redirect($url) {
   global $CFG_GLPI;
   
   if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk') {
      $redir = "/front/helpdesk.public.php?create_ticket=1";
   } else {
      $redir = "/front/ticket.form.php";
   }
   
   if(!empty($url) && $url[0]!='&') {
      Html::redirect($url);
   } else {
      if(!empty($url) && !preg_match('@\?@', $redir))
         $url[0] = '?'; // transformation du & en ? si nécessaire
      Html::redirect($CFG_GLPI["root_doc"].$redir.$url);
   }
}

function checkCategory($id, &$input) {
   $category = new ITILCategory();
   if($category->getFromDB($id)) {
      
      // Vérification de la compatibilité de la catégorie avec l'entité courante
      if($category->isRecursive()) {
         if($category->getEntityID() != $_SESSION['glpiactive_entity'] && !in_array($category->getEntityID(), getAncestorsOf("glpi_entities", $_SESSION['glpiactive_entity'])))
            return false;
      } else {
         if($category->getEntityID() != $_SESSION['glpiactive_entity'])
            return false;
      }
      
      // vérification de la compatibilité de la catégorie avec le type sélectionné
      if($input['type']==Ticket::INCIDENT_TYPE && !$category->fields['is_incident'])
         return false;
      if($input['type']==Ticket::DEMAND_TYPE && !$category->fields['is_request'])
         return false;
      
      // vérification de la visibilité de l'entité en mode helpdesk si celui-ci est sélectionné
      if($_SESSION["glpiactiveprofile"]["interface"] == 'helpdesk' && !$category->fields['is_helpdeskvisible'])
         return false;
      
      return true;
   } else return false;
}
