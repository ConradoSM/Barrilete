$(document).ready(function () {
    var progress = $('#progressBar-Container');
    var bar = $('.bar');
    var percent = $('.percent');
    var status = $('#status');
    var opciones = {
        beforeSerialize: getckeditor,
        beforeSubmit: mostrarLoader,
        uploadProgress: progressBar,
        success: mostrarRespuesta,
        error: mostrarError,
        data: $('#createArticle').serialize(),
        datatype: 'json'
    };

    $('#createArticle').ajaxForm(opciones);

    function getckeditor() {
        var section_notes_data = CKEDITOR.instances.article_body.getData();
        
        $('#article_body').val(section_notes_data);
    };
    function mostrarLoader() {
        
        var percentVal = '0%';
        $('p.invalid-feedback').hide();
        $(document).scrollTop(0);
        status.fadeOut('fast', function(){
            progress.fadeIn('fast', function(){
                bar.width(percentVal);
                percent.html(percentVal);
            });
        });
    };
    function progressBar(event, position, total, percentComplete) {
        
        var percentVal = percentComplete + '%';
        
        bar.width(percentVal);
        percent.html(percentVal);
    };
    function mostrarRespuesta(responseText) {

        $('#user-content').hide().html(responseText).fadeIn('fast');
    };
    function mostrarError(xhr) {
        
        var errors = xhr.responseJSON.errors;
        
        progress.fadeOut('fast', function(){    
            $('#status').fadeIn('slow');
            $.each(errors, function(key,value) {
                $('#errors').append('<p class="invalid-feedback">'+value+'</p>');       
            });
        });
    };   
    CKEDITOR.replace('article_body');
});