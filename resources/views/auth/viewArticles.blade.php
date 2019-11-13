@if (isset($success))
    <p class="alert-success"><img src="/svg/ajax-success.svg" alt="Exito"/>{{ $success }}</p>
@endif
<div id="user-articles-list">
    <h1>{{ ucfirst($status) }}</h1>
    <p>Se encontraron {{ $articles->total() }} {{ $status }}</p>
    @forelse ($articles as $article)
    <article class="searchResult">
        <p class="searchDate">
            @if ($article->status == 'DRAFT')
            <img src="{{ asset('svg/forbidden.svg') }}" title="No publicado" />
            @else
            <img src="{{ asset('svg/checked.svg') }}" title="Publicado" />
            @endif
            {{ $article->created_at->diffForHumans() }} · {{ $article->section ? ucfirst($article->section->name) : $status}} · {{ $article->views }} lecturas @if($status == 'encuestas') · {{ $article->option->sum('votes') }} votos @endif
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
        {{$articles->links()}}
</div>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
