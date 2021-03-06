$(document).ready(function() {
    const messages = $('ul.messages');
    messages.animate({
        scrollTop: messages.prop('scrollHeight')
    }, 500);
    let page = messages.data('next-page');
    const loader = $('img#loader');
    if (page) {
        messages.scroll(function () {
            clearTimeout($.data(this, 'scrollCheck'));
            $.data(this, 'scrollCheck', setTimeout(function() {
                if (messages.scrollTop() === 0 && page) {
                    const offSet = $('li.replies').first();
                    $.get(page, {
                        beforeSend: function() {
                            loader.show();
                        }
                    }).done(function(data) {
                        messages.prepend(data.replies);
                        messages.scrollTop(offSet.position().top - 50);
                        page = data.next_page;
                        if (!page) {
                            $('li#parent').prependTo(messages).css('display','flex');
                        }
                    }).always(function() {
                        loader.hide();
                    });
                }
            }, 350));
        });
    } else {
        $('li#parent').prependTo(messages).css('display','flex');
    }

    /** Validate form **/
    $('form#send-message').validate({
        messages: {
            body: {
                required: 'Éste campo es requerido.'
            }
        },
        errorElement: 'p',
        errorPlacement: function(error, element) {
            element.after(error);
        },
        submitHandler: function(form) {
            loader.show();
            $.post(form.action, $(form).serialize()
            ).done(function(data) {
                $('div#container').html(data.view);
                if (data.status) {
                    const statusClass = data.status === 'success' ? 'success' : 'error' ? 'error' : 'warning';
                    $('div#status').html('<p class="alert feedback-'+statusClass+'">'+data.message+'</p>');
                }
            }).fail(function(xhr) {
                const errors = typeof xhr.responseJSON != 'undefined' ? xhr.responseJSON.errors : '';
                if (errors) {
                    $.each(errors, function (key, value) {
                        $('div#status').append('<p class="alert feedback-error">' + value + '</p>');
                    });
                } else {
                    $('div#status').html('<p class="alert feedback-error">'+ xhr.status + ' - ' + xhr.statusText +'</p>');
                }
            }).always(function() {
                $('html, body').animate({
                    scrollTop: $('div#users-content').offset().top -250
                });
                loader.hide();
                form.reset();
            });
        }
    });

    /** Delete message **/
    $('img.delete-message').on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const messageID = $(this).data('message-id'),
            screenWidth = $( window ).width() < 767.98 ? '90%' : '55%';
        $.confirm({
            title: 'Confirmar',
            closeIcon: true,
            content: '<p class="alert feedback-warning">¿Estás seguro que deseas borrar éste mensaje?</p>',
            boxWidth: screenWidth,
            useBootstrap: false,
            type: 'dark',
            buttons: {
                confirmar: {
                    btnClass: 'button small danger',
                    action: function() {
                        const container = $('div#container'),
                            loader = $('img#loader');
                        loader.show();
                        container.hide();
                        $.get('/dashboard/myaccount/messages/message/delete/'+messageID).done(function(data) {
                            container.html(data.view);
                            if (data.status) {
                                $('div#status').html('<p class="alert feedback-'+data.status+'">'+data.message+'</p>');
                            }
                        }).fail(function(xhr) {
                            $('div#status').html('<p class="alert feedback-error">'+ xhr.status + ' - ' + xhr.statusText +'</p>')
                        }).always(function() {
                            $('html, body').animate({
                                scrollTop: $('div#users-content').offset().top -250
                            });
                            loader.hide();
                            container.show();
                        });
                    }
                },
                cancelar: {
                    btnClass: 'button small default',
                }
            }
        });
    });
});
