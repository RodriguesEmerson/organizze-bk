<?php 
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   
   header('Content-Type: application/json; charset=UTF-8');
   header("Access-Control-Allow-Origin: http://localhost:3000");  // Permitir apenas localhost:3000
   header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, UPDATE");
   header("Access-Control-Allow-Headers: Content-Type, Authorization, origin");
   header("Access-Control-Allow-Credentials: true"); //Obrigatório o envio de: credentials: 'include' no header pelo front.

   $request =str_replace( '/organizze-bk/public/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
   $method = $_SERVER['REQUEST_METHOD'];
?>