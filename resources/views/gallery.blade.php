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
<div class="pub-container">
<article class="pub gallery">
    <h1>{{ $article -> title }}</h1>
    <p>{{ $article->article_desc }}</p>
    <div class="info">
        <img alt="Fecha" class="icon" src="{{ asset('svg/calendar.svg') }}" /><span>{{ ucfirst($article->created_at->diffForHumans()) }}</span>
        <img alt="Autor" class="icon" src="{{ asset('svg/user_black.svg') }}" /><span>{{ $article->user->name }}</span>
        <img alt="Visitas" class="icon" src="{{ asset('svg/eye.svg') }}" /><span>{{ $article->views }}</span>
        @php($userReaction = Auth::user() ? Auth::user()->articleReaction($article->id, $article->section->id)->first() : null)
        @if($userReaction)
            @if($userReaction->reaction == 1)
                <img alt="Me gusta" title="Me gusta" class="icon article-reaction" id="like" src="{{asset('svg/like-active.svg')}}" data-reaction="1" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" /><span>{{$article->reactions($article->section_id, '1')->count()}}</span>
                <img alt="No me gusta" title="No me gusta" class="icon article-reaction dislike" id="dislike" src="{{asset('svg/article-reaction.svg')}}" data-reaction="0" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" /><span>{{$article->reactions($article->section_id, '0')->count()}}</span>
            @elseif($userReaction->reaction == 0)
                <img alt="Me gusta" title="Me gusta" class="icon article-reaction" id="like" src="{{asset('svg/article-reaction.svg')}}" data-reaction="1" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" /><span>{{$article->reactions($article->section_id, '1')->count()}}</span>
                <img alt="No me gusta" title="No me gusta" class="icon article-reaction dislike" id="dislike" src="{{asset('svg/like-active.svg')}}" data-reaction="0" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" /><span>{{$article->reactions($article->section_id, '0')->count()}}</span>
            @endif
        @else
            <img alt="Me gusta" title="Me gusta" class="icon article-reaction" id="like" src="{{asset('svg/article-reaction.svg')}}" data-reaction="1" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" /><span>{{$article->reactions($article->section_id, '1')->count()}}</span>
            <img alt="No me gusta" title="No me gusta" class="icon article-reaction dislike" id="dislike" src="{{asset('svg/article-reaction.svg')}}" data-reaction="0" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" /><span>{{$article->reactions($article->section_id, '0')->count()}}</span>
        @endif
    </div>
@forelse ($photos as $photo)
<article class="photos">
    <img src="{{ asset('img/before-load.png') }}" data-src="{{ asset('img/galleries/images/'.$photo->photo)}}" class="lazy" title="{{$photo->title}}" />
    <p>{{$photo->title}}</p>
</article>
@empty
    <h2>No hay fotos</h2>
@endforelse
    <!-- Start Comments -->
    <h2><span id="comments-count" data-url="{{URL::route('getComments',['article_id' => $article->id, 'section_id' => $article->section_id], false) }}">{{ $article->comments($article->section_id)->count() }}</span> Comentarios</h2>
    <div id="comment">
        <img alt="loader" class="loader" src="{{asset('svg/loader.svg')}}" />
        <div id="status"></div>
        <section class="comments"></section>
    </div>
    @include('comments.form')
    <script type="text/javascript" src="{{ asset('js/comments.js') }}"></script>
    <!-- End Comments -->
</article>
<script type="text/javascript" src="{{asset('js/article-reaction.js')}}"></script>
@endsection
