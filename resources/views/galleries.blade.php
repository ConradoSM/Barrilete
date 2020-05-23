@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('description', 'Galerías de fotos')
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
@forelse ($galleries as $galeria)
    <article class="pubIndex">
        <img src="{{ asset('img/before-load.png') }}" data-src="{{$loop->iteration == 1 ? asset('img/galleries/images/'.$galeria->photos->first()->photo) : asset('img/galleries/.thumbs/'.$galeria->photos->first()->photo)}}" title="{{$galeria->title}}" alt="{{$galeria->title}}" class="lazy" onclick="location.href='{{route('gallery',['id'=>$galeria->id,'title'=>str_slug($galeria->title,'-')])}}'" />
        <a href="{{route('gallery',['id'=>$galeria->id,'title'=>str_slug($galeria->title,'-')])}}">{{$galeria->title}}</a>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@endsection
