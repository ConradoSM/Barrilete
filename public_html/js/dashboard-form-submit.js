$(document).ready(function () {
    var opciones = {
        beforeSubmit: mostrarLoader,
        success: mostrarRespuesta,
        error: mostrarError,
        data: $('#createArticle').serialize(),
        datatype: 'json'
    };
    $('#createArticle').ajaxForm(opciones);
    
    function mostrarLoader() {

        $('#loader').fadeIn('slow');
        $('#Article_Form').css('display', 'none');
    };
    function mostrarRespuesta(responseText) {

        $('#loader').fadeOut('slow', function () {
            $('#Article_Form').css('display', 'none');
            $('#user-content').html(responseText).fadeIn('normal');
        });
    };
    function mostrarError(xhr) {
        
        var errors = xhr.responseJSON.errors;
        
        $('#loader').fadeOut('slow', function () {
            
            $('#Article_Form').fadeIn('slow');
            $.each(errors, function(key,value) {
                $('#errors').append('<p class="invalid-feedback">'+value+'</p>');       
            });
        });
    };   
    $('#enviar').on('click', function() {
        $('p.invalid-feedback').hide();
    });
});