<?php 
   require_once __DIR__ . '/../../config/database.php';

   class EntriesModel{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function insertEntry(array $data):bool{

         $query = [];
         $params = [];
         $placeholders = [];
         
         foreach($data AS $field => $value){
            $query[] = "`$field`";
            $placeholders[] = ":$field";
            $params[":$field"] = $value;
         }

         $fields = implode(',', $query);
         $placeholders = implode(',' , $placeholders);

         //Puting together the SQL Query.
         $sql = "INSERT INTO `entries` ($fields) VALUES ($placeholders)";
         $stmt = $this->pdo->prepare($sql);

         return ($stmt->execute($params));
         //Depuring
         // try{
         //    $stmt->execute($params);
         // }catch(Exception $e){
         //    echo json_encode($e->getMessage());
         //    exit;
         // }
      }
   }
?>