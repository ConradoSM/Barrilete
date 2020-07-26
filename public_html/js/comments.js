$(document).ready(function() {
    const form = $('form#comments'),loader = $('img.loader'),divStatus = $('div#status'),container = $('section.comments');

    /** Post New Comment **/
    form.validate({
        messages: {
            comment: 'Debes completar éste campo.'
        },
        errorElement: 'p',
        errorPlacement: function(error, element) {
            element.after(error);
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            ajaxPost(form.action, $(form).serialize());
        }
    });

    /** Delete Comment **/
    deleteComment = function(commentID, articleID, sectionID) {
        $.confirm({
            title: 'Borrar Comentario',
            content: '<p class="alert feedback-warning">¿Realmente quieres borrar tu comentario?</p>',
            type: 'orange',
            boxWidth: '55%',
            useBootstrap: false,
            buttons: {
                borrar: {
                    btnClass: 'button danger',
                    action: function() {
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
    }

    /** Add/Edit Comment **/
    replyComment = function(commentID, articleID, sectionID, userID, commentContent) {
        const title = userID ? 'Responder Comentario' : 'Editar Comentario', textareaContent = commentContent ? commentContent : '';
        $.confirm({
            title: title,
            content: '<form id="reply"><textarea name="reply" placeholder="Tu respuesta:" required>' + textareaContent + '</textarea></form>',
            onContentReady: function () {
                $('textarea#reply').focus();
            },
            type: 'blue',
            boxWidth: '55%',
            useBootstrap: false,
            buttons: {
                enviar: {
                    btnClass: 'button primary',
                    action: function() {
                        const formReply = $('form#reply');
                        formReply.validate({
                            messages: {
                                reply: 'Debes completar éste campo.'
                            },
                            errorElement: 'p',
                            errorPlacement: function (error, element) {
                                element.after(error);
                            }
                        });
                        if (formReply.valid()) {
                            let URL = userID ? '/comment/save' : '/comment/update';
                            let textarea = formReply.find('textarea');
                            let token = $('meta[name="_token"]').attr('content');
                            let data = commentContent ? {
                                _token: token,
                                id: commentID,
                                article_id: articleID,
                                section_id: sectionID,
                                comment: textarea.val()
                            } : {
                                _token: token,
                                parent_id: commentID,
                                article_id: articleID,
                                section_id: sectionID,
                                user_id: userID,
                                comment: textarea.val()
                            };
                            ajaxPost(URL, data);
                        } else {
                            return false;
                        }
                    }
                },
                cancelar: {
                    btnClass: 'button default',
                }
            }
        })
    };

    /** Get Comments **/
    getComments = function(link) {
        $.get(link, {
            beforeSend: function () {
                loader.fadeIn('fast');
            }
        }).done(function (data) {
            container.hide().html(data).fadeIn('normal');
        }).fail(function (xhr) {
            container.hide().html('<p class="alert feedback-error">' + xhr.status + ' - ' + xhr.statusText + '</p>').fadeIn('normal');
        }).always(function () {
            loader.fadeOut('fast');
        });
    }

    /** Ajax Post Comment **/
    ajaxPost = function(URL, data) {
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
                $('#comments-count span').text(data.count);
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
    }

    /** Comment Reaction Save **/
    commentReactionSave = function (userID, commentID, reaction) {
        $.post('/reaction/save', {
            _token: $('meta[name="_token"]').attr('content'),
            user_id: userID,
            comment_id: commentID,
            reaction: reaction
        }, function (data) {
            const like = $('a#like-' + commentID), dislike = $('a#dislike-' + commentID);
            if (data.reaction === '1') {
                like.addClass('reaction-active');
                dislike.removeAttr('class');
            } else if (data.reaction === '0') {
                like.removeAttr('class');
                dislike.addClass('reaction-active');
            } else {
                like.removeAttr('class');
                dislike.removeAttr('class');
            }
        }).done().fail(function (xhr) {
            divStatus.html('<p class="alert feedback-error">' + xhr.status + ' - ' + xhr.statusText + '</p>');
        }).always(function (data) {
            if (!data.statusText) {
                const likes = $('img#total-likes-' + commentID), dislikes = $('img#total-dislikes-' + commentID);
                likes.get(0).nextSibling.nodeValue = ' ' + data.likes;
                dislikes.get(0).nextSibling.nodeValue = ' ' + data.dislikes;
            }
        });
    }
});
