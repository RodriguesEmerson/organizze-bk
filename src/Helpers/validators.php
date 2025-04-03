<?php 
   class Validators{
      public static function validateString($string, int $maxLen, int $minLen):bool{
         if((!is_string($string)) || $string === null){
            throw new InvalidArgumentException("Invalid string format", 400);
         }

         $string = trim($string);
         if(strlen($string) < $minLen || strlen($string) > $maxLen){
            throw new InvalidArgumentException("The string must have between $minLen and $maxLen chars.", 400);
         }
         return true;
      }

      public static function validateFloat($number):bool{
         if(!is_float($number) || $number === null){
            throw new InvalidArgumentException('Invalid number format', 400);
         }
         return true;
      }

      public static function validateDateYMD(string $date):bool{
         $dateFormat = DateTime::createFromFormat('Y-m-d', $date);
         $result = $dateFormat && $dateFormat->format('Y-m-d') === $date;
         if(!$result){
            throw new InvalidArgumentException('Invalid Date format.', 400);
         };
         return true;
      }

      public static function validateBool($bool):bool{
         if(!is_bool($bool) || $bool == null){
            throw new InvalidArgumentException('Invalid boolean value. 400');
         }
         return true;
      }
   }

   

?>