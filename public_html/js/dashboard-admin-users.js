$(document).ready(function(){        
    //BOTÓN CREAR
    $('a#crear').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();

        var href = $(this).attr('href');
        $(this).attr({href: '#'});

        $('#loader').fadeIn('fast');
        $('#user-content').hide(0, function () {
            $('#user-content').load(href, function () {
                $('#loader').fadeOut('fast', function () {
                    $('#user-content').fadeIn('fast');
                });
            });
        });
    });
    
    //BOTÓN BORRAR
    $('a#borrar').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();

        var href = $(this).attr('href');
        $(this).attr({href: '#'});

        if (confirm("¿Estás seguro que quieres eliminar el usuario?")) {

            $('#loader').fadeIn('fast');
            $('#user-content').hide(0, function () {
                $('#user-content').load(href, function () {
                    $('#loader').fadeOut('fast', function () {
                        $('#user-content').fadeIn('fast');
                    });
                });
            });
        } return false;
    });

    //BOTÓN EDITAR
    $('a#editar').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();

        var href = $(this).attr('href');
        $(this).attr({href: '#'});

        $('#loader').fadeIn('fast');
        $('#user-content').hide(0, function () {
            $('#user-content').load(href, function () {
                $('#loader').fadeOut('fast', function () {
                    $('#user-content').fadeIn('fast');
                });
            });
        });
    });
    //LISTADO
    $('a#ver').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();

        var href = $(this).attr('href');
        $(this).attr({href: '#'});

        $('#loader').fadeIn('fast');
        $('#user-content').hide(0, function () {
            $('#user-content').load(href, function () {
                $('#loader').fadeOut('fast', function () {
                    $('#user-content').fadeIn('fast');
                });
            });
        });
    });
    setTimeout(function(){
        $('p.alert-success').fadeOut('fast', function(){
            $('p.alert-success').remove();
        });
    }, 2000);
});