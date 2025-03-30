<?php 
   require __DIR__ . '/../src/Helpers/headerTop.config.inc.php';

   if($request == 'signin.php' && $method == 'POST'){
      require __DIR__ . '/../src/Auth/authHandler.php';
      $authHandler = new AuthHandler();
      $data = json_decode(file_get_contents('php://input'), true);

      $authHandler->authenticateUser($data['email'], $data['password']);
   }
?>