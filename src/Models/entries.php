<?php
   require_once __DIR__ . '/../../config/database.php';
   require_once __DIR__ . '/../Helpers/utils.php';

   class EntriesModel{
      private $pdo;

      public function __construct(){
         $this->pdo = Database::getConnection();
      }

      public function getEntries(string $userId, string $year, string $month):array|bool{
         $period = "%$year-$month%";
         $stmt = $this->pdo->prepare(
            'SELECT `id`, `description`, `category`, `type`, `date`, `fixed`, `end_date`, `last_edition`, `icon`, `value`, `effected`, `recurrence_id`
            FROM `entries`
            WHERE foreing_key = :userId AND `date` LIKE :period
            ORDER BY `date` DESC' //It should be foreign_key - just a typo mistake.
         );
         
         $stmt->bindValue(':userId', $userId);
         $stmt->bindValue(':period', $period);
         $stmt->execute();
         return  $stmt->fetchAll(PDO::FETCH_ASSOC);
      }

      public function getYearlyData(string $year, string $userId):array|bool{
         $yearMonth = "%$year-%m";
         $stmt = $this->pdo->prepare(
            'SELECT *
               FROM (
                  SELECT 
                     DATE_FORMAT(`date`, :yearMonth) AS month, `type`, SUM(`value`) AS total
                     FROM `entries`
                     WHERE `foreing_key` = :userId
                     GROUP BY  month, `type`
               ) AS mensal
               ORDER BY month ASC' //Precisar ser ASC, quando o Front faz um loop ficará DESC
         );
         
         $stmt->bindValue(':yearMonth', $yearMonth);
         $stmt->bindValue(':userId', $userId);
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
         exit;
      }

      public function getEntriesSum(string $userId, string $year, string $month):array|bool{
         $period = "%$year-$month%";
         $stmt = $this->pdo->prepare(
            "SELECT 
               SUM(CASE WHEN `type`= 'income' AND `effected` = 1 THEN `value` ELSE 0 END) AS `incomes_sum`,
               SUM(CASE WHEN `type`= 'expense' AND `effected` = 1 THEN `value` ELSE 0 END) AS `expenses_sum`
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
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }


      public function insertEntry(array $data):bool{
         $columns = []; //Query's columns;
         $params = []; //Query's params;
         $singleEntryPlaceholders = [];
         $allPlaceholders = [];

         foreach($data AS $field => $value){
            $columns[] = "`$field`";
            $singleEntryPlaceholders[] = ":$field";
            $params[":$field"] = $value;
         }  

         $columns = implode(',', $columns); 
         $allPlaceholders[] = "(" . implode(',' , $singleEntryPlaceholders) . ")"; 

         if($data['fixed'] === true){
            $startDate = new DateTime($data['date']);
            $endDate = new DateTime($data['end_date']);
            //Moths from start to end date.
            $monthsFromStartToEndDate = $startDate->diff($endDate)->m ;
            
            for($i = 1; $i <= $monthsFromStartToEndDate; $i++){
               //It use the index as the number of months to add;
               $currentDate = Utils::incrementOneMonth($data['date'], $i);

               $placeholdersRow = [];

               foreach ($data as $field => $value) {
                  if($field == 'date'){$value = $currentDate;} //Change to new date;
                  if($field == 'id'){$value = Utils::genereteUUID();} //Create a new UUID for the entry;
                  if($field == 'effected'){$value = false;} //The other entries isn't effect yet;
                  $key = ":$field" . "_$i"; //placeholder - :field_1
                  $placeholdersRow[] = $key; //Add the placeholder
                  $params[$key] = $value; //Add the param - :field_1 = value;
               };

               $allPlaceholders[] = "(" . implode(',', $placeholdersRow) . ")";
            }
         }  
         
         //Puting together the SQL Query.
         $allPlaceholders =  implode(',' , $allPlaceholders); 
         $query = "INSERT INTO `entries` ($columns) VALUES $allPlaceholders";
         $stmt = $this->pdo->prepare($query);

         return ($stmt->execute($params));
      }


      public function updateEntry(array $data, string $userId):array|bool{
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

      public function deleteEntry(string $entryId, string $userId):bool{
         $stmt = $this->pdo->prepare(
            'DELETE FROM `entries` WHERE `foreing_key` = :userId AND `id` = :entryId'
         );
         $stmt->bindValue(':userId', $userId);
         $stmt->bindValue(':entryId', $entryId);

         return $stmt->execute();
      }
   }
?>

