$(document).ready(function() {
    /** Main menu functionality **/
    $('ul.menu').find('li').on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const link = $(this).attr('data-link');
        const submenu = $(this).find('ul.sub-menu');
        const arrow = $(this).find('img.arrow');
        if (link) {
            getAjaxRequest(e, $(this), link, null);
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
        const href = $(this).attr('href');
        const dataConfirm = $(this).attr('data-confirm');
        $(this).attr({href: '#'});
        /** Click event **/
        $(this).on('click', function(e) {
            if (href) {
                /** Execute ajax request **/
                getAjaxRequest(e, $(this), href, dataConfirm);
            }
        });
    });

    /** Search content **/
    $('button#search-button').on('click', function(e) {
        const button = $(this);
        const query = $('#query').val();
        const author = $('#author').val();
        const sec = $('#sec').val();
        const href = $('form#search').attr('action')+'?sec='+sec+'&author='+author+'&query='+query;
        getAjaxRequest(e, button, href, null);
    });

    /** Before ajax request **/
    function beforeSend() {
        $('html, body').animate({ scrollTop: $('div#users-content').offset().top }, 'fast');
        $('div#user-content').hide();
        $('div#loader').fadeIn('fast');
    }

    /** Get ajax request **/
    function getAjaxRequest(e, button, href, dataConfirm) {
        e.preventDefault();
        e.stopImmediatePropagation();
        if (dataConfirm) {
            $.confirm({
                title: '<h3>Confirmar Acción</h3>',
                content: '<p class="alert feedback-warning">' + dataConfirm + '</p>',
                type: 'orange',
                boxWidth: '55%',
                useBootstrap: false,
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
            const xhrError = xhr.responseJSON.error ? xhr.responseJSON.error : xhr.statusText;
            const errorMessage = '<p class="alert feedback-error">' + xhr.status + ' - ' + xhrError + '</p>';
            $('#loader').fadeOut('fast', function () {
                $('div#user-content').html(errorMessage).fadeIn('fast');
            });
        }).always(function() {
            button.data('requestRunning', false);
        });
    }
});
