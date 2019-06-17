@extends('layouts.barrilete')
@section('title', 'Error 500')
@section('content')
<article class="pub">
    <h1>Error 500 - Internal Server Error</h1>
    <h2>El servidor encuentra una condición inesperada que le impide cumplir la solicitud que realizó el cliente</h2>
    <ul>
        <li>El error puede ser resultado del mantenimiento al sitio web.</li>
        <li>Un error de programación.</li>
        <li>Un conflicto en los plugins del sitio.</li>
    </ul>
    <br />
    <a href="{{ route('default') }}">Volver al inicio</a>
</article>
<img class="errorIMG" src="{{asset('img/error.gif')}}" />
@endsection