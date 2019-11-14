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
        <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$gallery->views}}
    </p>
    <hr />
@forelse ($photos as $photo)
<article class="fotos translate">
    <img src="{{asset('svg/placeholder.svg')}}" class="placeholder"/>
    <img src="{{ asset('img/before-load.png') }}" data-src="{{ asset('img/galleries/'.$photo->photo)}}" class="lazy" title="{{$photo->title}}" />
    <p>{{$photo->title}}</p>
</article>
@empty
    <h2>No hay fotos</h2>
@endforelse
<div id="disqus_thread"></div>
<script>
var disqus_config = function () {
this.page.url = '{{ Request::url() }}';
this.page.identifier = '{{ Request::url() }}';
};

(function() {
var d = document, s = d.createElement('script');
s.src = 'https://barrilete.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
</article>
@endsection
