@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('description', 'Galerías de fotos')
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
@forelse ($galleries as $gallery)
    <article class="pubIndex">
        <img class="lazy article-image" src="{{ asset('img/before-load.png') }}" data-src="{{$loop->iteration == 1 ? asset('img/galleries/images/'.$gallery->photos->first()->photo) : asset('img/galleries/.thumbs/'.$gallery->photos->first()->photo)}}" title="{{$gallery->title}}" alt="{{$gallery->title}}" onclick="location.href='{{route('gallery',['id'=>$gallery->id,'title'=>str_slug($gallery->title,'-')])}}'" />
        <a class="article-link" href="{{route('gallery',['id'=>$gallery->id,'title'=>str_slug($gallery->title,'-')])}}">{{$gallery->title}}</a>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@endsection
