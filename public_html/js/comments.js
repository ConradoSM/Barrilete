$(document).ready(function() {
    /**
     * Constants
     * @type {jQuery|HTMLElement}
     */
    const form = $('form#comments');
    const loader = $('img.loader');
    const divStatus = $('div#status');
    const container = $('section.comments');

    /**
     * New Comment Form
     */
    form.validate({
        messages: {
            comment: 'Debes completar éste campo.'
        },
        errorElement: 'p',
        errorPlacement: function (error, element) {
            element.after(error);
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            ajaxPost(form.action, $(form).serialize())
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
            content: '<p class="alert feedback-warning">¿Realmente quieres borrar tu comentario?</p>',
            type: 'orange',
            boxWidth: '55%',
            useBootstrap: false,
            buttons: {
                borrar: {
                    btnClass: 'button danger',
                    action: function () {
                        let URL = '/comment/delete';
                        let data = {
                            _token: $('meta[name="_token"]').attr('content'),
                            id: commentID,
                            article_id: articleID,
                            section_id: sectionID
                        };
                        ajaxPost(URL, data);
                    }
                },
                cancelar: {
                    btnClass: 'button default',
                }
            }
        })
    };

    /**
     * Edit Comment
     * @param commentID
     * @param articleID
     * @param sectionID
     * @param commentContent
     */
    editComment = function (commentID, articleID, sectionID, commentContent) {
        $.confirm({
            title: 'Editar Comentario',
            content: '<textarea id="reply" placeholder="Tu respuesta:" required>'+commentContent+'</textarea>',
            onContentReady: function () {
                $('textarea#reply').focus();
            },
            type: 'blue',
            boxWidth: '55%',
            useBootstrap: false,
            buttons: {
                enviar: {
                    btnClass: 'button primary',
                    action: function () {
                        let URL = '/comment/update';
                        let textarea = $('textarea#reply');
                        let data = {
                            _token: $('meta[name="_token"]').attr('content'),
                            id: commentID,
                            article_id: articleID,
                            section_id: sectionID,
                            comment: textarea.val()
                        };
                        ajaxPost(URL, data);
                    }
                },
                cancelar: {
                    btnClass: 'button default',
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
                    btnClass: 'button primary',
                    action: function () {
                        let URL = '/comment/save';
                        let textarea = $('textarea#reply');
                        let data = {
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
                    btnClass: 'button default',
                }
            }
        })
    };

    /**
     * Get Comments
     * @param link
     */
    getComments = function (link)
    {
        $.ajax({
            method: 'GET',
            url: link,
            beforeSend: function () {
                loader.fadeIn('fast');
            },
            success: function (data) {
                container.hide().html(data).fadeIn('normal');
            },
            error: function (xhr) {
                container.hide().html('<p class="alert feedback-error">'+xhr.status+' - '+xhr.statusText+'</p>').fadeIn('normal');
            },
            complete: function () {
                loader.fadeOut('fast');
            }
        });
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
            beforeSend: function () {
                loader.fadeIn('fast');
                $(form).find(':input').attr('disabled', true);
                $(form).find(':input').addClass('disabled');
                divStatus.find('p').remove();
            },
            success: function (data) {
                divStatus.append('<p class="alert feedback-success">'+ data.success +'</p>');
                divStatus.find('p').delay(3000).fadeOut('fast');
                container.html(data.view).fadeIn('fast');
                $('html, body').animate({scrollTop:container.offset().top -250});
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function (key, value) {
                        divStatus.append('<p class="alert feedback-error">'+ value +'</p>');
                    });
                } else {
                    divStatus.html('<p class="alert feedback-error">'+ xhr.status + ' - ' + xhr.statusText +'</p>');
                }
            },
            complete: function () {
                loader.fadeOut('fast', function () {
                    $(form).find(':input').attr('disabled', false);
                    $(form).find(':input').removeClass('disabled');
                    $(form).find('input[type=hidden]#parent_id').attr('value', '');
                    $(form).resetForm();
                });
                $('html, body').animate({scrollTop:container.offset().top -250});
            }
        });
    };
});
