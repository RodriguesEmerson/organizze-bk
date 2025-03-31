<?php 
   require_once __DIR__ . '/../../config/database.php';

   class EntriesModel{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function insertEntry(array $data){
         $query = [];
         $params = [];
         foreach($data AS $field => $value){
            $query[] = "`$field`";
            $params[":$field"] = $value;
         }

         echo json_encode($query);
         exit;
      }
   }
?>