<?php 
   require_once __DIR__ . '/../../config/database.php';

   class User{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function getUserByEmail(string $email):array{
         $stmt = $this->pdo->prepare('SELECT * FROM `users` WHERE `email` = :email');
         $stmt->bindValue(':email', $email);
         $stmt->execute();

         $result = $stmt->fetch(PDO::FETCH_ASSOC);
         if(!$result){
            throw new Exception('User not found', 400);
            exit;
         }
         return $result;
      }

      public function getUserInfo(string $userId):array|bool{
         
         $stmt = $this->pdo->prepare('SELECT `name`, `email`, `image` FROM `users` WHERE `id` = :userId');
         $stmt->bindValue(':userId', $userId);
         $stmt->execute();
         return $stmt->fetch(PDO::FETCH_ASSOC);
      }

      public function createUser(string $id, string $name, string $email, string $lastname, string $startDate  , string $password):bool{

         $stmt = $this->pdo->prepare(
            'INSERT INTO `users` (`id`, `name`, `email`, `lastname`, `start_date`, `password`)
            VALUES (:id, :name, :email, :lastname, :start_date, :password)'
         );

         $stmt->bindValue(':id', $id);
         $stmt->bindValue(':name', $name);
         $stmt->bindValue(':lastname', $lastname);
         $stmt->bindValue(':email', $email);
         $stmt->bindValue(':start_date', $startDate);
         $stmt->bindValue(':password', $password);
         
         return $stmt->execute();
      }
   }
?>