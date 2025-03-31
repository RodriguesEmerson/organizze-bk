<?php 
   require_once __DIR__ . '/../Models/entries.php';
   require_once __DIR__ . '/../Helpers/validators.php';

   class EntriesController{
      private $entriesModel;

      public function __construct(){
         $this->entriesModel = new EntriesModel();
      }

      public function insertEntry(string $id, string $foreing_key, string $description, string $category, string $date, bool $fixed,
      string $end_date, string $icon, float $value){
         
         $data = [
            'id' => $id,
            'foreing_key' => trim($foreing_key),
            'description' => trim($description),
            'category' => trim($category),
            'date' => trim($date),
            'fixed' => $fixed,
            'end_date' => trim($end_date),
            'icon' => trim($icon),
            'value' => $value,
         ];

         try{
            foreach($data AS $field => $value){
               if(in_array($field, ['value', 'fixed'])) continue;

               if(in_array($field, ['date', 'end_date'])){
                  Validators::validateDateYMD($value);
               }else{
                  Validators::validateString($value, 255, 0);
               }
            }
            Validators::validateFloat($data['value']);
            Validators::validateBool($data['fixed']);

         }catch(InvalidArgumentException $e){
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
            exit;
         }

         $this->entriesModel->insertEntry($data);
      }
   }
?>