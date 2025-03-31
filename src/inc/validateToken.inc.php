<?php 
   require_once __DIR__ . '/../Auth/JWT/JWT.php';
   $headers = apache_request_headers();
   $headerToken = $headers['Authorization'] ?? null;
   
   if(empty($headerToken)) $headerToken = null;
   $cookeToken = $_COOKIE['JWTToken'] ?? null;
   $token = $cookeToken ?? $headerToken ?? null;
   if($token){
      $token = str_replace('Bearer ', '', $token);
   }   
   if(!$token || !JWTHandler::validateToken($token)){
      http_response_code(401);
      echo json_encode(['message' => 'Invalid token']);
      exit;
   }
?>