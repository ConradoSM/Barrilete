@extends('layouts.barrilete')
@section('title', $article->titulo)
@section('content')
<div class="pubContainer">
<article class="pub">
    <img src="{{ asset('img/articles/'.$article->foto) }}" title="{{ $article->titulo }}" alt="{{ $article->titulo }}" />
    <p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $article->fecha }}</p>
    <h2>{{ $article -> titulo }}</h2>
    <p class="copete">{{ $article->copete }}</p>
    <p class="info">
    <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $article->autor }}
    <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $article->visitas }} lecturas
    </p>
    <hr />
    {!! $article->cuerpo !!}
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
        <a href="{{ route('article', ['id' => $more -> id, 'seccion' => str_slug($more -> seccion), 'titulo' => str_slug($more -> titulo, '-')]) }}">{{ $more -> titulo }}</a>
        <img src="{{ route('imgSecond', ['image' => $more -> foto]) }}" title="{{ $more -> titulo }}" alt="{{ $more -> titulo }}" />   
    </article> 
    @empty
    @endforelse
</aside>
</div>
@endsection