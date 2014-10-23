$(document).ready(function(){
    var pattern = {
        ' ;' : /,/gi,
        ',' : / ;/gi,
    };

    $('#separator').on('change', function() {
        $('#showlist').text($('#showlist').text().replace(pattern[$(this).val()], $(this).val()));
    });
});
