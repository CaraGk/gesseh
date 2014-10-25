$('document').ready(function(){
    $('.action').hide();

    $('li.entity').hover(function(){
        $(this).children('ul').children('.action').show();
    }, function(){
        $(this).children('ul').children('.action').hide();
    });
});
