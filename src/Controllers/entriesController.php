<?php 
   require_once __DIR__ . '/../Models/entries.php';
   require_once __DIR__ . '/../Helpers/validators.php';
   require_once __DIR__ . '/../Helpers/utils.php';

   class EntriesController{
      private $entriesModel;

      public function __construct(){
         $this->entriesModel = new EntriesModel();
      }

      public function getEntries($userId):void{
         
         try{
            $result = $this->entriesModel->getEntries($userId);
            http_response_code(200);
            echo json_encode($result);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error.']);
            exit;
         }
      }

      public function insertEntry(string $id, string $foreing_key, string $description, string $category, string $type, string $date, bool $fixed,
      string|null $end_date, string $icon, string $value):void{

         // json_encode($end_date);exit;

         $data = [
            'id' => $id,
            'foreing_key' => trim($foreing_key),
            'description' => trim($description),
            'category' => trim($category),
            'type' => trim($type),
            'date' => trim($date),
            'fixed' => $fixed,
            'end_date' => trim($end_date),
            'last_edition' => Utils::getDateWithTimezone('America/Sao_Paulo'),
            'icon' => trim($icon),
            'value' => $value,
         ];


         //Validating data
         try{
            foreach($data AS $field => $value){
               if(in_array($field, ['value', 'fixed', 'date', 'end_date', 'last_edition'])) continue;
               Validators::validateString($value, 255, 1);
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

         try{
            $this->entriesModel->insertEntry($data);
            http_response_code(201);
            echo json_encode(['message' => 'New entry successfuly saved']);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error']);
            exit;
         }
      }

      public function updateEntry(array $data, string $userId):void{
         $data['foreing_key'] = $userId;
         $data['last_edition'] = Utils::getDateWithTimezone('America/Sao_Paulo');
         try{
            foreach($data AS $field => $value){
               match(true){
                  $field === 'value' => $data[$field] = (float) $data[$field],
                  default => $data[$field] = trim($value),
               };
            }

            foreach($data AS $field => $value){
               match(true){
                  in_array($field, ['date', 'end_date', 'last_edition']) => Validators::validateDateYMD($value),
                  $field === 'fixed' => Validators::validateBool($data['fixed']),
                  $field === 'value' => Validators::validateFloat($value),
                  default => Validators::validateString($value, 255, 1),
               };
            }
         }catch(InvalidArgumentException $e){
            http_response_code($e->getCode());
            echo json_encode($e->getMessage());
            exit;
         }

         try{
            $this->entriesModel->updateEntry($data);
            http_response_code(200);
            echo json_encode(['message' => 'Entry updated successfuly', 'code' => '200']);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error']);
            exit;
         }
      }
   }
?>