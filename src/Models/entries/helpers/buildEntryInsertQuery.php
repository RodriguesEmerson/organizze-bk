<?php

   require_once __DIR__ . '/../../../Helpers/utils.php';

class BuildEntryInsertQuery{
   public static function query(array $data):array{
      $columns = []; //Query's columns;
      $params = []; //Query's params;
      $singleEntryPlaceholders = [];
      $placeholders = [];

      foreach($data AS $field => $value){
         $columns[] = "`$field`";
         $singleEntryPlaceholders[] = ":$field";
         $params[":$field"] = $value;
      }  

      $columns = implode(',', $columns); 
      $placeholders[] = "(" . implode(',' , $singleEntryPlaceholders) . ")"; 

      if($data['fixed'] === true){
         $startDate = new DateTime($data['date']);
         $endDate = new DateTime($data['end_date']);
         //Moths from start to end date.
         $monthsFromStartToEndDate = $startDate->diff($endDate)->m ;
         
         for($i = 1; $i <= $monthsFromStartToEndDate; $i++){
            //It use the index as the number of months to add;
            $currentDate = Utils::incrementMonth($data['date'], $i);

            $placeholdersRow = [];

            foreach ($data as $field => $value) {
               if($field == 'date'){$value = $currentDate;} //Change to new date;
               if($field == 'id'){$value = Utils::genereteUUID();} //Create a new UUID for the entry;
               if($field == 'effected'){$value = false;} //The other entries isn't effected yet;
               $key = ":$field" . "_$i"; //placeholder - :field_1
               $placeholdersRow[] = $key; //Add the placeholder
               $params[$key] = $value; //Add the param - :field_1 = value;
            };

            $placeholders[] = "(" . implode(',', $placeholdersRow) . ")";
         }
      }  
      
      //Puting together the SQL Query.
      $placeholders =  implode(',' , $placeholders); 
      $query = "INSERT INTO `entries` ($columns) VALUES $placeholders";
      return ['query' => $query, 'params' => $params];
   }

}