//recuperer les classes
let toggle = document.querySelector('.toggle');
let body = document.querySelector('body');
// grace a la class body qui va s'ajouter à la balise body on saura si le menu est overt ou pas
// creer une fonction quand on click sur la toggle

toggle.addEventListener('click', function() {
    body.classList.toggle('afficher')
})