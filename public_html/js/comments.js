$(document).ready(function() {
    /**
     * Post New Comment
     */
    $('form#comments').validate({
        messages: {
            comment: 'Debes completar éste campo.'
        },
        errorElement: 'p',
        errorPlacement: function (error, element) {
            element.after(error);
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            const divError = $('div#status');
            $.ajax({
                type: form.method,
                url: form.action,
                data: $(form).serialize(),
                datatype: 'json',
                async: true,
                beforeSend: function () {
                    $('img.loader').fadeIn('fast');
                    $('section.comments').css('opacity', '50%');
                    $(form).find(':input').attr('disabled', true);
                    $(form).find(':input').addClass('disabled');
                    $(form).find('input[type=submit]').attr('value', 'Enviando...');
                    divError.find('p').remove();
                },
                success: function (responseText) {
                    divError.append('<p class="alert feedback-success"><img src="/svg/ajax-success.svg" alt="Exito"/>' + responseText.success + '</p>');
                    divError.find('p').delay(3000).fadeOut('fast');
                    $('section.comments').html(responseText.view).fadeIn('fast');
                },
                error: function (xhr) {
                    const imgError = '<img src="/svg/ajax-error.svg"/>';
                    const errors = xhr.responseJSON.errors;
                    $('div#status').fadeIn(function () {
                        if (errors) {
                            $.each(errors, function (key, value) {
                                $('div#status').append('<p class="alert feedback-error">' + imgError + value + '</p>');
                            });
                        } else {
                            $('div#status').html('<p class="alert feedback-error">' + imgError + xhr.status + ' - ' + xhr.statusText + '</p>');
                        }
                    });
                },
                complete: function () {
                    $('img.loader').fadeOut('fast', function () {
                        $(form).find(':input').attr('disabled', false);
                        $(form).find(':input').removeClass('disabled');
                        $(form).find('input[type=submit]').attr('value', 'Comentar');
                        $('section.comments').css('opacity', '100%');
                        $(form).find('input[type=hidden]#parent_id').attr('value', '');
                        $(form).resetForm();
                    });
                }
            });
        }
    });

    /**
     * Delete Comment Confirm
     * @param commentID
     * @param articleID
     * @param sectionID
     */
    deleteConfirm = function (commentID, articleID, sectionID) {
        $.confirm({
            title: 'Borrar Comentario',
            content: '<p class="alert feedback-warning"><img src="/svg/ajax-warning.svg" />¿Realmente quieres borrar tu comentario?</p>',
            type: 'orange',
            boxWidth: '55%',
            useBootstrap: false,
            buttons: {
                borrar: {
                    btnClass: 'btn-red',
                    action: function () {
                        const URL = '/comment/delete';
                        const data = {
                            _token: $('meta[name="_token"]').attr('content'),
                            id: commentID,
                            article_id: articleID,
                            section_id: sectionID
                        };
                        ajaxPost(URL, data);
                    }
                },
                cancelar: {
                    btnClass: 'btn-default',
                }
            }
        })
    };

    /**
     * Reply Comment
     * @param commentID
     * @param articleID
     * @param sectionID
     * @param userID
     */
    replyComment = function (commentID, articleID, sectionID, userID) {
        $.confirm({
            title: 'Responder Comentario',
            content: '<textarea id="reply" placeholder="Tu respuesta:" required></textarea>',
            onContentReady: function () {
                $('textarea#reply').focus();
            },
            type: 'blue',
            boxWidth: '55%',
            useBootstrap: false,
            buttons: {
                enviar: {
                    btnClass: 'btn-blue',
                    action: function () {
                        const URL = '/comment/save';
                        const textarea = $('textarea#reply');
                        const data = {
                            _token: $('meta[name="_token"]').attr('content'),
                            parent_id: commentID,
                            article_id: articleID,
                            section_id: sectionID,
                            user_id: userID,
                            comment: textarea.val()
                        };
                        ajaxPost(URL, data);
                    }
                },
                cancelar: {
                    btnClass: 'btn-default',
                }
            }
        })
    };


    /**
     * Ajax Post Comment
     * @param URL
     * @param data
     */
    ajaxPost = function (URL, data) {
        $.ajax({
            method: 'post',
            url: URL,
            dataType: 'json',
            async: true,
            data: data,
            success: function (response) {
                $.alert({
                    type: 'green',
                    boxWidth: '55%',
                    useBootstrap: false,
                    title: 'Listo!',
                    content: '<p class="alert feedback-success"><img src="/svg/ajax-success.svg" alt="Exito"/>' + response.success + '</p>',
                    buttons: {
                        ok: {
                            btnClass: 'btn-green'
                        }
                    }
                });
                $('section.comments').html(response.view).hide().fadeIn('normal');
            },
            error: function (xhr) {
                const imgError = '<img src="/svg/ajax-error.svg"/>';
                const errors = xhr.responseJSON.errors;
                $.alert({
                    type: 'red',
                    boxWidth: '55%',
                    useBootstrap: false,
                    title: 'Error',
                    content: '<div id="errors"></div>',
                    onContentReady: function () {
                        if (errors) {
                            $.each(errors, function (key, value) {
                                $('div#errors').append('<p class="alert feedback-error">' + imgError + value + '</p>');
                            });
                        } else {
                            $('div#errors').html('<p class="alert feedback-error">' + imgError + xhr.status + ' - ' + xhr.statusText + '</p>');
                        }
                    },
                    buttons: {
                        ok: {
                            btnClass: 'btn-red'
                        }
                    }
                });
            }
        });
    };
});
