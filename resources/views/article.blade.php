@extends('layouts.barrilete')
@section('title', $article->title)
@section('description', $article->article_desc)
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
<div class="pubContainer">
<article class="pub">
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
    <hr />
<div class="fb-comments" data-href="{{url()->current()}}" data-width="100%" data-numposts="5"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v3.2';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</article>
<aside class="pubSeccion">
    @forelse ($moreArticles as $more)
    <article class="pubArticle">
        <a href="{{ route('article', ['id' => $more -> id, 'section' => str_slug($article->section->name), 'title' => str_slug($more -> title, '-')]) }}">{{ $more -> title }}</a>
        <img src="/img/articles/.thumbs/images/{{ $more -> photo }}" title="{{ $more -> title }}" alt="{{ $more -> title }}" />   
    </article> 
    @empty
    @endforelse
</aside>
</div>
@endsection