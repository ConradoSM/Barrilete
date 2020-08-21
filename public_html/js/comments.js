(function($){
    const $w = $(window);
    $.fn.visible = function(partial,hidden,direction,container){
        if (this.length < 1)
            return;
        direction = direction || 'both';
        const $t = this.length > 1 ? this.eq(0) : this,
            isContained = typeof container !== 'undefined' && container !== null,
            $c = isContained ? $(container) : $w,
            wPosition = isContained ? $c.position() : 0,
            t = $t.get(0),
            vpWidth = $c.outerWidth(),
            vpHeight = $c.outerHeight(),
            clientSize = hidden === true ? t.offsetWidth * t.offsetHeight : true;

        if (typeof t.getBoundingClientRect === 'function'){
            let rec = t.getBoundingClientRect(),
                tViz = isContained ?
                    rec.top - wPosition.top >= 0 && rec.top < vpHeight + wPosition.top :
                    rec.top >= 0 && rec.top < vpHeight,
                bViz = isContained ?
                    rec.bottom - wPosition.top > 0 && rec.bottom <= vpHeight + wPosition.top :
                    rec.bottom > 0 && rec.bottom <= vpHeight,
                lViz = isContained ?
                    rec.left - wPosition.left >= 0 && rec.left < vpWidth + wPosition.left :
                    rec.left >= 0 && rec.left < vpWidth,
                rViz = isContained ?
                    rec.right - wPosition.left > 0 && rec.right < vpWidth + wPosition.left :
                    rec.right > 0 && rec.right <= vpWidth,
                vVisible = partial ? tViz || bViz : tViz && bViz;
            let hVisible = partial ? lViz || rViz : lViz && rViz;
            vVisible = (rec.top < 0 && rec.bottom > vpHeight) ? true : vVisible;
            hVisible = (rec.left < 0 && rec.right > vpWidth) ? true : hVisible;

            if(direction === 'both')
                return clientSize && vVisible && hVisible;
            else if(direction === 'vertical')
                return clientSize && vVisible;
            else if(direction === 'horizontal')
                return clientSize && hVisible;
        } else {
            const viewTop = isContained ? 0 : wPosition,
                viewBottom = viewTop + vpHeight,
                viewLeft = $c.scrollLeft(),
                viewRight = viewLeft + vpWidth,
                position = $t.position(),
                _top = position.top,
                _bottom = _top + $t.height(),
                _left = position.left,
                _right = _left + $t.width(),
                compareTop = partial === true ? _bottom : _top,
                compareBottom = partial === true ? _top : _bottom,
                compareLeft = partial === true ? _right : _left,
                compareRight = partial === true ? _left : _right;
            if(direction === 'both')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop)) && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
            else if(direction === 'vertical')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
            else if(direction === 'horizontal')
                return !!clientSize && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
        }
    };
})(jQuery);

$(document).ready(function() {
    const form = $('form#send-comment'),
        loader = $('img.loader'),
        divStatus = $('div#status'),
        container = $('section.comments'),
        screenWidth = $(window).width() < 767.98 ? '90%' : '55%';

    /** Get Comments **/
    let isLoad = false;
    $(window).scroll(function() {
        const element = $('span#comments-count');
        if (element.visible() && !isLoad) {
            isLoad = true;
            setTimeout(function() {
                ajaxGet(element.data('url'));
            },1000);
        }
    });

    /** Comments Links Pagination **/
    $(document).on('click','a.page-link',function(e) {
        $('html, body').animate({
            scrollTop: $('span#comments-count').offset().top -250
        }, 100);
        e.preventDefault();
        ajaxGet($(this).attr('href'));
    });

    /** Comments Box Functionality **/
    $(document).on('mouseover','p.comment',function() {
        const buttonDelete = $(this).find('img.delete'), buttonEdit = $(this).find('img.edit');
        buttonEdit.show();
        buttonDelete.show();
        $(this).mouseleave(function() {
            buttonDelete.hide();
            buttonEdit.hide();
        });
    });

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
    $(document).on('click','.delete',function() {
        const commentID = $(this).data('comment'),
            articleID = $(this).data('article'),
            sectionID = $(this).data('section');
        $.confirm({
            title: 'Borrar Comentario',
            closeIcon: true,
            type: 'dark',
            content: '<p class="alert feedback-warning">¿Realmente quieres borrar tu comentario?</p>',
            boxWidth: screenWidth,
            useBootstrap: false,
            buttons: {
                borrar: {
                    btnClass: 'button small danger',
                    action: function () {
                        let URL = '/comment/delete';
                        let data = {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: commentID,
                            article_id: articleID,
                            section_id: sectionID
                        };
                        ajaxPost(URL, data);
                    }
                },
                cancelar: {
                    btnClass: 'button small default',
                }
            }
        });
    });

    /** Add/Edit Comment **/
    $(document).on('click','.edit',function() {
        const userID = $(this).data('user'),
            currentPage = $('ul.pagination').find('li.page-item.active').find('span.page-link').text(),
            title = userID ? 'Responder Comentario' : 'Editar Comentario',
            commentContent = $(this).closest('p.comment').find('span.content').text(),
            textareaContent = commentContent ? commentContent : '',
            commentID = $(this).data('comment'),
            articleID = $(this).data('article'),
            sectionID = $(this).data('section');
        $.confirm({
            title: title,
            closeIcon: true,
            content: '<form id="reply"><textarea name="reply" placeholder="Tu respuesta:" required>' + textareaContent + '</textarea></form>',
            onContentReady: function() {
                $('form#reply').find('textarea').focus();
            },
            boxWidth: screenWidth,
            useBootstrap: false,
            type: 'dark',
            buttons: {
                enviar: {
                    btnClass: 'button small primary',
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
                            let token = $('meta[name="csrf-token"]').attr('content');
                            let data = commentContent ? {
                                _token: token,
                                id: commentID,
                                article_id: articleID,
                                section_id: sectionID,
                                current_page: currentPage,
                                comment: textarea.val()
                            } : {
                                _token: token,
                                parent_id: commentID,
                                article_id: articleID,
                                section_id: sectionID,
                                user_id: userID,
                                current_page: currentPage,
                                comment: textarea.val()
                            };
                            ajaxPost(URL, data);
                        } else {
                            return false;
                        }
                    }
                },
                cancelar: {
                    btnClass: 'button small default',
                }
            }
        })
    });

    /** Comment Reaction Save **/
    $(document).on('click','a.reaction',function() {
        const userID = $(this).data('user'),
            commentID = $(this).data('comment'),
            reaction = $(this).data('reaction');

        divStatus.html('');
        $.post('/reaction/save', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            user_id: userID,
            comment_id: commentID,
            reaction: reaction
        }).done(function(data) {
            const like = $('a#like-' + commentID), dislike = $('a#dislike-' + commentID);
            if (data.reaction === '1') {
                like.addClass('active');
                dislike.removeClass('active');
            } else if (data.reaction === '0') {
                like.removeClass('active');
                dislike.addClass('active');
            } else {
                like.removeClass('active');
                dislike.removeClass('active');
            }
        }).fail(function (xhr) {
            divStatus.html('<p class="alert feedback-error">' + xhr.status + ' - Algo salió mal mientras se procesaba la reacción, intenta mas tarde.</p>');
        }).always(function (data) {
            if (!data.statusText) {
                const likes = $('img#total-likes-' + commentID), dislikes = $('img#total-dislikes-' + commentID);
                likes.get(0).nextSibling.nodeValue = ' ' + data.likes;
                dislikes.get(0).nextSibling.nodeValue = ' ' + data.dislikes;
            }
        });
    });

    /** Ajax Post Comment **/
    function ajaxPost(link, params) {
        divStatus.html('');
        container.css('opacity','0.5');
        loader.fadeIn();
        $(form).find(':input').attr('disabled', true).addClass('disabled');
        $.post(link, params).done(function(data) {
            divStatus.html('<p class="alert feedback-success">'+ data.success +'</p>');
            divStatus.find('p').delay(2000).fadeOut();
            container.html(data.view).fadeIn();
            $('span#comments-count').text(data.count);
        }).fail(function(xhr) {
            let errors = xhr.responseJSON ? xhr.responseJSON.errors : false;
            if (errors) {
                $.each(errors, function (key, value) {
                    divStatus.append('<p class="alert feedback-error">'+ value +'</p>');
                });
            } else {
                divStatus.html('<p class="alert feedback-error">'+ xhr.status + ' - Algo salió mal mientras se procesaba la acción, intenta mas tarde.</p>');
            }
        }).always(function(data) {
            loader.fadeOut(function() {
                $(form).find(':input').attr('disabled', false).removeClass('disabled');
                $(form).find('input[type=hidden]#parent_id').attr('value', '');
                $(form).resetForm();
            });
            container.css('opacity','1');
            if (data.comment_id) {
                const comment = container.find('p#'+data.comment_id);
                if (comment.length) {
                    $('html, body').animate({
                        scrollTop: comment.offset().top -250
                    });
                    const originalColor = comment.css('background-color');
                    comment.css('background-color','#FFF9C4');
                    setTimeout(function() {
                       comment.animate({
                           'background-color': originalColor
                       }, 500);
                    }, 1500);
                }
            } else {
                $('html, body').animate({
                    scrollTop: container.offset().top -250
                });
            }
        });
    }

    /**
     * Ajax Get
     * @param link
     */
    function ajaxGet(link) {
        divStatus.html('');
        container.hide();
        loader.fadeIn();
        $.get(link).done(function(data) {
            container.html(data);
        }).fail(function(xhr) {
            divStatus.html('<p class="alert feedback-error"><span>'+xhr.status+' - Algo salió mal mientras se cargaban los comentarios, <a href="location.reload();"> recargar página</a>.</span></p>');
        }).always(function() {
            loader.fadeOut(function() {
                container.fadeIn();
                $('html, body').animate({
                    scrollTop:container.offset().top -250
                });
            });
        });
    }
});
