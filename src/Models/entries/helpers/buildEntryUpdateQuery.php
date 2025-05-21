
<?php

   require_once __DIR__ . '/../../../Helpers/utils.php';

class BuildEntryUpdateQuery{
   public static function query(array $data, string $userId):array{

      $params = [];
      $placeholders = [];
      $data['foreing_key'] = $userId;
      $query = '';

      foreach ($data as $field => $value) {
         if ($field == 'id') continue;
         if ($field == 'change_recurrence') continue;
         if ($field == 'recurrence_id') continue;
         $params[":$field"] = $value;

         if ($field == 'date') continue;
         if ($field == 'foreing_key') continue;
         $placeholders[] = "`$field` = :$field";
      }
      // echo json_encode($data);exit;

      if ($data['change_recurrence'] === true) {

         //Change only the month of the date;
         $params[':new_day'] = Utils::getDayOfTheDate($data['date']);
         $placeholders[] = "`date` = DATE_FORMAT(`date`, CONCAT('%Y-%m-', :new_day))";

         $params[':recurrence_id'] = $data['recurrence_id'];
         $placeholders = implode(',', $placeholders);

         $query =
            "UPDATE `entries`
                  SET $placeholders 
                  WHERE `recurrence_id` = :recurrence_id 
                  AND `foreing_key` = :foreing_key
                  AND `date` >= :date";
      } else {

         $params[':id'] = $data['id'];
         $placeholders[] = "`id` = :id";

         //Create the `date` column if it exists in the $data. The $params[':date'] is already done;
         if (array_key_exists('date', $data)) {
            $placeholders[] = "`date` = :date";
         }

         $placeholders = implode(',', $placeholders);
         $query =
            "UPDATE `entries` 
                  SET $placeholders 
                  WHERE `id` = :id  AND `foreing_key` = :foreing_key";
      }

      return ['query' => $query, 'params' => $params];
   }
}
?>