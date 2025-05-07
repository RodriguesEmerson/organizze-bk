<?php

   require_once __DIR__ . '/../Models/user.php';
   require_once __DIR__ . '/../Helpers/utils.php';
   require_once __DIR__ . '/JWT/JWT.php';

   class AuthHandler{
      private $userModel;

      public function __construct(){
         $this->userModel = new User();
      }

      public function authenticateUser(string $email, string $password){
         try{
            $user = $this->userModel->getUserByEmail($email);
            
            if($user && password_verify($password, $user['password'])){
               $token = JWTHandler::generateToken($user['id'], $user['name']);
               setcookie('JWTToken', $token, [
                  'expires' => time() + 3600,  //It expires in 1 hour
                  'path' => '/',               //Avalaible for the entire site
                  'httponly' => true,          //It protects against accesses by javascript
                  'secure' => true,            //Only HTTPS
                  'samesite' => 'Strict'       //Avoid other sites accsses
               ]);

               http_response_code(200);
               echo json_encode([
                  'success' => true,
                  'message' => 'Successful login',
                  'redirect' => 'http://localhost:3000/dashboard'
               ]);
               exit;
            }

         }catch(Exception $e){
            http_response_code(400);
            echo json_encode([
               'success' => false,
               'message' => 'Invalid credentials'
            ]);
         }
      }

      public static function signout(){
         try{
            setcookie('JWTToken', '', [
               'expires' => time() - (30 * 24 * 60 * 60),  //Delete the cookie //30 days
               'path' => '/',
               'httponly' => true,
               'secure' => true,
               'samesite' => 'Strict'
            ]);

            http_response_code(200);
            echo json_encode([
               'success' => true,
               'message' => 'User signed out succssfuly',
               'redirect' => 'http://localhost:3000/dashboard'
            ]);
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error.']);
         }
         
      }

      public function createUser(string $id, string $name, string $lastname,  string $email, string $password){
         if(!empty($id) && !empty($name) && !empty($lastname) && !empty($email) && !empty($password)){
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $startDate = getDateWithTimezone('America/Sao_Paulo');
            // echo json_encode(['m' => $startDate]);exit;
            try{
               $this->userModel->createUser($id, $name, $email, $lastname, $startDate , $hashedPassword);
               http_response_code(200);
               echo json_encode(['message' => 'User created successfuly']);
               exit;
            }catch(Exception $e){
               http_response_code($e->getCode());
               echo json_encode(['message' => $e->getMessage()]);
               exit;
            }
         };
         http_response_code(400);
         echo json_encode(['message' => 'All fields are required']);
         exit;
      }
   }
?>