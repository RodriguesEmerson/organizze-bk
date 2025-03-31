<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../src/Auth/authHandler.php';
   require_once __DIR__ . '/../src/inc/getToken.inc.php';
   
   
   if($request == 'signout.php' && $method == 'POST'){
      if($token && JWTHandler::validateToken($token)){
         AuthHandler::signout();
         exit;
      }

      http_response_code(500);
      echo json_encode(['message' => 'Internal server error.']);
   }
?>