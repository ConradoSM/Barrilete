@extends('layout')
@section('title', $article -> first()-> titulo)
@section('content')
@forelse ($article as $pub)
<div class="pubContainer">
<article class="pub">
    <img src="{{ asset('img/articles/'.$pub -> foto) }}" title="{{ $pub -> titulo }}" alt="{{ $pub -> titulo }}" />
    
    <h2>{{ $pub -> titulo }}</h2>
    <p class="copete">{{ $pub -> copete }}</p>
    <p class="info">
    <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $pub -> autor }}
    <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $pub -> visitas }} lecturas
    <img class="svg" src="{{ asset('svg/clock-circular-outline.svg') }}" /> {{ $pub -> fecha }}
    </p>
    <hr />
    {!! $pub -> cuerpo !!}
    <hr />
</article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
<aside class="pubSeccion">
    @forelse ($moreArticles as $more)
    <article>
        <a href="{{ route('article', ['id' => $more -> id, 'seccion' => str_slug($more -> seccion), 'titulo' => str_slug($more -> titulo, '-')]) }}">{{ $more -> titulo }}</a>
        <img src="{{ route('imgSecond', ['image' => $more -> foto]) }}" title="{{ $more -> titulo }}" alt="{{ $more -> titulo }}" />   
    </article> 
    @empty
    @endforelse
</aside>
</div>
@endsection
