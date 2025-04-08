<?php
$token = $_COOKIE['JWTToken']  ?? null;

if ($token) {
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
   if (!$validToken) {
      header('Location: http://localhost/organizze-bk/front/front/signin.php');
      die();
   }
} else {
   header('Location: http://localhost/organizze-bk/front/signin.php');
   die();
}

?>
<!DOCTYPE html>
<html lang="pt">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=trending_up" />
   <link rel="stylesheet" href="./css/global.css">
   <link rel="stylesheet" href="./css/header.css">
   <link rel="stylesheet" href="./css/teste.css">
</head>

<body>
   <?php
      require __DIR__ . '/header.inc.php';
   ?>
   <main>
      <aside>

      </aside>
      <section>
         <div id="black-background-form" class="hidden">
            <div class="form-box">
               <h3>Add new Entry</h3>
               <div class="close-button">
                  <span>x</span>
               </div>
               <form action="" id="formId" style="margin-bottom: 20px;">

                  <div class="row">
                     <div class="field">
                        <label for="selectId">Type</label>
                        <select name="type" id="selectId">
                           <option value="income" >income</option>
                           <option value="expense">expese</option>
                        </select>
                     </div>
                     <div class="field">
                        <label for="categId">Category</label>
                        <input type="text" name="category" value="Category-1" id='categId'>
                     </div>
                  </div>

                  <div class="row">
                     <div class="field">
                        <label for="descId">Description</label>
                        <input type="text" name="description" value="Teste-1" id="descId">
                     </div>
                  </div>
                  
                  <div class="row">
                     <div class="field">
                        <label for="dateId">Date</label>
                        <input type="text" name="date" value="30/03/2025" id="dateId">
                     </div>
                     <div class="field">
                        <label for="fixedId">Fixed</label>
                        <input type="checkbox" name="fixed" checked id="fixedId">
                     </div>
                     <div class="field">
                        <label for="endDateId">End date</label>
                        <input type="text" name="end_date" value="30/04/2025" id="endDateId">
                     </div>
                  </div>

                  <div class="row">
                     <div class="field">
                        <label id="valueId">Amount</label>
                        <input type="text" name="value" value="100,50" id="valueId">
                     </div>
                  </div>

                  <input type="submit" value="Save">
               </form>
            </div>
         </div>

         <div class="table-box">
            <table id="table">
               <thead>
                  <tr>  
                     <th scope="col">Type</th>
                     <th scope="col">Description</th>
                     <th scope="col">Category</th>
                     <th scope="col">Date</th>
                     <th scope="col">Fixed</th>
                     <th scope="col">End date</th>
                     <th scope="col">Amount</th>
                  </tr>
               </thead>
            </table>
         </div>

         <div id="black-background-formEdit" class="hidden">
            <div class="form-box">
               <h3>Editing Entry</h3>
               <div class="close-button">
                  <span>x</span>
               </div>
               <form action="" id="editId" style="margin-bottom: 20px;">
                  <div class="row">
                     <div class="field">
                        <label for="selectIdEdit">Type</label>
                        <select name="type" id="selectIdEdit">
                           <option value="selecione">*Selecione*</option>
                           <option value="income" >income</option>
                           <option value="expense">expese</option>
                        </select>
                     </div>
                     <div class="field">
                        <label for="categIdEdit">Category</label>
                        <input type="text" name="category" id='categIdEdit'>
                     </div>
                  </div>

                  <div class="row">
                     <div class="field">
                        <label for="descIdEdit">Description</label>
                        <input type="text" name="description" id="descIdEdit">
                     </div>
                  </div>
                  
                  <div class="row">
                     <div class="field">
                        <label for="dateIdEdit">Date</label>
                        <input type="text" name="date" id="dateIdEdit">
                     </div>
                     <div class="field">
                        <label for="fixedIdEdit">Fixed</label>
                        <input type="checkbox" name="fixed" id="fixedIdEdit">
                     </div>
                     <div class="field">
                        <label for="endDateIdEdit">End date</label>
                        <input type="text" name="end_date" id="endDateIdEdit">
                     </div>
                  </div>

                  <div class="row">
                     <div class="field">
                        <label for="valueIdEdit">Amount</label>
                        <input type="text" name="value" id="valueIdEdit">
                     </div>
                  </div>

                  <input type="submit" value="Save">
               </form>
            </div>
         </div>

         <!-- <form action="" id="editId" style="margin-top: 20px;">
            <select name="type">
               <option value="selecione">*Selecione*</option>
               <option value="income">income</option>
               <option value="expense">expese</option>
            </select>
            <input type="text" name="description">
            <input type="text" name="category">
            <input type="text" name="date">
            <input type="checkbox" name="fixed">
            <input type="text" name="end_date">
            <input type="text" name="value">
            <input type="submit" value="Update">
         </form> -->

         <div>
            <input type="button" value="Signout" style="margin-top: 10px; cursor: pointer;" id="signoutButton">
         </div>
      </section>
   </main>


   <script>
      const form = document.querySelector('#formId');
      const editForm = document.querySelector('#editId');
      const signoutButton = document.querySelector('#signoutButton');
      const TABLE = document.querySelector('#table');
      const buttonCloseForm = document.querySelector('.close-button');
      const backForm = document.querySelector('#black-background-form');
      const backFormEdit = document.querySelector('#black-background-formEdit');
      let entryEditing = false;

      buttonCloseForm.addEventListener('click', () => {
         backForm.classList.add('hidden');
      });

      //Load entry data into Table ====================================================================================================
      //===============================================================================================================================
      (async function() {
         const request = await fetch('http://localhost/organizze-bk/public/entries.php', {
            method: 'GET'
         });
         const data = await request.json();
         // console.log(data)
         const tbody = document.createElement('tbody');
         TABLE.appendChild(tbody);
         data.forEach(entry => {
            const tr = createElementDOM('tr', {id: entry.id, class: entry.type});
            tdType = createElementDOM('td', {class: 'material-symbols-outlined'}, entry.type == 'income' ? 'trending_up': 'trending_down')
            const tdDesc = createElementDOM('td', false, entry.description);
            const tdCateg = createElementDOM('td', false, entry.category);
            const tdDate = createElementDOM('td', false, formatToDMYDate(entry.date))
            const tdFixed = createElementDOM('td', false, entry.fixed == 1 ? 'Sim' : 'Não');
            const tdEndDate = createElementDOM('td', false, entry.end_date ? formatToDMYDate(entry.end_date) : '');
            const tdValue = createElementDOM('td', false, new Intl.NumberFormat('pt-BR', {
               style: 'currency',
               currency: 'BRL'
            }).format(entry.value));


            tr.appendChild(tdType)
            tr.appendChild(tdDesc);
            tr.appendChild(tdCateg);
            tr.appendChild(tdDate);
            tr.appendChild(tdFixed);
            tr.appendChild(tdEndDate);
            tr.appendChild(tdValue);

            tbody.appendChild(tr);

            tr.addEventListener('click', () => {
               entryEditing = entry;
               backFormEdit.classList.remove('hidden')

               document.querySelector('#selectIdEdit').value = entry.type;
               document.querySelector('#categIdEdit').value = entry.category;
               document.querySelector('#descIdEdit').value = entry.description;
               document.querySelector('#dateIdEdit').value = formatToDMYDate(entry.date);
               if(entry.fixed){
                  document.querySelector('#fixedIdEdit').setAttribute('checked', '')
               }else{
                  document.querySelector('#fixedIdEdit').removeAttribute('checked')
               }
               document.querySelector('#endDateIdEdit').value = formatToDMYDate(entry.end_date);
               document.querySelector('#valueIdEdit').value =  new Intl.NumberFormat('pt-BR', {
                  style: 'currency',
                  currency: 'BRL'
               }).format(entry.value);
              

               console.log(document.querySelector('#valueIdEdit'))
            })
         })
      })();

      function formatToDMYDate(date) {
         dateSplited = date.split('-');
         return `${dateSplited[2]}/${dateSplited[1]}/${dateSplited[0]}`
      }

      function createElementDOM(type, attributes, value) {
         const element = document.createElement(type);
         if (Object.entries(attributes).length > 0) {
            for (const att in attributes) {
               element.setAttribute(`${att}`, `${attributes[att]}`);
            }
         }
         if (value) {
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

      async function saveEntry(data) {
         const resquest = await fetch('http://localhost/organizze-bk/public/entries.php', {
            method: 'POST',
            headers: {
               'Content-Type': 'applicaton/json'
            },
            body: JSON.stringify(data)
         });

         const result = await resquest.json();
         console.log(result);
      }

      //EDIT SUBMIT EVENT ==================================================================================================================
      //====================================================================================================================================
      editForm.addEventListener('submit', (e) => {
         e.preventDefault();
         const formData = new FormData(editForm);
         const data = Object.fromEntries(formData.entries());

         //Criando função para pegar apenas dados alterados
         const alteredData = {};
         if (entryEditing && data) {
            data.date = formatToYdmDate(data.date);
            data.fixed == 'on' ? data.fixed = 1 : data.fixed = 0;
            data.value = data.value.replace('.', '');
            data.value = data.value.replace(',', '.');
            data.value = data.value.slice(3);
            if (data.fixed == 1) {
               data.end_date = formatToYdmDate(data.end_date);
            }

            for (let key in data) {
               if (data[key] != entryEditing[key]) {
                  alteredData[key] = data[key];
               }
            }
         }

         if (Object.entries(alteredData).length > 0) {
            alteredData.id = entryEditing.id;
            uptdateEntry(alteredData);
         }
      })

      function formatToYdmDate(date) {
         dateSplited = date.split('/');
         return `${dateSplited[2]}-${dateSplited[1]}-${dateSplited[0]}`
      }

      async function uptdateEntry(data) {
         const resquest = await fetch('http://localhost/organizze-bk/public/entries.php', {
            method: 'UPDATE',
            headers: {
               'Content-Type': 'applicaton/json'
            },
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
            headers: {
               'Content-Type': 'application/json'
            }
         })
         const result = await request.json();
         if (result.success) {
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