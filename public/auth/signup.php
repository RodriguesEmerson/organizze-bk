<?php 
   require __DIR__ . '/../../src/inc/headerTop.config.inc.php';
   require_once __DIR__ . '/../../src/Controllers/authController.php';

   if($request == 'auth/signup.php' && $method == 'POST'){
      $authController = new AuthController();
      $data = json_decode(file_get_contents('php://input'), true);
      $authController->createUser($data);
   };

?>