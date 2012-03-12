$(document).ready(function() {
  $('.confirm').click(function() {
    if(confirm("Attention cette opération va supprimer définitivement l'élément ainsi que ceux qui en dépendent ! \nVoulez-vous vraiment continuer ?")) {
      return true;
    } else {
      return false;
    }
  });
});
