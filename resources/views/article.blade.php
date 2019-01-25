@extends('layouts.barrilete')
@section('title', $article->title)
@section('description', $article->article_desc)
@section('article_title', $article->title)
@section('article_type', 'article')
@section('article_desc', $article->article_desc)
@section('article_url', route('article', ['id' => $article->id, 'section' => str_slug($article->section->name), 'title' => str_slug($article->title, '-')]))
@section('article_photo', 'https://barrilete.com.ar/img/articles/.thumbs/images/'.$article->photo)
@section('site_name', 'Barrilete')
@section('created_at', $article->created_at)
@section('updated_at', $article->updated_at)
@section('article_section', $article->section->name)
@section('content')
<div class="pubContainer">
<article class="pub">
    <img data-src="{{ asset('img/articles/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" class="lazy" />
    <h2>{{ $article -> title }}</h2>
    <p class="copete">{{ $article->article_desc }}</p>
    <p class="info">
        <img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $article->created_at->diffForHumans() }}
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
        <img data-src="/img/articles/.thumbs/images/{{ $more -> photo }}" title="{{ $more -> title }}" alt="{{ $more -> title }}" class="lazy" onclick="location.href='{{ route('article', ['id' => $more -> id, 'section' => str_slug($article->section->name), 'title' => str_slug($more -> title, '-')]) }}'" />   
    </article> 
    @empty
    @endforelse
</aside>
</div>
@endsection