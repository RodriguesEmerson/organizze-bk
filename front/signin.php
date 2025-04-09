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
         header('Location: http://localhost/organizze-bk/front/teste.php');
         die();
      }
   }
   
?><!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Signin Page</title>
   <link rel="stylesheet" href="./css/global.css">
   <link rel="stylesheet" href="./css/signig.css">
   <link rel="stylesheet" href="./css/header.css">
</head>
<body>
   <?php 
      require __DIR__ . '/header.inc.php';
   ?>
   <main>
      <div id="form-box">
         <h2>Login</h2>
         <form id="formID">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" placeholder="E-mail" value="emerson@teste.com">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password" value="12345567">
            <input type="submit" value="SignIn">
         </form>

         <p id="wornning">Invalid credentials, please try again.</p>

      </div>
   </main>

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
         .then(response => {{
            return response.json();
         }})
         .then(response => {
            if(response.success){
               window.location = response.redirect;
            }
         })
         .catch(erro => {
            const wornning = document.querySelector('#wornning');
            wornning.style.display = 'block';
         })
      }  

      </script>
</body>
</html>