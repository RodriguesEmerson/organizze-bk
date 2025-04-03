<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../src/inc/validateToken.inc.php';
   require_once __DIR__ . '/../src/Controllers/entriesController.php';
   $entriesController = new EntriesController();
   // echo json_encode('message');exit;

   if($request == 'entries.php'){

      $data = json_decode(file_get_contents('php://input'), true) ?? null;
      $userId = JWTHandler::getUserId($token);
      
      switch($method){
         case 'GET':
            $entriesController->getEntries($userId);
            exit;
         break;
         case 'POST':
            $data['fixed'] == 'on' ? $data['fixed'] = true : $data['fixed'] = false; 
            $entriesController->insertEntry($data['id'], $userId, $data['desc'], $data['categ'], $data['date'], $data['fixed'], $data['endDate'], $data['icon'], $data['value']);
            exit;
         break;  
         case 'UPDATE':
            $data['fixed'] == 'on' ? $data['fixed'] = true : $data['fixed'] = false; 
            $entriesController->updateEntry($data, $userId);
            exit;
         break;

      }
   }
?>