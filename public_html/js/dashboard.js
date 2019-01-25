$(document).ready(function () {

    // NAVEGACIÓN CONTENIDO PRINCIPAL DEL DASHBOARD
    $(document).find('a').each(function () {

        var href = $(this).attr('href');
        $(this).attr({href: '#'});

        $(this).click(function () {

            $('aside.tools-bar').find('a').removeClass('selected');
            $(this).addClass('selected');
            $('#loader').fadeIn('fast');

            $('#user-content').hide(0, function () {
                $('#user-content').load(href, function () {
                    $('#loader').fadeOut('fast', function () {
                        $('#user-content').fadeIn('fast');
                    });
                });
            });
        });
    });
    // BUSCADOR CONTENIDO USUARIOS
    $('button#search-button').on('click', function(e){            
        e.preventDefault();
        var form = $('#search');
        var formAction = $(form).attr('action');           
        $.ajax({
            type: 'get',
            url: formAction,
            data: $(form).serialize(),
            beforeSend: function(){
                $('#loader').fadeIn('fast');
            }
        }).done(function(responseText){ 
            $('#loader').fadeOut('fast', function(){
                $('#user-content').html(responseText).fadeIn('fast');
                $(form).trigger('reset');
            });        
        }).fail(function(xhr, statusText){
            $('#loader').fadeOut('fast', function(){
                $('#user-content').empty();
                $('#user-content').prepend('<p class="invalid-feedback">Error: '+xhr.statusText+'</p>'); 
            });
        });
    });    
    // DROPDOWN MENU USUARIO
    $('div#user-menu').click(function(){

        $(this).find('div#user-options').slideToggle('fast');
        $(this).addClass('focus');
    });

    $(document).on('click', function(event){

        var trigger = $('div#user-menu');

        if(trigger !== event.target && !trigger.has(event.target).length){

            $('div#user-options').slideUp('fast');
            $(trigger).removeClass('focus');
        }            
    });
});