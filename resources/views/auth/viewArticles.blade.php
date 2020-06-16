@if (isset($success))
    <p class="alert feedback-success">{{ $success }}</p>
@endif
<h1>{{ ucfirst($status) }}</h1>
<p>Se encontraron {{ $articles->total() }} {{ $status }}</p>
@forelse ($articles as $article)
<fieldset>
    <p class="article-status">
        @if ($article->status == 'DRAFT')
        <img src="{{ asset('svg/forbidden.svg') }}" title="No publicado" alt="No publicado" />
        @else
        <img src="{{ asset('svg/checked.svg') }}" title="Publicado" alt="Publicado" />
        @endif
        {{ $article->created_at->diffForHumans() }} · {{ $article->section ? ucfirst($article->section->name) : $status}} · {{ $article->views }} lecturas @if($status == 'encuestas') · {{ $article->option->sum('votes') }} votos @endif
    </p>
    @if ($status == 'artículos')
    <h3><a href="{{ route('previewArticle', ['id'=>$article->id]) }}">{{ $article->title }}</a></h3>
    @elseif ($status == 'galerías')
    <h3><a href="{{ route('previewGallery', ['id'=>$article->id]) }}">{{ $article->title }}</a></h3>
    @elseif ($status == 'encuestas')
    <h3><a href="{{ route('previewPoll', ['id'=>$article->id]) }}">{{ $article->title }}</a></h3>
    @endif
    <p>{{ $article->article_desc }}</p>
</fieldset>
@empty
@endforelse
    {{$articles->links()}}
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
