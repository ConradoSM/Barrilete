<h1>Preview del artículo</h1>
<p>así se verá el artículo cuando se publique</p>
<br />
<article class="pub-preview">
    <img src="{{ asset('img/articles/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" />
    <p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $article->date }}</p>
    <h2>{{ $article -> title }}</h2>
    <p class="copete">{{ $article->article_desc }}</p>
    <p class="info">
    <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $article->user->name }}
    <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $article->views }} lecturas
    </p>
    <hr />
    {!! $article->article_body !!}
</article>

