<?php 
   require_once __DIR__ . '/../../config/database.php';

   class EntriesModel{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function getEntries(string $userId):array|bool{
         $stmt = $this->pdo->prepare(
            'SELECT `id`, `description`, `category`, `date`, `fixed`, `end_date`, `last_edition`, `icon`, `value`
             FROM `entries` 
             WHERE `foreing_key` = :userID 
             ORDER BY `date` 
             DESC 
             LIMIT 10'
         );
         $stmt->bindValue(':userID', $userId);
         $stmt->execute();
         return  $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

      public function insertEntry(array $data):bool{

         $keys = [];
         $params = [];
         $placeholders = [];
         
         foreach($data AS $field => $value){
            $keys[] = "`$field`";
            $placeholders[] = ":$field";
            $params[":$field"] = $value;
         }

         $fields = implode(',', $keys);
         $placeholders = implode(',' , $placeholders);

         //Puting together the SQL Query.
         $sql = "INSERT INTO `entries` ($fields) VALUES ($placeholders)";
         $stmt = $this->pdo->prepare($sql);

         return ($stmt->execute($params));
      }

      public function updateEntry(array $data){

         $params = [];
         $preQuery = [];

         foreach($data AS $field => $value){
            $params[":$field"] = $value;
            if($field == 'id') continue;
            $preQuery[] = "`$field` = :$field";
         }

         $preQuery = implode(',', $preQuery);
         $query = "UPDATE `entries` SET $preQuery WHERE `id` = :id";
         
         $stmt = $this->pdo->prepare($query);
         
         return $stmt->execute($params);

      }
   }
?>