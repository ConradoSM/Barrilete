<div id="user-articles-list">
    <h1>Mis {{ $status }}</h1>
    <p>Se encontraron {{ $Articles->total() }} {{ $status }}</p>
    @forelse ($Articles as $article)
    <article class="searchResult">
        <p class="searchDate">
            @if ($article->status == 'DRAFT')
            <img src="{{ asset('svg/forbidden.svg') }}" title="No publicado" />
            @else
            <img src="{{ asset('svg/checked.svg') }}" title="Publicado" />
            @endif
            {{ $article->created_at->diffForHumans() }}
        </p>
        @if ($status == 'artículos')
        <a class="searchTitle" href="{{ route('previewArticle', ['id'=>$article->id]) }}">{{ $article->title }}</a>
        @elseif ($status == 'galerías')
        <a class="searchTitle" href="{{ route('previewGallery', ['id'=>$article->id]) }}">{{ $article->title }}</a>
        @elseif ($status == 'encuestas')
        <a class="searchTitle" href="{{ route('previewPoll', ['id'=>$article->id]) }}">{{ $article->title }}</a>
        @endif
        <p class="searchCopete">{{ $article->article_desc }}</p>
    </article>
    @empty
    @endforelse
        {{$Articles->links()}}
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $('div#user-articles-list a').each(function () {

            var href = $(this).attr('href');
            $(this).attr({href: '#'});

            $(this).click(function () {
                $('#user-articles-list').fadeOut('fast', 'linear', function () {
                    $('#loader').fadeIn('fast', 'linear', function () {
                        $('#loader').fadeOut('fast', 'linear', function () {
                            $('#user-content').fadeOut('fast', 'linear', function () {
                                $('#user-content').load(href, function () {
                                    $('#user-content').fadeIn('fast', 'linear');
                                });
                            });
                        });
                    });
                });
            });
        });
    });
</script>