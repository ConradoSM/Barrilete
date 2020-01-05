@foreach($comments as $comment)
    @include('comments.item', ['comment' => $comment])
@endforeach
@if(method_exists($comments,'links'))
    {{$comments->links()}}
@endif
<script>
    /**
     * Comments Links Pagination
     */
    $('a.page-link').on('click', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const container = $('section.comments');
        const link = $(this).attr('href');
        $.get({
            beforeSend: function () {
                container.html('<center><img class="loading" src="{{ asset("img/loader.gif") }}" /></center>');
            },
            type: 'GET',
            url: link,
            async: true
        }).done(function (data) {
            $('html, body').animate({scrollTop:container.offset().top -250});
            container.html(data);
        }).fail(function (xhr) {
            container.html('<p class="alert feedback-error">'+xhr.status+': '+xhr.statusText+' - <a href="location.reload();">Recargar página</a></p>');
        });
    });
</script>
