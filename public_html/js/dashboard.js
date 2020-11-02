$(document).ready(function() {
    /** Main menu functionality **/
    $('ul.menu').find('li').on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const link = $(this).attr('data-link');
        const submenu = $(this).find('ul.sub-menu');
        const arrow = $(this).find('img.arrow');
        if (link) {
            getConfirm(e, $(this), link, null, null);
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
        /** Stop ajax request running **/
        if ($(this).data('requestRunning')) {
            return;
        }
        $(this).data('requestRunning', true);
    });

    /** Find links **/
    $(document).find('a').each(function() {
        if (!$(this).attr('data-ajax')) {
            const href = $(this).attr('href'),
                dataConfirm = $(this).attr('data-confirm'),
                dataView = $(this).attr('data-view');
            $(this).attr({href: '#'});
            /** Click event **/
            $(this).on('click', function (e) {
                if (href) {
                    /** Execute ajax request **/
                    getConfirm(e, $(this), href, dataConfirm, dataView);
                }
            });
        }
    });

    /** Search content **/
    $('button#search-button').on('click', function(e) {
        const button = $(this),
            query = $('#query').val(),
            author = $('#author').val(),
            sec = $('#sec').val(),
            href = $('form#search').attr('action')+'?sec='+sec+'&author='+author+'&query='+query;
        getConfirm(e, button, href, null, null);
    });

    /** Before ajax request **/
    function beforeSend() {
        const userContent = $('div#user-content');
        $('html, body').animate({ scrollTop: userContent.offset().top }, 'fast');
        userContent.hide();
        $('div#loader').fadeIn('fast');
    }

    /** Get ajax request **/
    function getConfirm(e, button, href, dataConfirm, dataView) {
        e.preventDefault();
        e.stopImmediatePropagation();
        if (dataConfirm) {
            $.confirm({
                title: '<h3>Confirmar Acción</h3>',
                content: '<p class="alert feedback-warning">' + dataConfirm + '</p>',
                type: 'orange',
                boxWidth: '55%',
                useBootstrap: false,
                closeIcon: true,
                buttons: {
                    confirmar: {
                        btnClass: 'button danger',
                        action: function () {
                            ajaxCallback(button, href);
                        }
                    },
                    cancelar: {
                        btnClass: 'button default',
                    }
                }
            });
        } else if (dataView) {
            $.alert({
                title: '<h3>Comentario</h3>',
                boxWidth: '55%',
                useBootstrap: false,
                content: function(){
                    const self = this;
                    return $.get(href).done(function(response) {
                        self.setContentAppend('<div><p class="comment-info">'+response.date+' · <a href="'+response.article_link+'">Artículo</a></p><p class="comment-content">'+response.content+'</p></div>');
                    }).fail(function(xhr){
                        const xhrError = xhr.responseJSON.error ? xhr.responseJSON.error : xhr.statusText;
                        self.setContentAppend('<p class="alert feedback-error">' + xhr.status + ' - ' + xhrError + '</p>');
                    });
                },
                type: 'dark',
                closeIcon: true,
                buttons: {
                    Listo: {
                        btnClass: 'button primary'
                    }
                }
            })
        } else {
            ajaxCallback(button, href);
        }
    }

    /** Ajax Callback **/
    function ajaxCallback(button, href) {
        /** Stop ajax request running **/
        if (button.data('requestRunning')) {
            return;
        }
        button.data('requestRunning', true);
        /** Ajax request starter **/
        beforeSend();
        $.get(href).done(function(data) {
            $('#loader').fadeOut('fast', function () {
                $('div#user-content').html(data.view).fadeIn('fast');
            });
        }).fail(function(xhr) {
            const xhrError = xhr.responseJSON.error ? xhr.responseJSON.error : xhr.statusText,
                errorMessage = '<p class="alert feedback-error">' + xhr.status + ' - ' + xhrError + '</p>';
            $('#loader').fadeOut('fast', function () {
                $('div#user-content').html(errorMessage).fadeIn('fast');
            });
        }).always(function() {
            button.data('requestRunning', false);
        });
    }
});
