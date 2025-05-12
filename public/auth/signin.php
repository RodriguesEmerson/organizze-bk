<?php 
   require __DIR__ . '/../../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../../src/Controllers/authController.php';

   if($request == 'auth/signin.php' && $method == 'POST'){
      $authContoller = new AuthController();
      $data = json_decode(file_get_contents('php://input'), true);

      $authContoller->authenticateUser($data);
   }
?>