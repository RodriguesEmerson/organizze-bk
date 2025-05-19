<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../src/inc/validateToken.inc.php';
   require_once __DIR__ . '/../src/Controllers/entriesController.php';
   $entriesController = new EntriesController();
   
   if($request == 'entries.php'){
      $data = json_decode(file_get_contents('php://input'), true) ?? null;
      $userId = JWTHandler::getUserId($token);
      
      switch($method){
         case 'GET':
            $query = parse_url($_SERVER['REQUEST_URI'])['query'];
            parse_str($query, $parameters);
            $reporType = $parameters['reportType'];

            switch($reporType){
               case 'monthly':
                  $year = $parameters['year'];
                  $month = $parameters['month'];
                  $entriesController->getEntries($userId, $year, $month);
               break;
               case 'yearly':
                  $year = $parameters['year'];
                  $entriesController->getYearlyData($year, $userId);
               break;
               case 'availableTables':
                  $entriesController->getAvailablesTalbes($userId);
               break;
            };
            exit;
         break;
         case 'POST':
            //I know it has a better way to do it, but it is enough for now.
            // $data['fixed'] == 1 ? $data['fixed'] = true : $data['fixed'] = false; 
            $entriesController->insertEntry($data, $userId);

            exit;
         break;  
         case 'PUT':
            $entriesController->updateEntry($data, $userId);
            exit;
         break;
         case 'DELETE':
            $entriesController->deleteEntry($data, $userId);
         break;

      };
   };
   http_response_code(400);
   echo json_encode(['mesage' => 'Missing data']);
   exit;
?>