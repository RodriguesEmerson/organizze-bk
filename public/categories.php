<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../src/inc/validateToken.inc.php';
   require_once __DIR__ . '/../src/Controllers/categoriesController.php';

   if($request == 'categories.php'){
      $categoriesController = new CategoriesController();
      $userId = JWTHandler::getUserId($token);

      switch($method){
         case ('GET'):
            $query = parse_url($_SERVER['REQUEST_URI'])['query'];
            parse_str($query, $parameters);
            $type = $parameters['type'];
            $categoriesController->getCategories($userId, $type);
         break;
      };
      exit;
   }

   http_response_code(400);
   echo json_encode(['message' => 'Route not found.']);
?>