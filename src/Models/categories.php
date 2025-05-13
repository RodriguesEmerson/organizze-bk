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

      public function updateCategory(array $data, string $userId):bool{

         $stmtCategory = $this->pdo->prepare(
         'UPDATE `categories` 
         SET `name` = :name, `type` = :type, `icon` = :icon 
         WHERE `foreing_key` = :userId AND `id` = :id'
         );
         $stmtCategory->bindValue(':name', $data['name']);
         $stmtCategory->bindValue(':type', $data['type']);
         $stmtCategory->bindValue(':icon', $data['icon']);
         $stmtCategory->bindValue(':id', $data['id']);
         $stmtCategory->bindValue(':userId', $userId);

         $stmtEntries = $this->pdo->prepare(
            'UPDATE `entries` 
            SET `category` = :category, `type` = :type, `icon` = :icon 
            WHERE `foreing_key` = :userId AND `category` = :categoryOldName'
         );
         $stmtEntries->bindValue(':category', $data['name']);
         $stmtEntries->bindValue(':type', $data['type']);
         $stmtEntries->bindValue(':icon', $data['icon']);
         $stmtEntries->bindValue(':userId', $userId);
         $stmtEntries->bindValue(':categoryOldName', $data['categoryOldName']);

         try{
            $this->pdo->beginTransaction(); // Inicia a transação
               //Executa uma ou mais queries
               $stmtCategory->execute();
               $stmtEntries->execute();
            $this->pdo->commit(); //Salva as alterações se tudo deu certo, 
            return true;

         }catch(Exception $e){
            $this->pdo->rollBack(); //Se dar erro em alguma query, desfaz tudo.
            throw new Exception($e->getMessage(), $e->getCode());
         }
      }

      public function deleteCategory(array $data, string $userId){

         $stmtCategory = $this->pdo->prepare('DELETE FROM `categories` WHERE `id` = :id');
         $stmtCategory->bindValue(':id', $data['id']);

         $stmtEntries = $this->pdo->prepare(
            'UPDATE `entries`
            SET `category` = :category, `icon` = :icon 
            WHERE `foreing_key` = :userId AND `category` = :name'
         );
         $stmtEntries->bindValue(':category', 'sem categoria');
         $stmtEntries->bindValue(':icon', 'c-no-category.png');
         $stmtEntries->bindValue(':userId', $userId);
         $stmtEntries->bindValue(':name', $data['name']);

         try{

            $this->pdo->beginTransaction();
               $stmtCategory->execute();
               $stmtEntries->execute();
            $this->pdo->commit();
            return true;

         }catch(Exception $e){
            
            $this->pdo->rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
         }
      }
   }
?>