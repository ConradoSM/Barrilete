$(document).ready(function () {
    var header = $('header');
    var logo = $('img#logo');
    var btn = $('a#menu-btn');
    var sbtn = $('img#search-btn');

    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll >= 200) {
            header.removeClass('relative').addClass('fixed');
            logo.removeClass('logo').addClass('logoSmall');
            btn.removeClass('menu-btn').addClass('menu-btn-small');
            sbtn.removeClass('search-btn').addClass('search-btn-small');
        } else {
            header.removeClass('fixed').addClass('relative');
            logo.removeClass('logoSmall').addClass('logo');
            btn.removeClass('menu-btn-small').addClass('menu-btn');
            sbtn.removeClass('search-btn-small').addClass('search-btn');
        }
    });
    
    //BOTON MENU VERSION MOVIL
    $('a#menu-btn').on('click', function () {
        $('div.menu-btn-block').toggleClass('active');
        $('nav.none').toggleClass('active');
        $('div#glass.hide').toggleClass('show');

        $('div#glass').on('click', function () {
            $('div#glass').removeClass('show');
            $('nav.none').removeClass('active');
            $('div.menu-btn-block').removeClass('active');
        });
    });
    
    // BUSCADOR CONTENIDO USUARIOS
    $('img#search-btn').on('click', function () {
        $('div#search').slideDown('fast', 'linear');
        $("#inputText").focus();
        $('div#glass.hide').addClass('show');

    $('div#glass').on('click', function () {
        $('div#search').slideUp('fast', 'linear');
        $('div#glass').removeClass('show');
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
