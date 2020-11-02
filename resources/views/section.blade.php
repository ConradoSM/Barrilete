@extends('layouts.barrilete')
@section('title', 'Barrilete | '.ucfirst($articles->first()->section->name))
@section('article_title', $articles->first()->title)
@section('description', $articles->first()->article_desc)
@section('article_type', 'article')
@section('article_desc', $articles->first()->article_desc)
@section('article_url', route('section',['seccion'=>str_slug($articles->first()->section->name)]))
@section('article_photo', 'https://barrilete.com.ar/img/articles/.thumbs/'.$articles->first()->photo)
@section('site_name', 'Barrilete')
@section('created_at', $articles->first()->created_at)
@section('updated_at', $articles->first()->updated_at)
@section('article_section', $articles->first()->section->name)
@section('content')
    <div class="articles-index">
        @forelse ($articles as $article)
            <article class="pubIndex">
                @if ($article->video == 1)
                    <img class="video" alt="video" src="{{ asset('img/play-button.png') }}" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
                @endif
                <img class="lazy article-image" src="{{ asset('img/before-load.png') }}" data-src="{{$loop->iteration == 1 ? asset('img/articles/images/'.$article->photo) : asset('img/articles/.thumbs/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
                <a class="article-link" href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}">{{ $article->title }}</a>
                <span class="article-date">{{ ucfirst($article->created_at->diffForHumans()) }}</span>
            </article>
        @empty
            <h2>No hay artículos para mostrar</h2>
        @endforelse
    </div>
@endsection
