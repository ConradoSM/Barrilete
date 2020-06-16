@foreach($comments as $comment)
    @include('comments.item', ['comment' => $comment])
@endforeach
@if(method_exists($comments,'links'))
    {{$comments->links()}}
@endif
<script>
    /** Comments Links Pagination **/
    $('a.page-link').on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const container = $('section.comments');
        const link = $(this).attr('href');
        $.get(link, {
            beforeSend: function () {
                container.html('<center><img class="loading" src="{{ asset("img/loader.gif") }}" /></center>');
            }
        }).done(function (data) {
            $('html, body').animate({scrollTop:container.offset().top -250});
            container.html(data);
        }).fail(function (xhr) {
            container.html('<p class="alert feedback-error">'+xhr.status+': '+xhr.statusText+' - <a href="location.reload();">Recargar página</a></p>');
        });
    });

    /** Comments Box Functionality **/
    $('img.delete').hide();
    $('img.edit').hide();
    $('div.comment-container').find('p.comment').mouseover(function() {
        const buttonDelete = $(this).find('img.delete');
        const buttonEdit = $(this).find('img.edit');
        buttonEdit.show();
        buttonDelete.show();
        $(this).mouseleave(function () {
            buttonDelete.hide();
            buttonEdit.hide();
        });
    });
</script>
