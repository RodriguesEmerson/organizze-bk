<?php 
   require_once __DIR__ . '/../../config/database.php';

   class User{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function getUserByEmail(string $email):array{
         $stmt = new $this->pdo->prepare('SELECT * FROM `users` WHERE `email` = :email');
         $stmt->bindValue(':email', $email);
         $stmt->execute();
         $result = $stmt->fetch(PDO::FETCH_ASSOC);
         
         if(!$result){
            throw new Exception('User not found');
            exit;
         }
         return $result;

      }

      public function createUser(string $id, string $name, string $email, string $lastname, string $startDate  , string $password):bool{

         if(!empty($id) && !empty($name) && !empty($lastname) && !empty($email) && !empty($password)){
            $stmt = $this->pdo->prepare('INSERT INTO `users` (`id`, `name`, `email`, `lastname`, `start_date`, `password`)
                                             VALUES (:id, :name, :email, :lastname, :start_date`, :password)');
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':lastname', $lastname);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $password);
            $result = $stmt->execute();
            if(!$result){
               throw new Exception('Internal server error', 500);
               exit;
            }
            return $result;
         };
         throw new Exception('All fields are required', 400);
      }
   }
?>