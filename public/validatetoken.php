<?php 
   require_once __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require_once __DIR__ . '/../src/Auth/JWT/JWT.php';
   require_once __DIR__ . '/../src/inc/getToken.inc.php';
   
   if($request == 'validatetoken.php' && $method == 'POST'){
      if($token && JWTHandler::validateToken($token)){
         http_response_code(200);
         echo json_encode(['message' => 'Valid token']);
         exit;
      }
      http_response_code(401);
      echo json_encode(['message' => 'Invalid token.']);
   }
?>