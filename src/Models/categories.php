<?php 

   require_once __DIR__ . '/../../config/database.php';

   class CategoriesModel{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function getCategoreis(string $userId, string $type):array|bool{
         $stmt = $this->pdo->prepare(
            'SELECT `name`, `type`, `image`, `id` 
            FROM `categories` 
            WHERE `foreing_key` = :userId AND `type` = :type'
         );
         $stmt->bindValue(':userId', $userId);
         $stmt->bindValue(':type', $type);
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
   }
?>