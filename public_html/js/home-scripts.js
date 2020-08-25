$(document).ready(function() {
    /** Lazy Load Images **/
    $(document).find('img.lazy').lazy({
        effect: 'fadeIn',
        effectTime: 300,
        threshold: 0,
        placeholder: 'data:image/gif',
        onError: function(element) {
            element.attr('src','/img/placeholder.png').addClass('placeholder').css({
                'background-image': 'none',
                'width': '100%'
            });
        },
        onFinishedAll: function() {
            $(document).find('img.lazy').css('background-image', 'none');
        }
    });

    /** Header Functionality **/
    $(window).scroll(function() {
        if ($(window).scrollTop() >= 50) {
            if ($(window).width() > 991.98) {
                $('div#nav-container').slideUp('fast', function() {
                    $('div.menu-btn-block').removeClass('active');
                });
                $('a#menu-btn').removeAttr('class');
                $('div#header-container').css('padding','20px 0 20px 0');
                $('div#user-menu').css('left')
            }
            $('header').addClass('fixed');
        } else {
            if ($(window).width() > 991.98) {
                $('div#nav-container').slideDown('fast', function () {
                    $('div.menu-btn-block').addClass('active');
                });
                $('a#menu-btn').addClass('display');
                $('div#header-container').css('padding','30px 0 20px 0');
            }
            $('header').removeClass('fixed');
        }
    });

    /** Menu Button **/
    $('a#menu-btn').on('click', function() {
        $('div.menu-btn-block').toggleClass('active');
        if ($(window).width() > 991.98) {
            $('div#nav-container').slideToggle(200);
        } else {
            $('div#nav-container').toggle('slide', {
                direction: 'left'
            }, 100);
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
            $.get(link, {
                login_redirect: window.location.href
            }).done(function(data) {
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
    const inputSearch = $('input#search-input'),
        divResults = $('div#results');
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

    /** Go to message from notification bar **/
    if (window.location.search) {
        const url_string = window.location.href,
            url = new URL(url_string),
            loader = $('img#loader').show(),
            container = $('div#container');
        if (url.searchParams.get('conversation_id') && !isNaN(url.searchParams.get('conversation_id'))) {
            container.hide();
            loader.show();
            $.get('/dashboard/myaccount/messages/message/' + url.searchParams.get('conversation_id')).done(function(data) {
                container.html(data.view);
                if (data.status) {
                    $('div#status').html('<p class="alert feedback-'+data.status+'">'+data.message+'</p>');
                }
            }).fail(function(xhr) {
                container.html('<p class="alert feedback-error">' + xhr.statusText + '</p>');
            }).always(function() {
                afterLoadContent(loader, container);
            });
        }
    }

    /** Dashboard Menu **/
    $(document).find('div#users-menu').find('ul li').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const link = $(this).attr('data-link'),
            submenu = $(this).find('ul.sub-menu'),
            arrow = $(this).find('img.arrow'),
            loader = $('img#loader'),
            container = $('div#container');
        if (link) {
            container.hide();
            loader.show();
            $.get(link).done(function(data) {
                container.html(data.view);
            }).fail(function(xhr) {
                container.html('<p class="alert feedback-error">Error: ' + xhr.status + ' - ' + xhr.statusText+'</p>');
            }).always(function() {
                afterLoadContent(loader, container);
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

    function afterLoadContent(loader, container) {
        $('html, body').animate({
            scrollTop: $('div#users-content').offset().top -250
        });
        loader.hide();
        container.show();
    }

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

    /** Newsletter subscribe **/
    $('p.newsletter').find('span').on('click', function() {
        const formNewsletter = $('form#newsletter');
        if (formNewsletter.valid()) {
            sendSubscription(formNewsletter.attr('action'), formNewsletter.serialize());
            formNewsletter.resetForm();
        }
    });

    $('a#subscribe').on('click', function(e) {
        e.preventDefault();
        const action = $(this).attr('href'),
            data = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: $(this).attr('data-bind')
            }
        sendSubscription(action, data);
    });

    function sendSubscription(action, data) {
        $.post(action, data).done(function(response) {
            newsletterResponse(response.message, response.type);
            const link = $('a#subscribe');
            if (data.status === '1') {
                link.html('Cancelar suscripción');
                link.attr('data-bind', '0');
            } else {
                link.html('Suscribirse');
                link.attr('data-bind', '1');
            }
        }).fail(function() {
            newsletterResponse('Ocurrió un error al procesar tu suscripción, por favor intentalo mas tarde.', 'error');
        });
    }

    function newsletterResponse(data, classType) {
        const screenWidth = $(window).width() < 767.98 ? '90%' : '55%';
        return $.alert({
            title: 'Boletín informativo',
            closeIcon: true,
            content: '<p class="alert feedback-'+classType+'">' + data + '</p>',
            boxWidth: screenWidth,
            useBootstrap: false,
            type: 'dark',
            buttons: {
                Cerrar: {
                    btnClass: 'button small primary',
                }
            }
        });
    }

    $('form#newsletter').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: 'Éste campo es requerido',
                email: 'La dirección ingresada no es válida'
            }
        },
        errorElement: 'p',
        errorPlacement: function(error, element) {
            element.after(error);
        }
    });

    /** Close breakingNews info **/
    $('img#close-breakingnews').on('click', function() {
        $('article.breaking-news').slideUp();
    });

    /** Form Poll Validate **/
    $('form#poll').validate({
        messages: {
            id_opcion: {
                required: 'Debes escoger alguna opción',
            }
        },
        errorElement: 'p',
        errorPlacement: function(error) {
            $(document).find('p#id_opcion-error').remove();
            $('form#poll').after(error);
        }
    });
});
