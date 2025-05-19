<?php

use Ramsey\Uuid\Rfc4122\Validator;
use Ramsey\Uuid\Uuid; //The composer ramsey/uuid must be installed (composer require ramsey/uuid)

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

      public static function genereteUUID():string{
         $uuid = Uuid::uuid4()->toString();
         return $uuid; //Ex: strig 3f0a3b80-d87e-4a83-bb1c-f92711db45d4
      }

      public static function incrementOneMonth($date, $howMuch):string{
         $explodedDate = explode('-', $date);
         $year = (int) $explodedDate[0];
         $month = (int) $explodedDate[1] + $howMuch;
         $day = (int) $explodedDate[2];
         
         if($day > 28){
            for($d = $explodedDate[2] ; $d > 27; $d--){
               $testDate = sprintf('%04d-%02d-%02d', $year, $month, $d);
               if(self::isValidDate($testDate)){
                  return $testDate;
               }
            }
         }
         return (new DateTime($date))->modify("+$howMuch month")->format('Y-m-d');
      }

      public static function isValidDate($date):bool{
         $d = DateTime::createFromFormat('Y-m-d', $date);
         if($d && $d->format('Y-m-d') === $date){
            return true;
         };
         return false;
      }
   }

   function getDateWithTimezone($timezone){
      $date = new DateTime('now', new DateTimeZone($timezone));
      return $date->format('Y-m-d');
   }

?>