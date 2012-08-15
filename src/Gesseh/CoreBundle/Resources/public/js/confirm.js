$(document).ready(function() {
  $('.confirm').click(function() {
    if(confirm("Attention cette opération va supprimer définitivement l'élément ainsi que ceux qui en dépendent !\n\nSi vous rencontrez un message d'erreur, c'est probablement que certains de ces éléments liés ne peuvent être supprimés automatiquement. Vous devrez le faire manuellement.\n\nVoulez-vous vraiment continuer ?")) {
      return true;
    } else {
      return false;
    }
  });
});
