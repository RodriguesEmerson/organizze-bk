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
         foreach($data AS $field => $value){
            $query[] = "`$field`";
            $params[":$field"] = $value;
         }

         $fields = implode(',', $query);
         $placeholders = implode(',' , $params);
         $sql = "INSERT INTO `entries` ($fields) VALUES ($placeholders)";
         $stmt = $this->pdo->prepare($sql);
         
         // echo json_encode([count(explode(',', $query)), count($params)]);exit;
         try{
            $stmt->execute($params);
         }catch(Exception $e){
            echo json_encode($e->getMessage());
            exit;
         }
         if(!$stmt->execute($params)){
            throw new Exception('Internal server error', 500);
         }
          
         return true;
      }
   }
?>