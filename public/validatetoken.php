<?php 
   require __DIR__ . '/../src/Helpers/headerTop.config.inc.php';
   require_once __DIR__ . '/../src/Auth/JWT/JWT.php';

   $headers = apache_request_headers();
   
   $headerToken = $headers['Authorization'] ?? null;
   
   
   if(empty($headerToken)) $headerToken = null;
   $cookeToken = $_COOKIE['JWTToken'] ?? null;
   $token = $cookeToken ?? $headerToken ?? null;
   if($token){
      $token = str_replace('Bearer ', '', $token);
   }
   // echo json_encode($token);exit;
   if($request == 'validatetoken.php' && $method == 'POST'){
      if($token && JWTHandler::validateToken($token)){
         http_response_code(200);
         echo json_encode(['message' => 'Valid token']);
         exit;
      }
      http_response_code(401);
      echo json_encode(['message' => 'Invalid token']);
   }

?>