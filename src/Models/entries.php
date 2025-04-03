<?php 
   require_once __DIR__ . '/../../config/database.php';

   class EntriesModel{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function getEntries(string $userId):array|bool{
         $stmt = $this->pdo->prepare('SELECT * FROM `entries` WHERE `foreing_key` = :userID ORDER BY `date` DESC LIMIT 10');
         $stmt->bindValue(':userID', $userId);
         $stmt->execute();
         // try{
         //    echo json_encode($stmt->execute());exit;
            
         // }catch(Exception $e){
         //    echo json_encode($e->getMessage());exit;
         // }
         return  $stmt->fetchAll(PDO::FETCH_ASSOC);
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
      }
   }
?>