

<?php 
   require_once __DIR__ . '/../Models/user.php';
   require_once __DIR__ . '/../Helpers/utils.php';
   require_once __DIR__ . '/../Auth/JWT/JWT.php';
   require_once __DIR__ . '/../Helpers/validators.php';

   class AuthController{
      private $userModel;

      public function __construct(){
         $this->userModel = new User();
      }

      public function authenticateUser(array $data){
         try{
            $email = $data['email'];
            $password =  $data['password'];
            $remember =  $data['remember'];
            
            Validators::validateString($email, 100, 10);
            Validators::validateString($password, 100, 6);
            Validators::validateBool($remember);
            
            $user = $this->userModel->getUserByEmail($email);
            
            $tokenExpiresTime = time() + 3600; //It expires in 1 hour;
            if($remember){
               $tokenExpiresTime = time() + (30 * 24 * 60 * 60);  //It expires in 30 days
            }
            
            if($user && password_verify($password, $user['password'])){
               $token = JWTHandler::generateToken($user['id'], $user['name'], $user['email'], $tokenExpiresTime);
               setcookie('JWTToken', $token, [
                  'expires' => $tokenExpiresTime,  
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

      public function getUserInfo(string $userId){
         
         try{
            Validators::validateString($userId, 255, 20);
            $result = $this->userModel->getUserInfo($userId);
            http_response_code(200);
            echo json_encode($result);
            exit;
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error.']);
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
               'redirect' => 'http://localhost:3000/signin'
            ]);
         }catch(Exception $e){
            http_response_code(500);
            echo json_encode(['message' => 'Internal server error.']);
         }
      }

      public function createUser(array $data){
         try{
            foreach ($data as $key => $value) {
               match(true){
                  $key === 'name' => Validators::validateString(trim($value), 255, 2),
                  default => Validators::validateString(trim($value), 255, 8),
               };
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $startDate = getDateWithTimezone('America/Sao_Paulo');
            // echo json_encode($data);exit;

            $this->userModel->createUser($data['id'], $data['name'], $data['email'], $data['lastname'], $startDate , $hashedPassword);
            http_response_code(200);
            echo json_encode(['message' => 'User created successfuly']);
            exit;
         }catch(Exception $e){
            http_response_code(401);
            echo json_encode(['message' => 'Iternal server error.']);
         };
      }

      public static function validateToken(string $token){
         if($token && JWTHandler::validateToken($token)){
            http_response_code(200);
            echo json_encode(['message' => 'Valid token', 'redirect' => 'http://localhost:3000/dashboard']);
            exit;
         }
         http_response_code(401);
         echo json_encode(['message' => 'Invalid token.']);
      }
}
?>