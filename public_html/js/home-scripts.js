/** Lazy Load Images **/
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

/** Header Functionality **/
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

    /** Menu Button **/
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

    /** Mobile Search Button **/
    const buttonSearch = $('img.search-mobile');
    const divSearch = $('div#search');
    buttonSearch.on('click', function () {
        divSearch.slideDown('fast');
        $('input#search-input').focus();
    });

    /** User Bar **/
    const userBar = $('div#user-bar img');
    const divUser = $('div#user-menu');
    userBar.on('click', function () {
        const img = $(this);
        if (img.attr('data-bind')) {
            const link = $(this).attr('data-bind');
            $.get(link, {
                beforeSend: function () {
                    if (divUser.is(':visible')) {
                        divUser.slideUp('fast');
                    }
                }
            }).done(function(data) {
                if (img.attr('alt') === 'Notificaciones') {
                    $('div.notifications.comments').hide();
                    $('div.notifications.comments span').text(0);
                } else if(img.attr('alt') === 'Mensajes') {
                    $('div.notifications.messages').hide();
                    $('div.notifications.messages span').text(0);
                }
                divUser.html(data);
            }).fail(function(xhr) {
                divUser.html('<p>Error: ' + xhr.status + ' - ' + xhr.statusText + '</p>');
            }).always(function() {
                divUser.slideDown('fast');
            });
        }
    });

    /** Autocomplete Search Functionality **/
    const inputSearch = $('input#search-input');
    const divResults = $('div#results');
    let searchAPI = function() {
        inputSearch.autocomplete({
            source: function(request) {
                const data = {query: request.term};
                $.get('/autocomplete', data).done(function(data) {
                    divResults.html(data).slideDown('fast');
                }).fail(function(xhr) {
                    divResults.html('<p>Error: ' + xhr.status + ' - ' + xhr.statusText+'</p>').slideDown('fast');
                });
            },
            minLength: 3
        });
    };
    inputSearch.keyup(function() {
        if ($(this).val().length === 0) {
            divResults.slideUp('fast');
        }
        searchAPI();
    }).keyup();

    /** Show User Notifications **/
    let divNotifications = $('div.notifications.comments');
    let spanValue = parseInt(divNotifications.find('span').text());
    if (spanValue > 0) {
        if (spanValue > 10) {
            divNotifications.find('span').text('+10');
        }
        divNotifications.show();
    }
    let userId = $('meta[name=user_id]').attr('content');
    if (userId) {
        /** Laravel Web Sockets **/
        window.Echo.private('Barrilete.User.' + userId)
            .notification((event) => {
                const numberOfNotifications = parseInt(divNotifications.find('span').text());
                divNotifications.find('span').text(numberOfNotifications === +10 ? '+10' : numberOfNotifications + event.data.notification);
                divNotifications.show();
            }
        );
    }

    /** Dashboard Menu **/
    $('div#users-menu').find('ul li').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const link = $(this).attr('data-link');
        const submenu = $(this).find('ul.sub-menu');
        const arrow = $(this).find('img.arrow');
        if (link) {
            $.get(link, {
                beforeSend: function() {
                    $(document).scrollTop(0);
                    $('div#users-content').html('<img id="loader" src="/img/loader.gif"  alt="loading..."/>');
                }
            }).done(function(data) {
                    $('div#users-content').html(data.view);
            }).fail(function(xhr) {
                    $('div#users-content').html('<p class="alert feedback-error">Error: ' + xhr.status + ' - ' + xhr.statusText+'</p>');
            });
        }
        if (submenu.length) {
            $('ul.sub-menu').slideUp();
            $('ul.menu li').removeClass('focus');
            $('img.arrow').removeClass('focus');
            if (submenu.is(':hidden')) {
                submenu.slideDown('fast');
                $(this).addClass('focus');
                arrow.addClass('focus');
            } else {
                submenu.slideUp('fast');
                $(this).removeClass('focus');
                arrow.removeClass('focus');
            }
        }
    });

    /** Document click functionality **/
    const header = $('header');
    $(document).on('click', function(e) {
        if (divResults.is(':visible') || divUser.is(':visible')) {
            if (e.target.id !== 'results' && !divResults.find(e.target).length) {
                divResults.slideUp('fast');
            }
            if (e.target.id !== 'user-menu' && !divUser.find(e.target).length) {
                divUser.slideUp('fast');
            }
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
});
