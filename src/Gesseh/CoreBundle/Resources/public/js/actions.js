$('document').ready(function(){
    $('.actions').hide();

    $('li.entity').hover(function(){
        $(this).children('ul.actions').show();
    }, function(){
        $(this).children('ul.actions').hide();
    });

    $('li.subentity').hover(function(){
        $(this).children('ul.actions').show();
    }, function(){
        $(this).children('ul.actions').hide();
    });
});
