<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../src/inc/validateToken.inc.php';
   require_once __DIR__ . '/../src/Controllers/entriesController.php';
   
   if($request === 'availables-tables.php' && $method === 'GET'){
      $entriesController = new EntriesController();
      $userId = JWTHandler::getUserId($token);

      $entriesController->getAvailablesTalbes($userId);
   }
?>