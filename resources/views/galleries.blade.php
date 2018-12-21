@extends('layouts.barrilete')
@section('title', 'Barrilete')
@section('description', 'Galerías de fotos')
@section('keywords', 'secciones, noticias, economía, editoriales, internacionales, galerías de fotos, tecnología, política, sociedad, encuestas, deportes, cultura')
@section('content')
@php ($i=0) @endphp
@forelse ($galleries as $galeria)
@php ($i++) @endphp
    <article class="pubIndex">
        @if ($i == 1)
        <img src="{{asset('img/articles/'.$galeria->photos->first()->photo)}}" title="{{$galeria->title}}" alt="{{$galeria->title}}" />
        @else
        <img src="{{route('imgFirst',['image'=>$galeria->photos->first()->photo])}}" title="{{$galeria->title}}" alt="{{$galeria->title}}" />
        @endif
        <a href="{{route('gallery',['id'=>$galeria->id,'titulo'=>str_slug($galeria->title,'-')])}}">{{$galeria->title}}</a>
        <p>{{$galeria -> article_desc}}</p>
    </article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@endsection

