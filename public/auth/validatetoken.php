<?php 
   require_once __DIR__ . '/../../src/inc/headerTop.config.inc.php';
   require_once __DIR__ . '/../../src/inc/getToken.inc.php';
   require_once __DIR__ . '/../../src/Controllers/authController.php';
   
   if($request == 'auth/validatetoken.php' && $method == 'POST'){
      AuthController::validateToken($token);
   }
?>