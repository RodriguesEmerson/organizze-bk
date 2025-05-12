<?php 
   require __DIR__ . '/../../src/inc/headerTop.config.inc.php';
   require __DIR__ . '/../../src/inc/validateToken.inc.php';
   require __DIR__ . '/../../src/Controllers/authController.php';
   require __DIR__ . '/../../src/inc/getToken.inc.php';

   if($request == 'auth/userinfo.php' && $method == 'GET'){
      $authContoller = new AuthController();
      $userId = JWTHandler::getUserId($token);
      $authContoller->getUserInfo($userId);
   }
?>