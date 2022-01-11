// import 'js/couture.js'
// Je dit a jQuery vas me cherche l'id qui se appele add-imageCouture
$('#add-imageCouture').click(function () {
// Je récupère le numéro des futurs champs que je vais créer
// On vas recuper le nombre de form-group qui existe dans la div qui se appele (couture_coutureImages) et stocker dans index
  const index = +$('#widgets-counter').val()

  // Je récupère le prototype des entrées (qui se trouve sur la div qui a l'id=couture_imageCoutures) en console
  const tmpl = $('#couture_imageCoutures').data('prototype').replace(/_name_/g, index)

  // J'injecte ce code de data_prototype au sein de la div
  $('#couture_imageCoutures').append(tmpl)

  // On dit a notre widgets-counter que ca valeur se sera l'index pus un
  $('#widgets-counter').val(index + 1)

  // Je gère le bouton supprimer
  handleDeleteButtons()
})

function handleDeleteButtons () {
// Button Supprimer une Image Toute les button data_action_delete
// Je cherche toute les button qui a comment action data_target
  $('button[data-action="delete"]').click(function () { // Donne moi le button que on a click
    const target = this.dataset.target
    // Vas me cherche la target (div) et supprime la.
    $(target).remove()
  })
}

// de mise a jour des images
function updateCounter () {
// Va cherche dans la div de form-group des images et leur nombre
//
  const count = +$('#couture_imageCouture div.form-group').length
  // On appele le compteur et ca valeur et on ajoute le compteur
  $('#widgets-counter').val(count)
}
// On appele le compteur de mois a jour
updateCounter()

handleDeleteButtons()
// Et aussi on gère le bouton a chaque foit que on recharge la page depuis debut
