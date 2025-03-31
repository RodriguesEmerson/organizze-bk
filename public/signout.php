<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../src/Auth/authHandler.php';
   require_once __DIR__ . '/../src/inc/getToken.inc.php';
   
   
   if($request == 'signout.php' && $method == 'POST'){
      if($token && JWTHandler::validateToken($token)){
         AuthHandler::signout();
        
      }
   }

?>