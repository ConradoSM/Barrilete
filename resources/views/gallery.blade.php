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
        <img alt="Fecha" class="svg" src="{{ asset('svg/calendar.svg') }}" />{{ $article->created_at->diffForHumans() }}
        <img alt="Autor" class="svg" src="{{ asset('svg/user_black.svg') }}" />{{ $article->user->name }}
        <img alt="Visitas" class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $article->views }}
        @php($userReaction = Auth::user() ? Auth::user()->articleReaction($article->id, $article->section->id)->first() : null)
        @if($userReaction)
            @if($userReaction->reaction == 1)
                <img alt="Me gusta" title="Me gusta" class="svg article-reaction" id="like" src="{{asset('svg/like-active.svg')}}" data-reaction="1" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" />{{$article->reactions($article->section_id, '1')->count()}}
                <img alt="No me gusta" title="No me gusta" class="svg article-reaction dislike" id="dislike" src="{{asset('svg/article-reaction.svg')}}" data-reaction="0" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" />{{$article->reactions($article->section_id, '0')->count()}}
            @elseif($userReaction->reaction == 0)
                <img alt="Me gusta" title="Me gusta" class="svg article-reaction" id="like" src="{{asset('svg/article-reaction.svg')}}" data-reaction="1" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" />{{$article->reactions($article->section_id, '1')->count()}}
                <img alt="No me gusta" title="No me gusta" class="svg article-reaction dislike" id="dislike" src="{{asset('svg/like-active.svg')}}" data-reaction="0" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" />{{$article->reactions($article->section_id, '0')->count()}}
            @endif
        @else
            <img alt="Me gusta" title="Me gusta" class="svg article-reaction" id="like" src="{{asset('svg/article-reaction.svg')}}" data-reaction="1" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" />{{$article->reactions($article->section_id, '1')->count()}}
            <img alt="No me gusta" title="No me gusta" class="svg article-reaction dislike" id="dislike" src="{{asset('svg/article-reaction.svg')}}" data-reaction="0" data-user="{{Auth::id()}}" data-section="{{$article->section_id}}" data-article="{{$article->id}}" />{{$article->reactions($article->section_id, '0')->count()}}
        @endif
    </p>
    <hr />
@forelse ($photos as $photo)
<article class="fotos translate">
    <img src="{{ asset('img/before-load.png') }}" data-src="{{ asset('img/galleries/images/'.$photo->photo)}}" class="lazy" title="{{$photo->title}}" />
    <p>{{$photo->title}}</p>
</article>
@empty
    <h2>No hay fotos</h2>
@endforelse
    <hr />
    <h2 id="comments-count"><span>{{ $article->comments($article->section_id)->count() }}</span> Comentarios</h2>
    <div id="status"></div>
    <section class="comments"></section>
    @include('comments.form')
    <script src="{{ asset('js/comments.js') }}"></script>
    <script>
        /** Load Comments Box **/
        $(document).ready(function() {
            const link = '{{URL::route('getComments',['article_id' => $article->id, 'section_id' => $article->section_id], false) }}';
            getComments(link);
        });
    </script>
</article>
<script type="text/javascript" src="{{asset('js/article-reaction.js')}}"></script>
@endsection
