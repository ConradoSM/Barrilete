@extends('layouts.barrilete')
@section('title', 'Buscador')
@section('content')
@php 
$query = Request::get('query')
@endphp
<div class="searchResultContainer">
    <p class="searchFilter">
        <a class="{{ Request::get('sec') == 'articulos' ? 'active' : 'searchFilterLink' }}" href="{{route('search',['query'=>$query,'sec'=>'articulos'])}}">Artículos</a>
        <a class="{{ Request::get('sec') == 'galerias' ? 'active' : 'searchFilterLink' }}" href="{{route('search',['query'=>$query,'sec'=>'galerias'])}}">Galerías</a>
        <a class="{{ Request::get('sec') == 'encuestas' ? 'active' : 'searchFilterLink' }}" href="{{route('search',['query'=>$query,'sec'=>'encuestas'])}}">Encuestas</a>
    </p>
    <p class="searchInfo">Se encontraron {{$resultado->total()}} resultados para la búsqueda: <b>{{$query}}</b></p>
    @forelse ($resultado as $pub)
    <article class="searchResult">
        <p class="searchDate">{{$pub->created_at->diffForHumans()}}</p>
        @if (Request::get('sec') == 'articulos')
        <a class="searchTitle" href="{{route('article',['id'=>$pub->id,'section'=>str_slug($pub->section->name),'title'=>str_slug($pub->title,'-')])}}">{{$pub->title}}</a>
        @elseif (Request::get('sec') == 'galerias')
        <a class="searchTitle" href="{{route('gallery',['id'=>$pub->id,'title'=>str_slug($pub->title,'-')])}}">{{$pub->title}}</a>
        @elseif (Request::get('sec') == 'encuestas')
        <a class="searchTitle" href="{{route('poll',['id'=>$pub->id,'title'=>str_slug($pub->title,'-')])}}">{{$pub->title}}</a>
        @endif
        <p class="searchCopete">{{$pub->article_desc}}</p>
    </article>
    @empty
    <hr />
    <p>No hay artículos para mostrar</p>
    @endforelse
    {{$resultado->links()}}
</div>
@endsection