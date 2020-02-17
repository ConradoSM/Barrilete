/**
 * LAZY LOAD IMAGE
 */
$(function() {
    $('.lazy').lazy({
        effect: 'fadeIn',
        effectTime: 300,
        threshold: 0,
        visibleOnly: false,
        placeholder: 'data:image/gif',
        beforeLoad: function(element) {
            element.attr('src','/img/before-load.png');
        },
        onError: function(element) {
            element.attr('src','/svg/placeholder.svg');
            element.addClass('placeholder');
        }
    });
});

/**
 * HEADER FUNCTIONALITY
 */
$(document).ready(function() {
    $(window).scroll(function () {
        if ($(window).scrollTop() >= 95) {
            $('header').addClass('fixed');
            if ($(window).width() > 991.98) {
                $('.logo').addClass('small');
                $('div#nav-container').slideUp('fast', function () {
                    $('div.menu-btn-block').removeClass('active');
                });
                $('a#menu-btn').removeAttr('class');
            }
        } else {
            $('header').removeAttr('class');
            if ($(window).width() > 991.98) {
                $('.logo').removeClass('small');
                $('div#nav-container').slideDown('fast', function () {
                    $('div.menu-btn-block').addClass('active');
                });
                $('a#menu-btn').addClass('display');
            }
        }
    });

    /**
     * MENU BUTTON
     */
    $('a#menu-btn').on('click', function() {
        $('div.menu-btn-block').toggleClass('active');
        if ($(window).width() > 991.98) {
            $('div#nav-container').slideToggle();
        } else {
            $('div#nav-container').toggle(
                'slide', {
                    direction: 'left'
                }, 100
            );
        }
    });

    /**
     * MOBILE BUTTON SEARCH
     */
    const buttonSearch = $('img.search-mobile');
    const divSearch = $('div#search');
    buttonSearch.on('click', function () {
        divSearch.slideDown('fast');
        $('input#search-input').focus();
    });

    /**
     * User Bar
     * @type {jQuery|HTMLElement}
     */
    const userBar = $('div#user-bar img');
    const divUser = $('div#user-menu');
    userBar.on('click', function () {
        if ($(this).attr('data-bind')) {
            const link = $(this).attr('data-bind');
            $.ajax({
                type: 'GET',
                url: link,
                dataType: 'json',
                success: function (data) {
                    divUser.html(data).slideDown('fast');
                },
                error: function (xhr) {
                    divUser.html('<p>Error: ' + xhr.status + ' - ' + xhr.statusText + '</p>').slideDown('fast');
                }
            });
        }
    });

    /**
     * Autocomplete Search Functionality
     */
    const inputSearch = $('input#search-input');
    const divResults = $('div#results');
    let searchAPI = function () {
        inputSearch.autocomplete({
            source: function (request) {
                $.ajax({
                    type: 'GET',
                    url: '/autocomplete',
                    dataType: 'json',
                    data: {
                        query: request.term,
                    },
                    success: function (data) {
                        divResults.html(data).slideDown('fast');
                    },
                    error: function (xhr) {
                        divResults.html('<p>Error: ' + xhr.status + ' - ' + xhr.statusText+'</p>').slideDown('fast');
                    }
                });
            },
            minLength: 1
        });
    };

    const header = $('header');
    $(document).on('click', function (e) {
        if (e.target.id !== 'results' && !divResults.find(e.target).length) {
            divResults.slideUp('fast');
        }
        if (e.target.id !== 'user-menu' && !divUser.find(e.target).length) {
            divUser.slideUp('fast');
        }
        if ($(window).width() < 991.98) {
            if (e.target.id !== 'search' && !header.find(e.target).length) {
                divSearch.slideUp('fast');
            }
            if (e.target.id !== 'menu-btn' && !header.find(e.target).length) {
                $('div#nav-container').hide('slide', 100);
                $('div.menu-btn-block').removeClass('active');
            }
        }
    });

    inputSearch.keyup(function() {
        if ($(this).val().length === 0) {
            divResults.slideUp('fast');
        }
        searchAPI();
    }).keyup();
});
