/**
 * Arquivo para definir os eventos dos botoes de submit
 */


let button = initButons();

//Valida se componentes existem na tela
//Se não existirem, as chamadas dos metodos não ocorrerão
if(button.submitDoacao != undefined){

  //Ação do botão que envia os itens para adoção
  button.submitDoacao.addEventListener('click', (e)=>{
    e.preventDefault();

    let inputs = initVarsDoacao();

    const fetch = new Fetch("http://localhost:8000");

    var formData = new FormData();
     // Adiciona os campos de texto ao FormData
      for (const key in inputs) {
        if(inputs[key] == null){
          delete inputs[key];
        }
        formData.append(key, inputs[key]);
      }

      fetch.post('/nova/doacao', formData)
      .then( (data) => {
        if(data != undefined){
          var successModal = document.getElementById('successModal');
          var instance = M.Modal.init(successModal);
            instance.open();
            setTimeout(()=>{
              location.reload();
            },5000)
        }
      }).catch((error) => console.log(error))
  });

if(button.submitLocal != undefined){
    button.submitLocal.addEventListener('click', (e)=>{
      e.preventDefault();

      let inputs = initVarsLocalDoacao();

      var formData = new FormData();
     // Adiciona os campos de texto ao FormData
      for (const key in inputs) {
        if(inputs[key] == null){
          delete inputs[key];
        }
        formData.append(key, inputs[key]);
      }

      const fetch = new Fetch("http://localhost:8000");

      fetch.post('/novo/local-doacao',  formData)
      .then( (data) => {
        var successModal = document.getElementById('successModal');
        var instance = M.Modal.init(successModal);
          instance.open();
          setTimeout(()=>{
            location.reload();
          },5000)
      })
      .catch((error) => console.log(error))
    });

  }

}

if(button.submitLocalAbrigo != undefined){
  //Ação do botão que cadastra os locais de abrigo
  button.submitLocalAbrigo.addEventListener('click', (e)=>{
    e.preventDefault();

    let inputs = initVarsLocalAbrigo();

    var formData = new FormData();
     // Adiciona os campos de texto ao FormData
    for (const key in inputs) {
      if(inputs[key] == null){
        delete inputs[key];
      }
      formData.append(key, inputs[key]);
    }

    const fetch = new Fetch("http://localhost:8000");


    fetch.post('/novo/abrigo', formData )
      .then( (data) => {
        var successModal = document.getElementById('successModal');
        var instance = M.Modal.init(successModal);
          instance.open();
          setTimeout(()=>{
            location.reload();
          },5000)
      })
      .catch((error) => console.log(error))
  });

}

if(button.submitUserLogin != undefined){
  //Ação do botão que realiza login
  button.submitUserLogin.addEventListener('click', function(e){
    e.preventDefault();

    let inputs = initVarsUserLogin();

    var modalAuth = document.getElementById('modal-auth');
    var preloader = document.getElementById('preloader-auth');
    var instance = M.Modal.init(modalAuth, {
        dismissible: false, // Impede que o modal seja fechado clicando fora dele
        opacity: 0.5, // Define a opacidade do modal
        startingTop: '10%', // Define a posição inicial do modal
        endingTop: '20%' // Define a posição final do modal
    });


    let fetch = new Fetch("http://localhost:8000");
    preloader.style.display = 'block';
    modalAuth.style.alignItems= "center"
    instance.open()
    
    fetch.post('/autenticacao', {inputs}, {"Content-Type" : "application/json"}, "json")
    .then(data => {
        console.log(data);
        if(data != null){
          // Hide preloader
          preloader.style.display = 'none';
          // Display data in the 'data' div
          instance.close();

            window.location.href = data.redirect_url;
        }

    }).catch(error => {
        console.error('Error:', error);
        // Hide preloader in case of error
        preloader.style.display = 'none';
        instance.close();
    });

  });
}

if(button.submitCadastroCilvil != undefined){
  //Ação do botão para cadastro de civil ou pet
  button.submitCadastroCilvil.addEventListener('click', function(e){
    e.preventDefault();

    let inputs = initVarsCivil();
    
    let fetch = new Fetch('http://localhost:8000');

    var formData = new FormData();
     // Adiciona os campos de texto ao FormData
    for (const key in inputs) {
      if(inputs[key] == null){
        delete inputs[key];
      }
      formData.append(key, inputs[key]);
    }


    if(inputs.pet_civil == "Pet"){

      fetch.post('/novo/pet', formData)
      .then(data => {
        if(data.status == "sucesso"){
          var successModal = document.getElementById('successModalPessoaPet');
          var instance = M.Modal.init(successModal);
            instance.open();
        }
        
          setTimeout(()=>{
            location.reload();
          },6000)
      })
      .catch(error => console.log(error))

    }

    if(inputs.pet_civil == "Civil"){

      fetch.post('/novo/civil', formData)
      .then(data => {
        if(data.status == "sucesso"){
          var successModal = document.getElementById('successModalPessoaPet');
          var instance = M.Modal.init(successModal);
          instance.open();
        }
        setTimeout(()=>{
          location.reload();
        },8000)
        
      })
      .catch(error => console.log(error))
    }
  });
}

/**
 * Renderiza o gráfico com chartJs
 * @param {*} label Labels que terão relação com os valores
 * @param {*} value Valores a serem mostrados no gráfico 
 * @param {*} idCanvas O elemento onde o gráfico será renderizado,
 * @param {*} type Tipo de gráfico
 * @param {*} title Título que aparece gráfico
 */
function getChart(label, value, idCanvas, type = 'bar', title = ""){

  let ct = initVarCanvas(idCanvas);

  const labels = label;
  const values = value;

  var cores = returnColors(values.length);

  new Chart(ct.ctx, {
    type,
    data: {
      labels: labels,
      datasets: [{
        label: title,
        data: values,
        backgroundColor: cores,
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

}

//Links ativos
document.addEventListener("DOMContentLoaded", function() {
  // Obtém o caminho da URL atual
  var path = window.location.pathname;


  if(path == "/login"){

    var listaItens = document.querySelectorAll('.sidenav li');

    document.querySelector(".sidenav-trigger").setAttribute("hidden", "hidden");
    document.getElementById("logout").setAttribute("hidden", "hidden");
    document.getElementById("ajuda").setAttribute("hidden", "hidden");
    document.getElementById("info").setAttribute("hidden", "hidden");
    document.getElementById("drPessoa").setAttribute("hidden", "hidden");
    document.getElementById("drDoacao").setAttribute("hidden", "hidden");
    document.getElementById("principal").setAttribute("hidden", "hidden");
    document.getElementById("drAbrigo").setAttribute("hidden", "hidden");

    // Iterar sobre cada elemento e escondê-lo
    listaItens.forEach(function(item) {
      item.classList.add('hide');
  });
  }

  // Seleciona a lista da navbar
  var navbarList = document.querySelector('.right');

  // Seleciona todos os itens da lista da navbar
  var links = navbarList.querySelectorAll('li');

  // Itera sobre os links da navbar
  links.forEach(function(link) {
      var href = link.querySelector('a').getAttribute('href');
      // Verifica se o atributo href do link corresponde ao caminho da URL atual
      if (href === path) {
          // Adiciona a classe 'active' ao link correspondente
          link.classList.add('active');
      }
  });
});

//Formatar telefone
let telefone = document.getElementById('txt_telefone');

if(telefone != undefined){
  telefone.addEventListener('keyup', (event)=>{
    // Get the input value and remove non-digit characters
    let input = event.target.value.replace(/\D/g, '');

    // Check if the input length is less than 11 characters
    if (input.length < 11) {
        // Apply the mask for phone numbers with 10 digits (e.g., (11) 91234-5678)
        input = input.replace(/^(\d{2})(\d{4,5})(\d{4})$/, '($1) $2-$3');
    } else {
        // Apply the mask for phone numbers with 11 digits (e.g., (11) 99123-4567)
        input = input.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    }

    // Set the formatted value back to the input field
    event.target.value = input;
  });
}

let telefoneAbrigo = document.getElementById('txt_telefone_abrigo');
if(telefoneAbrigo != undefined){
  telefoneAbrigo.addEventListener('keyup', (event)=>{
    // Get the input value and remove non-digit characters
    let input = event.target.value.replace(/\D/g, '');

    // Check if the input length is less than 11 characters
    if (input.length < 11) {
        // Apply the mask for phone numbers with 10 digits (e.g., (11) 91234-5678)
        input = input.replace(/^(\d{2})(\d{4,5})(\d{4})$/, '($1) $2-$3');
    } else {
        // Apply the mask for phone numbers with 11 digits (e.g., (11) 99123-4567)
        input = input.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    }

    // Set the formatted value back to the input field
    event.target.value = input;
  });
}

//Gera cores aleatórias
function randomColor() {
  return '#' + Math.floor(Math.random()*16777215).toString(16);
}

//Retorno das cores para o gráfico renderizar com cores aleatórias
function returnColors(nData)
{
  var count = nData;
  var cores = [];

  for (var i = 0; i < count; i++) {
    cores.push(randomColor());
  }

  return cores;

}
