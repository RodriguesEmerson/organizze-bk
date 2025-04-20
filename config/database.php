<?php 
   require_once __DIR__ . '/loadEnv.php';
   class Database{
      private static $pdo;

      public static function getConnection(){
         if(!self::$pdo){ //It checks if has already a connection

            //It doesn't worked as I needed. It only worked calling it manualy.
            // $db = getenv('DB_NAME');
            // $host = getenv('DB_HOST');
            // $user = getenv('DB_USER');
            // $password = getenv('DB_PASS');

            $db = $_ENV['DB_NAME'] ?? '';
            $host = $_ENV['DB_HOST'] ?? '';
            $user = $_ENV['DB_USER'] ?? '';
            $password = $_ENV['DB_PASS'] ?? '';

            try{
               self::$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", "$user", "$password");
               self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
               header('Content-Type: application/json');
               echo json_encode(['message' => 'It was not possible conneting to the database, try again.']);
               die();
            }
         }
         return self::$pdo;
      }
   }
?>