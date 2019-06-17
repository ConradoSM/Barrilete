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
    <h1>{{ $article -> title }}</h1>
    <p class="copete">{{ $article->article_desc }}</p>
    <p class="info">
        <img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $article->created_at->diffForHumans() }}
        <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $article->user->name }}
        <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $article->views }}
    </p>
    <hr />
    {!! $article->article_body !!}
    <hr />
    <div id="disqus_thread"></div>
    <script>
    
    var disqus_config = function () {
    this.page.url = '{{ Request::url() }}';
    this.page.identifier = '{{ Request::url() }}'; 
    };
    
    (function() { 
    var d = document, s = d.createElement('script');
    s.src = 'https://barrilete.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
</article>
<aside class="pubSeccion">
    @forelse ($moreArticles as $more)
    <article class="pubArticle">
        <img data-src="/img/articles/.thumbs/images/{{ $more -> photo }}" title="{{ $more -> title }}" alt="{{ $more -> title }}" class="lazy" onclick="location.href='{{ route('article', ['id' => $more -> id, 'section' => str_slug($article->section->name), 'title' => str_slug($more -> title, '-')]) }}'" />   
        <a href="{{ route('article', ['id' => $more -> id, 'section' => str_slug($article->section->name), 'title' => str_slug($more -> title, '-')]) }}">{{ $more -> title }}</a>
    </article> 
    @empty
    @endforelse
</aside>
</div>
@endsection