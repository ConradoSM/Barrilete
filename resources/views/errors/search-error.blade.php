@extends('layouts.barrilete')
@section('title','Error: la búsqueda produjo un error')
@section('content')
<article class="pub">
    <h1>Error en la búsqueda</h1>
    <h2>El servidor no pudo procesar la búsqueda solicitada por el cliente. Esto puede deberse a que:</h2>
    <hr />
    <ul>
        <li>El cliente escribió mal la URL.</li>
        <li>La estructura de enlaces permanentes del sitio ha sido cambiada, por ejemplo, cuando un sitio ha sido trasladado a otro servidor web y el DNS todavía apunta a la ubicación anterior.</li>
        <li>La página web solicitada no está disponible temporalmente, pero puede intentarlo de nuevo más tarde.</li>
        <li>Se eliminó definitivamente la página web.</li>
    </ul>
    <br />
    <a href="{{ route('default') }}">Volver al inicio</a>
</article>
<img class="errorIMG" src="{{asset('img/error.gif')}}" />
@endsection