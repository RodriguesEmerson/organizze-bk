<?php 
   require_once __DIR__ . '/../Models/entries/EntriesModel.php';
   require_once __DIR__ . '/../Helpers/validators.php';
   require_once __DIR__ . '/../Helpers/utils.php';

   class EntriesController{
      private $entriesModel;

      public function __construct(){
         $this->entriesModel = new EntriesModel();
      }

      public function getEntries($userId, $year, $month):void{
         try{
            $entrieResult = $this->entriesModel->getEntries($userId, $year, $month);
            $entiresSum = $this->entriesModel->getEntriesSum($userId, $year, $month);
            http_response_code(200);
            echo json_encode(['entries' => $entrieResult, 'sum' => $entiresSum]);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error.']);
            exit;
         }
      }

      public function getYearlyData(string $year, string $foreing_key){
         try{
            Validators::validateString($year, 4, 4);
            $result = $this->entriesModel->getYearlyData($year, $foreing_key);
            http_response_code(200);
            echo json_encode($result);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error.']);
            exit;
         }
      }

      public function getAvailablesTalbes(string $foreing_key){

         try{
            $result = $this->entriesModel->getAvailablesTalbes($foreing_key);
            http_response_code(200);
            echo json_encode($result);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error.']);
            exit;
         }
      }

      public function insertEntry(array $data, string $foreing_key):void{
         // echo json_encode($data['value']);exit;

         foreach($data AS $field => $value){
            match ($field) {
                'fixed'=> $data['fixed'] = $value,
                'value'=>  $data['value'] = $value,
                'effected'=>  $data['effected'] = $value,
                default => $data[$field] = trim($value),
            };
         };
         $data['last_edition'] = Utils::getDateWithTimezone('America/Sao_Paulo');
         $data['foreing_key'] = $foreing_key;

         //Validating data
         try{
            foreach($data AS $field => $value){
               if(in_array($field, ['value', 'fixed', 'effected', 'date', 'end_date', 'last_edition'])) continue;
               Validators::validateString($value, 255, 1);
            }
            
            Validators::validateBool($data['fixed']);
            Validators::validateBool($data['effected']);
            Validators::validateDateYMD($data['date']);
            
            if($data['fixed'] === true){
               Validators::validateDateYMD($data['end_date']);
            }
            
            Validators::validateNumeric($data['value']);

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
                  $data[$field] === null => '',
                  $field === 'effected' => $data[$field] = $value,
                  $field === 'change_recurrence' => $data[$field] = $value,
                  default => $data[$field] = trim($value),
               };
            }
            
            
            if(array_key_exists('fixed', $data)){
               if($data['fixed'] == '1') {
                  $data['fixed'] = true;
               }else{
                  $data['fixed'] = false; 
               }
               if($data['fixed'] && empty($data['end_date'])){
                  throw new InvalidArgumentException('Invalid Date format.', 400);
               }
            }
            
            foreach($data AS $field => $value){
               match(true){
                  in_array($field, ['date', 'end_date', 'last_edition']) => Validators::validateDateYMD($value),
                  $field === 'fixed' => Validators::validateBool($data['fixed']),
                  $field === 'effected' => Validators::validateBool($data['effected']),
                  $field === 'change_recurrence' => Validators::validateBool($data['change_recurrence']),
                  $field === 'value' => Validators::validateNumeric($value),
                  default => Validators::validateString($value, 255, 1),
               };
            }  

            
         }catch(InvalidArgumentException $e){
            http_response_code($e->getCode());
            echo json_encode($e->getMessage());
            exit;
         }

         try{
            $this->entriesModel->updateEntry($data, $userId);
            http_response_code(200);
            echo json_encode(['message' => 'Entry updated successfuly', 'code' => '200']);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error']);
            exit;
         }
      }

      public function deleteEntry(array $data, string $userId){
         try{
            $entryId = $data['id'];
            Validators::validateString($entryId, 255, 1);

            $this->entriesModel->deleteEntry($entryId, $userId);
            http_response_code(200);
            echo json_encode(['message' => 'Entry deleted successfuly']);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error']);
            exit;
         }
      }
   }
?>