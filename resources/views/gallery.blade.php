@extends('layouts.barrilete')
@section('title', $article->title)
@section('description', $article->article_desc)
@section('article_title', $article->title)
@section('article_type', 'gallery')
@section('article_desc', $article->article_desc)
@section('article_url', route('gallery', ['id' => $article->id, 'title' => str_slug($article->title, '-')]))
@section('article_photo', 'https://barrilete.com.ar/img/galleries/.thumbs/'.$photos->first()->photo)
@section('site_name', 'Barrilete')
@section('created_at', $article->created_at)
@section('updated_at', $article->updated_at)
@section('article_section', $article->section->name)
<meta name="_token" content="{{ csrf_token() }}">
@section('content')
<div class="pubContainer">
<article class="pub_galeria">
    <h1>{{$article->title}}</h1>
    <p class="copete">{{$article->article_desc}}</p>
    <p class="info">
        <img class="svg" src="{{asset('svg/calendar.svg')}}" /> {{$article->created_at->diffForHumans()}}
        <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$article->user->name}}
        <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$article->views}}
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
    <hr />
    <h2>Comentarios ( {{ $article->comments->count() }} )</h2>
    <div id="status"></div>
    <section class="comments"></section>
    @include('comments.form')
    <script>
        /**
         * Load Comments Box
         */
        $(document).ready(function()
        {
            const link = '{{URL::route('getComments',['article_id' => $article->id, 'section_id' => $article->section_id], false) }}';
            return getComments(link);
        });
    </script>
</article>
@endsection
