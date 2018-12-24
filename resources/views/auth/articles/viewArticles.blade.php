<h1>Mis {{ $status }}</h1>
<p>se encontraron {{ $Articles->count() }} {{ $status }}</p>
@forelse ($Articles as $article)
<article class="user-article">
    <p class="date">{{ $article->date }}</p>
    <a href="#">{{ $article->title }}</a>
    <p class="desc">{{ $article->article_desc }}</p>
</article>
@empty
@endforelse