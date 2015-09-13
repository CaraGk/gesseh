/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François Angrand <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
*/

$(document).ready(function() {
  $('.confirm').click(function() {
    if(confirm("Attention cette opération va supprimer définitivement l'élément ainsi que ceux qui en dépendent ! \nVoulez-vous vraiment continuer ?")) {
      return true;
    } else {
      return false;
    }
  });
});
