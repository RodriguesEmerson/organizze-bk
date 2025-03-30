<?php 
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   
   header('Content-Type: application/json; charset=UTF-8');

   $request =str_replace( '/organizze-bk/public/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
   $method = $_SERVER['REQUEST_METHOD'];

   if($request == 'signup.php'){
      require_once __DIR__ . '/../src/Auth/authHandler.php';
      $authHandler = new AuthHandler();
      $data = json_decode(file_get_contents('php://input'), true);
      $authHandler->createUser($data['id'], $data['name'], $data['lastname'], $data['email'], $data['password']);

   };

?>