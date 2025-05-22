<?php
require_once __DIR__ . '/../../../Helpers/utils.php';

// Classe responsável por construir a query de update
class BuildEntryUpdateQuery{
   /**
     * @param array $data Dados para update
     * @param string $userId ID do usuário
     * @return array ['query' => string, 'params' => array]
     */

   public static function query(array $data, string $userId):array{
      $params = [];
      $placeholders = [];
      $data['foreing_key'] = $userId;
      $query = '';

      foreach ($data as $field => $value) {
         if(in_array($field, ['id', 'change_recurrence', 'recurrence_id'], true)){continue;}
         $params[":$field"] = $value;

         if(in_array($field, ['date', 'foreing_key'], true)){continue;}
         $placeholders[] = "`$field` = :$field";
      }

      if ($data['change_recurrence'] === true) {
         //Change only the day of the date;
         $params[':new_day'] = Utils::getDayOfTheDate($data['date']);
         $placeholders[] = "`date` = DATE_FORMAT(`date`, CONCAT('%Y-%m-', :new_day))";

         $params[':recurrence_id'] = $data['recurrence_id'];
         $placeholders = implode(',', $placeholders);

         $query =
            "UPDATE `entries` SET $placeholders 
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