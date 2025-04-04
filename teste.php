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

   <div>
      <table  id="table">
         <thead>
            <tr>
               <th>Descrição</th>
               <th>Categoria</th>
               <th>Data</th>
               <th>Fixa</th>
               <th>Data final</th>
               <th>Valor</th>
            </tr>
         </thead>
      </table>
   </div>

   <form action="" id="editId" style="margin-top: 20px;">
      <input type="text" name="desc">
      <input type="text" name="categ">
      <input type="text" name="date">
      <input type="checkbox" name="fixed">
      <input type="text" name="endDate">
      <input type="text" name="value">
      <input type="submit" value="Update">
   </form>

   <div >
      <input type="button" value="Signout" style="margin-top: 10px; cursor: pointer;" id="signoutButton">
   </div>

   <script>
      const form = document.querySelector('#formId');
      const editForm = document.querySelector('#editId');
      const signoutButton = document.querySelector('#signoutButton');
      const TABLE = document.querySelector('#table');
      
      //Load entry data into Table ====================================================================================================
      //===============================================================================================================================
      (async function(){
         const request = await fetch('http://localhost/organizze-bk/public/entries.php', {
            method: 'GET'
         });
         const data = await request.json();
         // console.log(data)
         const tbody = document.createElement('tbody');
         TABLE.appendChild(tbody);
         data.forEach(entry =>{
            const tr = createElementDOM('tr', {id: entry.id});
            const tdDesc = createElementDOM('td', false, entry.description);
            const tdCateg = createElementDOM('td', false, entry.category);
            const tdDate = createElementDOM('td', false, new Date(entry.date).toLocaleDateString('pt-BR', {day:'2-digit', month:'2-digit', year:"numeric"}))
            const tdFixed = createElementDOM('td', false, entry.fixed == 1 ? 'Sim' : 'Não');
            const tdEndDate = createElementDOM('td', false,  new Date(entry.end_date).toLocaleDateString('pt-BR', {day:'2-digit', month:'2-digit', year:"numeric"}));
            const tdValue = createElementDOM('td', false, new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(entry.value));

            tr.appendChild(tdDesc);
            tr.appendChild(tdCateg);
            tr.appendChild(tdDate);
            tr.appendChild(tdFixed);
            tr.appendChild(tdEndDate);
            tr.appendChild(tdValue);

            tbody.appendChild(tr);

            tr.addEventListener('click', () =>{
               for(let ind = 0; ind <= tr.childNodes.length; ind++){
                  if(editForm.children[ind].getAttribute('type') == 'checkbox'){
                     if(tr.childNodes[ind].textContent == 'Sim')  {
                        editForm.children[ind].setAttribute('checked','')
                     }
                  }
                  if(editForm.children[ind].getAttribute('type') == 'text'){
                     editForm.children[ind].value = tr.childNodes[ind].textContent;
                  }
               }
            })
         })
      })();

      function createElementDOM(type, attributes, value){
         const element = document.createElement(type);
         if(attributes.length > 0){
            for(const att in attributes){
               element.setAttribute(att, attributes[att]);
            }
         }
         if(value){
            element.textContent = value;
         }
         return element;
      }

      //FORM SUBMIT EVENT ==================================================================================================================
      //====================================================================================================================================
      form.addEventListener('submit', (e) => {
         e.preventDefault();
         const formData = new FormData(form);
         const data = Object.fromEntries(formData.entries());
         data.id = gerarCUID();
         data.icon = 'images/icon.png'
         saveEntry(data);
      });

      async function saveEntry(data){
         const resquest = await fetch('http://localhost/organizze-bk/public/entries.php', {
            method: 'POST',
            headers: {'Content-Type': 'applicaton/json'},
            body: JSON.stringify(data)
         });
         
         const result = await resquest.json();
         console.log(result);
      }

      //EDIT SUBMIT EVENT ==================================================================================================================
      //====================================================================================================================================
      editForm.addEventListener('submit', (e) => {
         e.preventDefault();
         const formData = new FormData(form);
         const data = Object.fromEntries(formData.entries());
         data.id = gerarCUID();
         data.icon = 'images/icon.png'
         uptdateEntry(data);
      })

      async function uptdateEntry(data){
         const resquest = await fetch('http://localhost/organizze-bk/public/entries.php', {
            method: 'POST',
            headers: {'Content-Type': 'applicaton/json'},
            body: JSON.stringify(data)
         });
         
         const result = await resquest.json();
         console.log(result);
      }

      //SIGNOUT ============================================================================================================================
      //====================================================================================================================================
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