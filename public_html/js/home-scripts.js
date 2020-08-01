/** Lazy Load Images **/
$(function() {
    $('.lazy').lazy({
        effect: 'fadeIn',
        effectTime: 300,
        threshold: 0,
        visibleOnly: false,
        placeholder: 'data:image/gif',
        onError: function(element) {
            element.attr('src','/img/placeholder.png').addClass('placeholder').css({
                'background-image': 'none',
                'width': '100%'
            });
        },
        onFinishedAll: function() {
            $('img.lazy').css('background-image', 'none');
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
    const userBar = $('div#user-bar img'),
          divUser = $('div#user-menu');
    userBar.on('click', function() {
        const img = $(this), link = $(this).attr('data-bind');
        if (link) {
            if (divUser.is(':visible')) {
                divUser.slideUp('fast');
            }
            $.get(link).done(function(data) {
                if (img.attr('alt') === 'Notificaciones') {
                    $('div#comments').hide(0,function() {
                        $(this).find('span').text(0);
                    });
                }
                if(img.attr('alt') === 'Mensajes') {
                    $('div#messages').hide(0,function() {
                        $(this).find('span').text(0);
                    });
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
    let divNotifications = $('div.notifications');
    divNotifications.each(function() {
        let spanValue = parseInt($(this).find('span').text());
        if (spanValue > 0) {
            if (spanValue > 10) {
                $(this).find('span').text('+10');
            }
            $(this).show();
        }
    });
    let userId = $('meta[name=user_id]').attr('content');
    if (userId) {
        /** Laravel Web Sockets **/
        window.Echo.private('Barrilete.User.' + userId)
            .notification((event) => {
                const notificationContainer =
                    event.type === 'barrilete\\Notifications\\UsersCommentReply' || event.type === 'barrilete\\Notifications\\UsersCommentReaction'
                        ? $('div#comments') : $('div#messages');
                const spanValue = parseInt(notificationContainer.find('span').text());
                notificationContainer.find('span').text(spanValue === +10 ? '+10' : spanValue + event.data.notification);
                notificationContainer.show();
            }
        );
    }

    /** Dashboard Menu **/
    $('div#users-menu').find('ul li').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const link = $(this).attr('data-link'),
            submenu = $(this).find('ul.sub-menu'),
            arrow = $(this).find('img.arrow'),
            loader = $('img#loader'),
            container = $('div#container');
        if (link) {
            $.get(link, {
                beforeSend: function() {
                    $(document).scrollTop(0);
                    container.hide();
                    loader.show();
                }
            }).done(function(data) {
                    container.html(data.view);
            }).fail(function(xhr) {
                    container.html('<p class="alert feedback-error">Error: ' + xhr.status + ' - ' + xhr.statusText+'</p>');
            }).always(function () {
                loader.hide();
                container.show();
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
