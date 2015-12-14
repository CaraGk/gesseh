/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François Angrand <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
*/

$(document).ready(function() {
    var hideAll = function () {
        for (var i = 1; i < 10; i++) {
            $('#gesseh_registerbundle_filtertype_value_' + i).hide();
            $("label[for='gesseh_registerbundle_filtertype_value_" + i + "']").hide();
        }
    }

    hideAll();

    $('#gesseh_registerbundle_filtertype_question').change(function() {
        hideAll();
        $('#gesseh_registerbundle_filtertype_value_' + $(this).val()).show();
    });
});
