<?php 
   $token = $_COOKIE['JWTToken']  ?? null;
   
   if($token){
      $optons = [
         'http' => [
            'method' => 'POST',
            'header' => 
               "Content-Type: applicaton\r\n" .
               "Authorization: Bearer $token\r\n",
            'time' => 10
         ]
      ];
      $context = stream_context_create($optons);
      $validToken =  file_get_contents('http://localhost/organizze-bk/public/validatetoken.php', false, $context);
      if($validToken){
         header('Location: http://localhost/organizze-bk/teste.php');
         die();
      }
   }
?><!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Signin Page</title>
</head>
<body>
   <form id="formID">
      <input type="email" name="email" placeholder="E-mail" value="emerson@teste.com">
      <input type="password" name="password" placeholder="Password" value="12345567">
      <input type="submit" value="SignIn">
   </form>

   <script>
      const form = document.querySelector('#formID');
      form.addEventListener('submit', req);
      async function req(e){
         e.preventDefault();
         const formData = new FormData(form);
         const data = Object.fromEntries(formData.entries());
         
         const response = await fetch('http://localhost/organizze-bk/public/signin.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
         })

         const result = await response.json();

         if(result.success){
            window.location.href = `${result.redirect}`;
         }else{
            console.log('Invalid credentials')
         }
      }  

      </script>
</body>
</html>