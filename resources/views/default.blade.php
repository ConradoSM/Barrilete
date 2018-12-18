@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('content')
@php ($i=0) @endphp
@forelse ($articlesIndex as $article)
@php ($i++) @endphp
<article class="pubIndex">
    <div class="seccion" onclick="location.href ='{{route('section',['seccion'=>str_slug($article->section)])}}'">{{$article->section}}</div>
    @if ($article->video == 1)<img src="img/play-button.png" class="video" />
    @endif
    @if ($i == 1)
    <img src="img/articles/{{$article->photo}}" title="{{$article->title}}" alt="{{$article->title}}" />
    @else
    <img src="{{route('imgFirst',['image'=>$article->photo])}}" title="{{$article->title}}" alt="{{$article->title}}" />
    @endif
    <a href="{{route('article',['id'=>$article->id,'seccion'=>str_slug($article->section),'titulo'=>str_slug($article->title,'-')])}}">{{$article->title}}</a>
    <p>{{$article->article_desc}}</p>
</article>
@empty
<h1>No hay artículos para mostrar</h1>
@endforelse
<hr />
<div class="galeriasContainerIndex">
    <h1>Galerías de fotos</h1>
    @if ($galleryIndex)
        <article class="galeriaIndex">
            <img src="{{route('imgSecond',['image'=>$galleryIndex->photo])}}" title="{{$galleryIndex->title}}" alt="{{$galleryIndex->title}}" />
            <a href="{{route('gallery',['id'=>$galleryIndex->id,'titulo'=>str_slug($galleryIndex->title,'-')])}}">{{$galleryIndex->title}}</a>       
        </article>
    @else
    <h2>No hay galerías</h2>
    @endif
</div>
<div class="pollsContainerIndex">
    <h1>Últimas encuestas</h1>
    @forelse ($pollsIndex as $pollIndex)
    <article class="pollIndex">
        <p>{{$pollIndex->date}}</p>
        <a href="{{route('poll',['id'=>$pollIndex->id,'titulo'=>str_slug($pollIndex->title,'-')])}}">{{$pollIndex->title}}</a>
    </article>
    @empty
    <h2>No hay encuestas</h2>
    @endforelse
</div>
@endsection

