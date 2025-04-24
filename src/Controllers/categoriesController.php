<?php 

   require_once __DIR__ . '/../Models/categories.php';

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
   }
?>