$('document').ready(function(){
    $('.action').hide();

    $('li.entity').hover(function(){
        $(this).children('.action').show();
    }, function(){
        $(this).children('.action').hide();
    });
});
