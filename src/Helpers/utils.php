<?php
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

      public static function incrementMonths($date, $monthsToAdd):string{
         $explodedDate = explode('-', $date);
         $year = (int) $explodedDate[0];
         $month = (int) $explodedDate[1] + $monthsToAdd;
         $day = (int) $explodedDate[2];
         
         if($day > 28){
            for($d = $explodedDate[2] ; $d > 27; $d--){
               //Correctly formats the date in the YYYY-MM-DD pattern, adding leading zeros when necessary.
               $formattedDate = sprintf('%04d-%02d-%02d', $year, $month, $d);
               if(self::isValidDate($formattedDate)){
                  return $formattedDate;
               }
            }
         }
         return (new DateTime($date))->modify("+$monthsToAdd month")->format('Y-m-d');
      }

      public static function isValidDate($date):bool{
         $d = DateTime::createFromFormat('Y-m-d', $date);
         if($d && $d->format('Y-m-d') === $date){
            return true;
         };
         return false;
      }

      public static function getDayOfTheDate($date):string{
         $explodedDate = explode('-', $date);
         $day =  $explodedDate[2];
         return $day;
      }
   }

   function getDateWithTimezone($timezone){
      $date = new DateTime('now', new DateTimeZone($timezone));
      return $date->format('Y-m-d');
   }

?>