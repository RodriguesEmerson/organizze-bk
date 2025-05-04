<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../src/inc/validateToken.inc.php';
   require_once __DIR__ . '/../src/Controllers/categoriesController.php';

   if($request == 'categories.php'){
      $categoriesController = new CategoriesController();
      $userId = JWTHandler::getUserId($token);

      switch($method){
         case 'GET':
            $type = null;
            $query = parse_url($_SERVER['REQUEST_URI'])['query'] ?? null;
            if($query){
               parse_str($query, $parameters);
               $type = $parameters['type'];
            };

            $categoriesController->getCategories($userId, $type);
         break;
         case 'POST': 
            $data = json_decode(file_get_contents('php://input'), true);
            $categoriesController->insertCategory($data, $userId);
         break;
      };
      exit;
   }

   http_response_code(400);
   echo json_encode(['message' => 'Route not found.']);
?>