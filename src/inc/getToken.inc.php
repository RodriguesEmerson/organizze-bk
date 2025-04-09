<?php
   $headers = apache_request_headers();
   $headerToken = $headers['Authorization'] ?? null;
   
   if(empty($headerToken)) $headerToken = null;
   $cookeToken = $_COOKIE['JWTToken'] ?? null;
   $token = $cookeToken ?? $headerToken ?? null;
   if($token){
      $token = str_replace('Bearer ', '', $token);
   }
?>