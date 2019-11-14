/**
 * LOAD IMAGE
 */
$(function() {
    $('img.placeholder').hide();
    $('.lazy').lazy({
        effect: 'fadeIn',
        effectTime: 300,
        threshold: 0,
        visibleOnly: false,
        placeholder: 'data:image/gif',
        beforeLoad: function(element) {
            element.attr('src','data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mPcWQ8AAfcBOsScuDQAAAAASUVORK5CYII=');
        },
        onError: function(element) {
            const article = element.parent('article');
            article.find('img.placeholder').show();
        }
    });
});
$(document).ready(function(){

    var header = $('header');
    var logo = $('img#logo');
    var buttonMenu = $('a#menu-btn');
    var buttonSearch = $('img#search-btn');
    var inputSearch = $('div#search').find('input[type=search]');
    var inputSearchButton = $('div#search').find('img.big');

    $(window).scroll(function(){

        var scroll = $(window).scrollTop();

        if (scroll >= 200) {
            header.removeClass('relative').addClass('fixed');
            logo.removeClass('big').addClass('small');
            buttonMenu.removeClass('big').addClass('small');
            buttonSearch.removeClass('big').addClass('small');
            inputSearch.removeClass('big').addClass('small');
            inputSearchButton.removeClass('big').addClass('small');
        } else {
            header.removeClass('fixed').addClass('relative');
            logo.removeClass('small').addClass('big');
            buttonMenu.removeClass('small').addClass('big');
            buttonSearch.removeClass('small').addClass('big');
            inputSearch.removeClass('small').addClass('big');
            inputSearchButton.removeClass('small').addClass('big');
        }
    });

    //BOTON MENU VERSION MOVIL
    $('a#menu-btn').on('click', function(){
        $('div.menu-btn-block').toggleClass('active');
        $('nav.none').toggleClass('active');
        $('div#glass.hide').toggleClass('show');

        $('div#glass').on('click', function(){
            $('div#glass').removeClass('show');
            $('nav.none').removeClass('active');
            $('div.menu-btn-block').removeClass('active');
        });
    });
    // BUSCADOR DE CONTENIDO
    $('img#search-btn').on('click', function () {
        $('div#search').slideDown('fast', 'linear');
        $("#inputText").focus();
        $('div#glass.hide').addClass('show');

    $('div#glass').on('click', function () {
        $('div#search').slideUp('fast', 'linear');
        $('div#glass').removeClass('show');
        });
    });
});
