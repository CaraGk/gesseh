/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François Angrand <caragk@angrand.fr>
 * @copyright: Copyright 2014 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
*/

$(document).ready(function() {
    $('.evaluated').parent('ul').parent('li.entity').addClass('valid').addClass('mouseHand');
    $('.nonevaluated').parent('ul').parent('li.entity').addClass('invalid').addClass('mouseHand');

    $('.placement').on('click', function() {
        $(location).attr('pathname', $(this).children('ul.actions').children('li').children('a').attr('href'));
    });
});
