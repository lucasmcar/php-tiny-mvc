function addCivil () {
    const name = document.getElementById('name').value
    const idade = document.getElementById('idade').value
    const local = document.getElementById('local').value
    const info = document.getElementById('info').value



    const confirmation = confirm("Deseja Cadastrar Civil: \n\n" + name + " - " + idade + " ?")

    if (confirmation) {
        const civisList = document.getElementById('civisList')
        const civilItem = document.createElement('li')
        civilItem.id = 'Civil - '+ local
        civilItem.innerText = name + " : " + idade + " (" + local + ")" + info
        civisList.appendChild(civilItem)

        document.getElementById('name').value = ''
        document.getElementById('idade').value = ''
        document.getElementById('local').value = ''
        document.getElementById('info').value = ''
    }
}

