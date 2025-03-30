<?php 
   function getDateWithTimezone($timezone){
      $date = new DateTime('now', new DateTimeZone($timezone));
      return $date->format('Y-m-d');
   }
?>