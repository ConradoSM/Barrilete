@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('description', 'Secciones de noticias, galerías de fotos, encuestas, toda la actualidad en un solo sitio')
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
@forelse ($articlesIndex as $article)   
    <article class="pubIndex">
        <div class="seccion" onclick="location.href ='{{ route('section',['seccion'=>str_slug($article->section->name)]) }}'">{{ $article->section->name }}</div>
        @if ($article->video == 1)<img src="{{ asset('img/play-button.png') }}" class="video" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
        @endif
        @if ($loop->iteration == 1)
        <img data-src="{{ asset('img/articles/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" class="lazy" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
        @else
        <img data-src="{{ asset('img/articles/.thumbs/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" class="lazy" onclick="location.href='{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}'" />
        @endif
        <a href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}">{{ $article->title }}</a>
    </article>
@empty
<h1>No hay artículos para mostrar</h1>
@endforelse
<div class="galeriasContainerIndex">    
    @forelse ($galleryIndex as $gallery)
    <img src="{{ asset('svg/photo-camera.svg') }}" title="Galería de fotos" class="camera" />
        <article class="galeriaIndex">
            <img data-src="{{ asset('img/galleries/.thumbs/'.$gallery->photos->first()->photo) }}" title="{{ $gallery->title }}" alt="{{ $gallery->title }}" class="lazy" />
            <a href="{{ route('gallery',['id'=>$gallery->id,'titulo'=>str_slug($gallery->title,'-')]) }}">{{ $gallery->title }}</a>       
        </article>
    @empty
    <h2>No hay galerías</h2>
    @endforelse
</div>
<div class="pollsContainerIndex">
    <h1>Últimas encuestas</h1>
    @forelse ($pollsIndex as $pollIndex)
    <article class="pollIndex">
        <p>{{ ucfirst($pollIndex->created_at->diffForHumans()) }}</p>
        <a href="{{ route('poll',['id'=>$pollIndex->id,'titulo'=>str_slug($pollIndex->title,'-')]) }}">{{ $pollIndex->title }}</a>
    </article>
    @empty
    <h2>No hay encuestas</h2>
    @endforelse
</div>
@endsection