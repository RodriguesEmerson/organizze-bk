<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../src/inc/validateToken.inc.php';
   // echo json_encode('message');exit;
   require_once __DIR__ . '/../src/Controllers/entriesController.php';
   $entriesController = new EntriesController();

   if($request == 'entries.php'){
      switch($method){
         case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            $data['fixed'] == 'on' ? $data['fixed'] = true : $data['fixed'] = false; 
            $data['value'] = str_replace(',', '.', $data['value']);
            $dateArray = explode('/', $data['date']);
            $date = $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0];
            echo json_encode($date);exit;
            $userId = JWTHandler::getUserId($token);
         
            $entriesController->insertEntry($data['id'], $userId, $data['desc'], $data['categ'], $data['date'], $data['fixed'], $data['endDate'], $data['icon'], $data['value']);
         break;
      }
   }
?>