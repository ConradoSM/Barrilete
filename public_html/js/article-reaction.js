$(document).ready(function() {
    $('img.article-reaction').on('click', function() {
        const reactionActive = window.location.origin+'/svg/like-active.svg',
            reactionInactive = window.location.origin+'/svg/article-reaction.svg',
            userID = $(this).data('user'),
            articleID = $(this).data('article'),
            sectionID = $(this).data('section'),
            userReaction = $(this).data('reaction');

        $.post('/article/reaction/save', {
            _token: $('meta[name="_token"]').attr('content'),
            user_id: userID,
            article_id: articleID,
            section_id: sectionID,
            user_reaction: userReaction
        }).done(function(data) {
            if (!data.error) {
                const like = $('img#like'), dislike = $('img#dislike');
                like.get(0).nextSibling.nodeValue = ' ' + data.likes;
                dislike.get(0).nextSibling.nodeValue = ' ' + data.dislikes;
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
                    content: data.error + ' <a href="/login">Ingresa</a> o <a href="/register">registrate</a>',
                    type: 'blue',
                    boxWidth: '55%',
                    useBootstrap: false,
                    buttons: {
                        OK: {
                            btnClass: 'button primary'
                        }
                    }
                });
            }
        }).fail(function(xhr) {
            $.alert({
                title: 'Error',
                content: xhr.status + ' - Ha ocurrido un error, por favor intenta mas tarde.',
                type: 'red',
                boxWidth: '55%',
                useBootstrap: false,
                buttons: {
                    OK: {
                        btnClass: 'button danger'
                    }
                }
            });
        });
    });
});
