$(document).ready(function(){
    //BOTÓN PUBLICAR
    $('div.article-admin').find('a#publish').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();

        var href = $(this).attr('href');
        $(this).attr({href: '#'});

        if (confirm("¿Estás seguro que quieres publicar el artículo?")) {

            $('#loader').fadeIn('fast', 'linear');
            $('#Article-container').hide(0, function () {
                $('#loader').fadeOut('fast', 'linear', function () {
                    $.get(href, function(data) {                                
                        if(data['Error']) {
                            $('<p class="invalid-feedback">'+data['Error']+'</p>').prependTo('#user-content');
                        } else {
                            $('#user-content').load(href).fadeIn('slow');
                        }
                    });
                });
            });
        } return false;
    });

    //BOTÓN BORRAR
    $('div.article-admin').find('a#delete').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();

        var href = $(this).attr('href');
        $(this).attr({href: '#'});

        if (confirm("¿Estás seguro que quieres eliminar el artículo?")) {

            $('#loader').fadeIn('fast', 'linear');
            $('#Article-container').hide(0, function () {
                $('#loader').fadeOut('fast', 'linear', function () {
                    $.get(href, function(data) {                                
                        if(data['Error']) {
                            $('<p class="invalid-feedback">'+data['Error']+'</p>').prependTo('#user-content');
                        } else if(data['Exito']) {
                            $('<p class="alert-success">'+data['Exito']+'</p>').prependTo('#user-content');
                        } else {
                            $('<p class="invalid-feedback">Ha ocurrido un error desconocido.</p>').prependTo('#user-content');
                        }
                    });
                });
            });
        } return false;
    });

    //BOTÓN EDITAR
    $('div.article-admin').find('a#edit').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();

        var href = $(this).attr('href');
        $(this).attr({href: '#'});

        $('#loader').fadeIn('fast', 'linear');
        $('#Article-container').hide(0, function () {
            $('#loader').fadeOut('fast', 'linear', function() {
                $.get(href, function(data) {                                
                    if(data['Error']) {
                        $('<p class="invalid-feedback">'+data['Error']+'</p>').prependTo('#user-content');
                    } else {
                        $('#user-content').load(href).fadeIn('slow');
                    }
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