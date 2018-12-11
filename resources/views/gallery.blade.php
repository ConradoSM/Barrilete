@extends('layout')
@section('title', $gallery -> first()-> titulo)
@section('content')
@forelse ($gallery as $pub)
<div class="pubContainer">
<article class="pub_galeria">
    <h2>{{ $pub -> titulo }}</h2>
    <p class="copete">{{ $pub -> copete }}</p>
    <p class="info">
    <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $pub -> autor }}
    <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $pub -> visitas }} lecturas
    <img class="svg" src="{{ asset('svg/clock-circular-outline.svg') }}" /> {{ $pub -> fecha }}
    </p>
    <hr />
</article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
@forelse ($fotos as $foto)
<article class="fotos">
    <p>{{ $foto -> titulo_foto }}</p>
    <img src="{{ asset('img/articles/'.$foto -> foto) }}" />
    <p>{{ $foto -> descripcion_foto }}</p>
</article>
<hr />
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
</div>
@endsection
