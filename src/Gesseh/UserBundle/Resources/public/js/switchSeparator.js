$(document).ready(function(){
    var pattern = new RegExp(", ", "gi");

    $('#separator').on('change', function() {
        if($(this).val() == "retour") {
            $('#showlist').text($('#showlist').text().replace(pattern, "\n"));
            pattern = new RegExp("\n", "gi");
        } else {
            $('#showlist').text($('#showlist').text().replace(pattern, $(this).val()));
            pattern = new RegExp($(this).val(), "gi");
        }
    });
});
