<?php 
   require_once __DIR__ . '../../../../vendor/autoload.php';
   require_once __DIR__ . '../../../../config/loadEnv.php';

   use Firebase\JWT\JWT;
   use Firebase\JWT\key;

   class JWTHandler{
      private static $secret_key;

      public static function generateToken($userId, $userName, $userEmail, $tokenExpiresTime){
         self::$secret_key = getenv('JWT_SECRET_KEY');

         $payload = [
            'iss' => 'localhost',
            'iat' => time(),
            'exp' => $tokenExpiresTime, 
            'userId' => $userId,
            'userName' => $userName,
            'useEmail' => $userEmail
         ];
         return JWT::encode($payload, self::$secret_key, 'HS256');
      }

      public static function validateToken($token){
         try{
            self::$secret_key = getenv('JWT_SECRET_KEY');
            return JWT::decode($token, new Key(self::$secret_key, 'HS256'));
         }catch(Exception $e){
            return false;
         }
      }

      public static function getUserId(string $token):string|bool{
         if($token){
            try{
               $decoded = JWT::decode($token, new key(self::$secret_key, 'HS256'));
               return $decoded->userId; //Authenticated user id;
            }catch(Exception $e){
               return false;
            }
         }
         return false;
      }

      public static function getUserInfo(string $token):array|bool{
         if($token){
            try{
               $decoded = JWT::decode($token, new key(self::$secret_key, 'HS256'));
               return ['email' => $decoded->userEmail, 'name' => $decoded->userName];
            }catch(Exception $e){
               return false;
            }
         }
         return false;
      }
   }

?>