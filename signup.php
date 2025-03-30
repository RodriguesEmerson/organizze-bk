<!DOCTYPE html>
<html lang="pt">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
</head>
<body>
   <form id="formID" >
      <input type="text" name="name" placeholder="Name" value="Emerson">
      <input type="text" name="lastname" placeholder="Lastname" value="Teste">
      <input type="email" name="email" placeholder="E-mail" value="emerson@teste.com">
      <input type="password" name="password" placeholder="Password" value="12345567">
      <input type="submit" value="SignUp">
   </form>

   <script>
      const form = document.querySelector('#formID');
      form.addEventListener('submit', req);
      async function req(e){
         e.preventDefault();
         const formData = new FormData(form);
         const data = Object.fromEntries(formData.entries());
         data.id = gerarCUID();
         
         const response = await fetch('http://localhost/organizze-bk/public/signup.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
         })

         const result = await response.json();
         console.log(result);
      }
      
      function gerarCUID(){
         const timestamp = Date.now().toString(36); // Base36 para reduzir o tamanho
         const randomPart = Math.random().toString(36).substring(2, 10); // Parte aleatória
         const uniquePart = performance.now().toString(36).replace('.', ''); // Para evitar colisões
         return `c${timestamp}${randomPart}${uniquePart}`;

         return;
      }
      
      </script>
</body>
</html>