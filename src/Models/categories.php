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

         $query = "SELECT `name`, `type`, `icon`, `id` FROM  `categories` WHERE `foreing_key` = :foreing_key ORDER BY `name` ASC";
         if(!empty($type)){
            $query = "SELECT `name`, `type`, `icon`, `id` FROM  `categories` WHERE `foreing_key` = :foreing_key AND `type` = :type ORDER BY `name` ASC";
         }

         $stmt = $this->pdo->prepare($query);
         $stmt->execute($params);
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

      public function insertCategory(array $data, string $userId):bool{
         $data['foreing_key'] = $userId;
         $keys = [];
         $params = [];
         foreach($data AS $field => $value){
            $keys[] = $field;
            $placeholders[] = ":$field";
            $params[":$field"] = $value;
         }

         $fields = implode(',', $keys);
         $placeholders = implode(',', $placeholders);

         $query = "INSERT INTO `categories` ($fields) VALUES ($placeholders)";

         $stmt = $this->pdo->prepare($query);
         return ($stmt->execute($params));
      }

      public function updateCategory(array $data):bool{
         $params = [];
         $preQuery = [];

         foreach($data AS $field => $value){
            $params[":$field"] = $value;
            if($field == 'id') continue;
            $preQuery[] = "`$field` = :$field"; 
         }

         $preQuery = implode(',', $preQuery);
         $query = "UPDATE `categories` SET $preQuery WHERE `id` = :id";
         
         $stmt = $this->pdo->prepare($query);
         
         return $stmt->execute($params);
      }

      public function deleteCategory(string $id){

         $stmt = $this->pdo->prepare('DELETE FROM `categories` WHERE `id` = :id');
         $stmt->bindValue(':id', $id);
         return $stmt->execute();
      }
   }
?>