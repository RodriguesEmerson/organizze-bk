<?php 

   require_once __DIR__ . '/../../config/database.php';

   class CategoriesModel{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function getCategoreis(string $userId, string|null $type):array|bool{

         $params = [];
         $params[":foreing_key"] = $userId;
         if(!empty($type)){
            $params[":type"] = $type;
         }

         $query = "SELECT `name`, `type`, `image`, `id` FROM  `categories` WHERE `foreing_key` = :foreing_key";
         
         if(!empty($type)){
            $query = "SELECT `name`, `type`, `image`, `id` FROM  `categories` WHERE `foreing_key` = :foreing_key AND `type` = :type";
         }


         $stmt = $this->pdo->prepare($query);
         // echo json_encode($params); exit;

         // $stmt = $this->pdo->prepare(
         //    'SELECT `name`, `type`, `image`, `id` 
         //    FROM `categories` 
         //    WHERE `foreing_key` = :userId AND `type` = :type'
         // );
         // $stmt->bindValue(':userId', $userId);
         // $stmt->bindValue(':type', $type);
         $stmt->execute($params);
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
   }
?>