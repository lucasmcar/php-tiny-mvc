// Função para criar uma classe CSS dinamicamente
function createCSSClass(cssObj = {}) {
    const style = document.createElement('style');
    style.type = 'text/css';
    style.innerHTML = `.${className} { ${rules} }`;
    document.getElementsByTagName('head')[0].appendChild(style);
}

// Função para adicionar a classe 'debug-border' a um elemento
function addDebugBorder(element) {
    element.classList.add('debug-border');
}

// Cria a classe 'debug-border'
createCSSClass('debug-border', 'border: 2px solid red; background-color: rgba(255, 0, 0, 0.1);');

// Adiciona evento ao botão para aplicar a classe 'debug-border' aos parágrafos
document.getElementById('debugButton').addEventListener('click', () => {
    const paragraphs = document.querySelectorAll('p');
    paragraphs.forEach(addDebugBorder);
});