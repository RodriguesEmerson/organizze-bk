<?php 

   require_once __DIR__ . '/../Models/categories.php';
   require_once __DIR__ . '/../Helpers/validators.php';

   class CategoriesController{
      private $categoriesModel;

      public function __construct(){
         $this->categoriesModel = new CategoriesModel();
      }

      public function getCategories($userId, $type):void{
         try{
            $categories = $this->categoriesModel->getCategoreis($userId, $type);
            http_response_code(200);
            echo json_encode($categories);
            
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error']);
         }
      }

      public function insertCategory(array $data, string $userId){
         try{
            foreach ($data AS $field => $value) {
              Validators::validateString($value, 255, 1);
            }

            $result = $this->categoriesModel->insertCategory($data, $userId);
            http_response_code(201);
            echo json_encode($result);

         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error']);
         }
      }

      public function updateCategory(array $data){
         try{
            foreach ($data AS $field => $value) {
              Validators::validateString($value, 255, 1);
            }

            $result = $this->categoriesModel->updateCategory($data);
            http_response_code(200);
            echo json_encode(['message' => 'Categoty updated.']);

         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error']);
         }
      }

      public function deleteCategory(array $data){
         try{
            $id = $data['id'];
            Validators::validateString($id, 255, 20);
            $result = $this->categoriesModel->deleteCategory($id);
            http_response_code(200);
            echo json_encode(['message' => 'Categoty deleted.']);
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error']);
         }
      }
   }
?>