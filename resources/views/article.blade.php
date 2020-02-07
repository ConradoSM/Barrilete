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
<meta name="_token" content="{{ csrf_token() }}">
@section('content')
<div class="pubContainer">
<article class="pub translate">
    <img src="{{ asset('svg/placeholder.svg') }}" class="placeholder"/>
    <img src="{{ asset('img/before-load.png') }}" data-src="{{ asset('img/articles/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" class="lazy" />
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
    <h2>Comentarios ( {{ $article->comments($article->section_id)->count() }} )</h2>
    <div id="status"></div>
    <section class="comments"></section>
    @include('comments.form')
</article>
<aside class="pubSeccion">
    @forelse ($moreArticles as $more)
    <article class="pubArticle translate">
        <img src="{{asset('svg/placeholder.svg')}}" class="placeholder"/>
        @if ($more->video == 1)
            <img src="{{ asset('img/play-button.png') }}" class="video" onclick="location.href='{{ route('article',['id'=>$more->id,'section'=>str_slug($more->section->name),'title'=>str_slug($more->title,'-')]) }}'" />
        @endif
        <img src="{{ asset('img/before-load.png') }}" data-src="{{ asset('img/articles/.thumbs/images/'.$more->photo) }}" title="{{ $more->title }}" alt="{{ $more->title }}" class="lazy" onclick="location.href='{{ route('article', ['id' => $more->id, 'section' => str_slug($article->section->name), 'title' => str_slug($more->title, '-')]) }}'" />
        <a href="{{ route('article', ['id' => $more -> id, 'section' => str_slug($article->section->name), 'title' => str_slug($more->title, '-')]) }}">{{ $more->title }}</a>
    </article>
    @empty
    @endforelse
</aside>
</div>
<script>
    /**
     * Load Comments Box
     */
    $(document).ready(function()
    {
        const link = '{{URL::route('getComments',['article_id' => $article->id, 'section_id' => $article->section_id], false) }}';
        return getComments(link);
    });
</script>
@endsection
