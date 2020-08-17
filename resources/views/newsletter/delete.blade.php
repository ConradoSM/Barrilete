@extends('layouts.barrilete')
@section('title', 'Boletín Informativo')
@section('site_name', 'Barrilete')
@section('content')
<div class="pub-container">
    <article>
        <h2>Boletín Informativo</h2>
        <p class="alert feedback-{{$response['type']}}">{{$response['message']}}</p>
        <a href="{{route('default')}}">Ir al inicio</a>
    </article>
</div>
@endsection
