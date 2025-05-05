<?php

   require_once __DIR__ . '/../../config/database.php';

   class EntriesModel{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }
       

      //CRIAR PARAMETRO PARA PEGAR EM OFFSET E LIMIT
      public function getEntries(string $userId, string $year, string $month):array|bool{
         $period = "%$year-$month%";
         $stmt = $this->pdo->prepare(
            "SELECT `id`, `description`, `category`, `type`, `date`, `fixed`, `end_date`, `last_edition`, `icon`, `value`
               FROM (
                  SELECT *, ROW_NUMBER() OVER (ORDER BY `date` DESC) as row_num
                  FROM `entries`
                  WHERE `date` LIKE :period AND `type` = 'expense'
               ) as ranked
               WHERE row_num BETWEEN 1 AND 10
               UNION ALL
               SELECT `id`, `description`, `category`, `type`, `date`, `fixed`, `end_date`, `last_edition`, `icon`, `value`
               FROM (
                  SELECT *, ROW_NUMBER() OVER (ORDER BY `date` DESC) as row_num
                  FROM `entries`
                  WHERE `date` LIKE :period AND `type` = 'income'
               ) as ranked
               WHERE row_num BETWEEN 1 AND 10"
         );
         
         $stmt->bindValue(':userID', $userId);
         $stmt->bindValue(':period', $period);
         $stmt->execute();
         return  $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

      public function getEntriesCount(string $userId, string $year, string $month):array|bool{
         $period = "%$year-$month%";
         $stmt = $this->pdo->prepare(
            "SELECT 
               COUNT(CASE WHEN `type` = 'expense' THEN 0 END) AS expenseRows,
               COUNT(CASE WHEN `type` = 'income' THEN 0 END) AS incomesRows
            FROM `entries`
            WHERE foreing_key = :userId AND `date` LIKE :period"
         );
         
         $stmt->bindValue(':userId', $userId);
         $stmt->bindValue(':period', $period);
         $stmt->execute();
         return  $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

      public function getEntriesSum(string $userId, string $year, string $month){
         $period = "%$year-$month%";
         $stmt = $this->pdo->prepare(
            "SELECT 
               SUM(CASE WHEN `type`= 'income' THEN `value` ELSE 0 END) AS `incomes_sum`,
               SUM(CASE WHEN `type`= 'expense' THEN `value` ELSE 0 END) AS `expenses_sum`
            FROM `entries`
            WHERE foreing_key = :userId AND `date` LIKE :period"
         );
         $stmt->bindValue(':userId', $userId);
         $stmt->bindValue(':period', $period);
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

      public function getAvailablesTalbes($userId){
         $stmt = $this->pdo->prepare('SELECT DATE_FORMAT(`date`, "%Y-%m") AS `y_m` FROM `entries` WHERE `foreing_key` = :userId GROUP BY `y_m`');
         $stmt->bindValue(':userId', $userId);  
         $stmt->execute();
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         echo json_encode($result);
         exit;
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