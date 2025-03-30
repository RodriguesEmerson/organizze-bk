<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
</head>
<body>
   <form id="formID" >
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
         
         const response = await fetch('http://localhost/mymoney-bk/public/signin.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
         })

         const result = await response.json();
         console.log(result);
      }  

      </script>
</body>
</html>