<?php 
   require_once __DIR__ . '../../../../vendor/autoload.php';
   require_once __DIR__ . '../../../../config/loadEnv.php';

   use Firebase\JWT\JWT;
   use Firebase\JWT\key;

   class JWTHandler{
      private static $secret_key;

      public static function generateToken($userId, $userName){
         self::$secret_key = getenv('JWT_SECRET_KEY');

         $payload = [
            'iss' => 'localhost',
            'iat' => time(),
            'exp' => time() + (60 * 60), //1 hour
            'userId' => $userId,
            'userName' => $userName,
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
   }

?>