<?php 
   require __DIR__ . '/../../src/inc/headerTop.config.inc.php';
   require_once __DIR__ . '/../../src/inc/validateToken.inc.php';
   require_once __DIR__ . '/../../src/Controllers/authController.php';
   
   if($request == 'auth/signout.php' && $method == 'POST'){
      AuthController::signout();
      exit;
   }
?>