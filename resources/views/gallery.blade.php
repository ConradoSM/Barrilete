@extends('layouts.barrilete')
@section('title', $gallery->title)
@section('description', $gallery->article_desc)
@section('article_title', $gallery->title)
@section('article_type', 'gallery')
@section('article_desc', $gallery->article_desc)
@section('article_url', route('gallery', ['id' => $gallery->id, 'title' => str_slug($gallery->title, '-')]))
@section('article_photo', 'https://barrilete.com.ar/img/galleries/.thumbs/'.$photos->first()->photo)
@section('site_name', 'Barrilete')
@section('created_at', $gallery->created_at)
@section('updated_at', $gallery->updated_at)
@section('article_section', $gallery->section->name)
@section('content')
<div class="pubContainer">
<article class="pub_galeria">
    <h1>{{$gallery->title}}</h1>
    <p class="copete">{{$gallery->article_desc}}</p>
    <p class="info">
        <img class="svg" src="{{asset('svg/calendar.svg')}}" /> {{$gallery->created_at->diffForHumans()}}
        <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$gallery->user->name}}
        <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$gallery->views}} lecturas
    </p>
    <hr />
</article>
@forelse ($photos as $photo)
<article class="fotos">
    <img data-src="{{ asset('img/galleries/'.$photo->photo)}}" class="lazy" title="{{$photo->title}}" />
    <p>{{$photo->title}}</p>
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