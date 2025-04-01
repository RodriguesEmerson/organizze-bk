<?php 

   class Utils{
      public static function getDateWithTimezone($timezone){
         $date = new DateTime('now', new DateTimeZone($timezone));
         return $date->format('Y-m-d');
      }

      public static function formatToNumericNumber(string $value):float|bool{
         try{
            $valueWithoutPoint = str_replace('.', '', $value);
            return (float) str_replace(',', '.', $valueWithoutPoint);
         }catch(Exception $e){
            throw new InvalidArgumentException('Invalid number format.', 400);
         }
      }

      public static function formatDateToYmd(string $date):string|bool{
         try{
            $dateArray = explode('/', $date);
            return  $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0];
         }catch(Exception $e){
            throw new InvalidArgumentException("Invalid date format.", 400);
         }
      }
   }

   function getDateWithTimezone($timezone){
      $date = new DateTime('now', new DateTimeZone($timezone));
      return $date->format('Y-m-d');
   }
?>