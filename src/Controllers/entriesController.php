<?php 
   require_once __DIR__ . '/../Models/entries.php';
   require_once __DIR__ . '/../Helpers/validators.php';
   require_once __DIR__ . '/../Helpers/utils.php';

   class EntriesController{
      private $entriesModel;

      public function __construct(){
         $this->entriesModel = new EntriesModel();
      }

      public function insertEntry(string $id, string $foreing_key, string $description, string $category, string $date, bool $fixed,
      string $end_date, string $icon, string $value){
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
               if(in_array($field, ['value', 'fixed', 'date', 'end_date'])) continue;
               Validators::validateString($value, 255, 0);
            }
            
            Validators::validateBool($data['fixed']);
            
            $data['date'] = Utils::formatDateToYmd($data['date']);
            Validators::validateDateYMD($data['date']);
            
            
            if($data['fixed']){
               $data['end_date'] = Utils::formatDateToYmd($data['end_date']);
               Validators::validateDateYMD($data['end_date']);
            }
            
            $data['value'] = Utils::formatToNumericNumber($data['value']);
            Validators::validateFloat($data['value']);
            
            
         }catch(InvalidArgumentException $e){
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
            exit;
         }
         // echo json_encode($data);exit;
         try{
            $this->entriesModel->insertEntry($data);
            http_response_code(201);
            echo json_encode(['message' => 'New entry successfuly saved']);

         }catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
            exit;
         }
         
      }
   }
?>