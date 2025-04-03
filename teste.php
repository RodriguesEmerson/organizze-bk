<?php 
   $token = $_COOKIE['JWTToken']  ?? null;

   if($token){
      $options = [
         'http' => [
            'method' => 'POST',
            'header' => 
            "Content-Type: application/json\r\n" . 
            "Authorization: Bearer $token\r\n",
            'timeout' => 10,
            ]
      ];
      $context = stream_context_create($options);
      $validToken =  file_get_contents('http://localhost/organizze-bk/public/validatetoken.php', false, $context);
      if(!$validToken){
         header('Location: http://localhost/organizze-bk/signin.php');
         die();
      }
   }else{
      header('Location: http://localhost/organizze-bk/signin.php');
      die();
   }

?><!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
</head>
<body>
   <form action="" id="formId" style="margin-bottom: 20px;">
      <input type="text" name="desc" value="Teste-1">
      <input type="text" name="categ" value="Category-1">
      <input type="text" name="date" value="30/03/2025">
      <input type="checkbox" name="fixed" checked>
      <input type="text" name="endDate" value="30/04/2025">
      <input type="text" name="value" value="100,50">
      <input type="submit" value="Save">
   </form>

   <div id="table">
      
   </div>

   <form action="" id="editId" style="margin-top: 20px;">
      <input type="text" name="desc" value="Teste-1">
      <input type="text" name="categ" value="Category-1">
      <input type="text" name="date" value="30/03/2025">
      <input type="checkbox" name="fixed" checked>
      <input type="text" name="endDate" value="30/04/2025">
      <input type="text" name="value" value="100,50">
      <input type="submit" value="Save">
   </form>

   <div >
      <input type="button" value="Signout" style="margin-top: 10px; cursor: pointer;" id="signoutButton">
   </div>

   <script>
      const form = document.querySelector('#formId');
      const editForm = document.querySelector('#editId');
      const signoutButton = document.querySelector('#signoutButton');

      (async function(){
         const request = await fetch('http://localhost/organizze-bk/public/entries.php', {
            method: 'GET'
         });
         const data = await request.json();
         console.log(data);
      })();

      form.addEventListener('submit', (e) => {
         e.preventDefault();
         const formData = new FormData(form);
         const data = Object.fromEntries(formData.entries());
         data.id = gerarCUID();
         data.icon = 'images/icon.png'
         saveEntry(data);
      });

      editForm.addEventListener('submit', (e) => {
         e.preventDefault();
         const formData = new FormData(form);
         const data = Object.fromEntries(formData.entries());
         data.id = gerarCUID();
         data.icon = 'images/icon.png'
         saveEntry(data);
      })

      async function saveEntry(data){
         const resquest = await fetch('http://localhost/organizze-bk/public/entries.php', {
            method: 'POST',
            headers: {'Content-Type': 'applicaton/json'},
            body: JSON.stringify(data)
         });
         
         const result = await resquest.json();
         console.log(result);
      }

      signoutButton.addEventListener('click', async () => {
         const request = await fetch('http://localhost/organizze-bk/public/signout.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'}
         })
         const result = await request.json();
         if(result.success){
            window.location.href = `${result.redirect}`;
         }
      })

      


      function gerarCUID() {
         const timestamp = Date.now().toString(36); // Base36 para reduzir o tamanho
         const randomPart = Math.random().toString(36).substring(2, 10); // Parte aleatória
         const uniquePart = performance.now().toString(36).replace('.', ''); // Para evitar colisões
         return `c${timestamp}${randomPart}${uniquePart}`;

         return;
      }
   </script>

</body>
</html>