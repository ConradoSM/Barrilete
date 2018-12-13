@extends('layout')
@section('title', $gallery->titulo)
@section('content')
<div class="pubContainer">
<article class="pub_galeria">
    <p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $gallery->fecha }}</p>
    <h2>{{ $gallery->titulo }}</h2>
    <p class="copete">{{ $gallery->copete }}</p>
    <p class="info">
    <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $gallery->autor }}
    <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $gallery->visitas }} lecturas
    </p>
    <hr />
</article>
@forelse ($fotos as $foto)
<article class="fotos">
    <p>{{ $foto->titulo_foto }}</p>
    <img src="{{ asset('img/articles/'.$foto->foto) }}" />
    <p>{{ $foto->descripcion_foto }}</p>
</article>
@empty
    <h1>No hay fotos</h1>
@endforelse
<div class="fb-comments" data-href="{{url()->current()}}" data-width="100%" data-numposts="5"></div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v3.2';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</div>
@endsection