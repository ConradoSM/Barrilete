$(document).ready(function() {
    $('img.article-reaction').on('click', function() {
        const reactionActive = window.location.origin+'/svg/like-active.svg',
            reactionInactive = window.location.origin+'/svg/article-reaction.svg',
            userID = $(this).data('user'),
            articleID = $(this).data('article'),
            sectionID = $(this).data('section'),
            userReaction = $(this).data('reaction'),
            screenWidth = $( window ).width() < 767.98 ? '90%' : '55%';

        $.post('/article/reaction/save', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            user_id: userID,
            article_id: articleID,
            section_id: sectionID,
            user_reaction: userReaction
        }).done(function(data) {
            if (!data.error) {
                const like = $('img#like'), dislike = $('img#dislike');
                like.next().text(data.likes);
                dislike.next().text(data.dislikes);
                if (data.reaction === '1') {
                    like.attr('src', reactionActive);
                    dislike.attr('src', reactionInactive);
                } else if (data.reaction === null) {
                    like.attr('src', reactionInactive);
                    dislike.attr('src', reactionInactive);
                } else {
                    like.attr('src', reactionInactive);
                    dislike.attr('src', reactionActive);
                }
            } else {
                $.alert({
                    title: 'Importante',
                    content: '<p class="alert feedback-warning"><span>'+data.error + ' <a href="/login">Ingresa</a> o <a href="/register">registrate</a></span></p>',
                    closeIcon: true,
                    boxWidth: screenWidth,
                    useBootstrap: false,
                    type: 'dark',
                    buttons: {
                        Cerrar: {
                            btnClass: 'button small primary'
                        }
                    }
                });
            }
        }).fail(function(xhr) {
            $.alert({
                title: 'Error',
                content: '<p class="alert feedback-error">'+xhr.status + ' - Ha ocurrido un error, por favor intenta mas tarde.</p>',
                closeIcon: true,
                boxWidth: screenWidth,
                useBootstrap: false,
                type: 'dark',
                buttons: {
                    Cerrar: {
                        btnClass: 'button small danger'
                    }
                }
            });
        });
    });
});
