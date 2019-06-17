@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('description', 'Galerías de fotos')
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
@forelse ($galleries as $galeria)
    <article class="pubIndex">
        @if ($loop->iteration == 1)
        <img data-src="{{asset('img/galleries/'.$galeria->photos->first()->photo)}}" title="{{$galeria->title}}" alt="{{$galeria->title}}" class="lazy" onclick="location.href='{{route('gallery',['id'=>$galeria->id,'titulo'=>str_slug($galeria->title,'-')])}}'" />
        @else
        <img data-src="{{asset('img/galleries/.thumbs/'.$galeria->photos->first()->photo)}}" title="{{$galeria->title}}" alt="{{$galeria->title}}" class="lazy" onclick="location.href='{{route('gallery',['id'=>$galeria->id,'titulo'=>str_slug($galeria->title,'-')])}}'" />
        @endif
        <a href="{{route('gallery',['id'=>$galeria->id,'titulo'=>str_slug($galeria->title,'-')])}}">{{$galeria->title}}</a>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@endsection

