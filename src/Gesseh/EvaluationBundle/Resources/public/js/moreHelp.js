/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François Angrand <gesseh@medlibre.fr>
 * @copyright: Copyright 2014 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
*/

$(document).ready(function() {
    var moreHelp = {
        "1" : "Nombre N<span>|</span>Étiquette1<span>,</span>Étiquette2<span>,</span>...<span>,</span>ÉtiquetteN<span>,</span>ÉtiquetteN+1",
        "2" : "Pas de prise en compte de ce champ.",
        "3" : "Choix1<span>|</span>Choix2<span>|</span>...<span>|</span>ChoixN",
        "4" : "Arrondi en nombre de chiffres après la virgule",
        "5" : "Nombre de choix affichés dans l'évaluation<span>|</span>Choix1<span>|</span>Choix2<span>|</span>...<span>|</span>ChoixN",
        "6" : "Pas de prise en compte de ce champ.",
    };

    $("input[id$='more']").each(function() {
        type = $(this).parent('div').prev('div').children('select').val();
        $(this).after('<div class="help">(format : ' + moreHelp[type] + ')</div>');
    })

    $("select[id$='type']").change(function() {
        type = $(this).val();
        $(this).parent('div').next('div').children('div.help').html('<div class="help">(format : ' + moreHelp[type] + ')</div>');
    });
});
