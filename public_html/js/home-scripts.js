/**
 * LAZY LOAD IMAGE
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

/**
 * HEADER FUNCTIONALITY
 */
$(document).ready(function() {

    $(window).scroll(function() {
        if ($(window).scrollTop() >= 95) {
            $('header').addClass('fixed');
            $('img#logo').addClass('small');
            $('div.navContainer').slideUp('fast', function () {
                $('div.menu-btn-block').removeClass('active');
            });
            $('a#menu-btn').removeAttr('class');
        } else {
            $('header').removeAttr('class');
            $('img#logo').removeAttr('class');
            $('div.navContainer').slideDown('fast', function () {
                $('div.menu-btn-block').addClass('active');
            });
            $('a#menu-btn').addClass('display');
        }
    });

    /**
     * MOBILE BUTTON MENU
     */
    $('a#menu-btn').on('click', function() {
        $('div.menu-btn-block').toggleClass('active');
        $('div.navContainer').slideToggle();
    });

    /**
     * MOBILE BUTTON SEARCH
     */
    $('img#search-btn').on('click', function () {
        $('div#search').slideDown('fast', 'linear');
        $('#inputText').focus();
        $('div#glass.hide').addClass('show');
    });

    /**
     * Autocomplete Search Functionality
     */
    const inputSearch = $('input#search');
    const div = $('div#results');

    searchAPI = function () {
        inputSearch.autocomplete({
            source: function (request) {
                $.ajax({
                    type: 'GET',
                    url: '/autocomplete',
                    data: {
                        query: request.term,
                    },
                    success: function (data) {
                        div.html(data).show();
                    }
                });
            },
            minLength: 1
        });
    };

    $(document).on('click', function (e) {
        if (e.target.id !== 'results' && !div.find(e.target).length) {
            div.hide();
        }
    });
    inputSearch.keyup(function() {
        if ($(this).val().length === 0) {
            div.hide();
        }
        searchAPI();
    }).keyup();
});
