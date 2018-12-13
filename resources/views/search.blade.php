@extends('layout')
@section('title', 'Buscador')
@section('content')
@forelse ($resultado as $pub)
<div class="pubContainer">
<article class="pub">
    <p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $pub -> fecha }}</p>
    <h2>{{ $pub -> titulo }}</h2>
    <p class="copete">{{ $pub -> copete }}</p>
    <hr />
</article>
@empty
    <h1>No hay artículos para mostrar</h1>
@endforelse
{{$resultado->links()}}
</div>
@endsection