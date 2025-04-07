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
            $data['fixed'] == 1 ? $data['fixed'] = true : $data['fixed'] = false; 
            $entriesController->insertEntry($data['id'], $userId, $data['description'], $data['category'], $data['date'], $data['fixed'], $data['end_date'], $data['icon'], $data['value']);
            exit;
         break;  
         case 'UPDATE':
            if(isset($data['fixed'])){
               $data['fixed'] == 1 ? $data['fixed'] = true : $data['fixed'] = false; 
            }
            // echo json_encode($data);exit;
            $entriesController->updateEntry($data, $userId);
            exit;
         break;

      }
   }
?>