<?php 
   require __DIR__ . '/../src/inc/headerTop.config.inc.php';

   if($request == 'signup.php' && $method == 'POST'){
      require_once __DIR__ . '/../src/Auth/authHandler.php';
      $authHandler = new AuthHandler();
      $data = json_decode(file_get_contents('php://input'), true);
      $authHandler->createUser($data['id'], $data['name'], $data['lastname'], $data['email'], $data['password']);
   };

?>