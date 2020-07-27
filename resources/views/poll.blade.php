@extends('layouts.barrilete')
@section('title', $article->title)
@section('description', $article->article_desc)
@section('article_title', $article->title)
@section('article_type', 'poll')
@section('article_desc', $article->article_desc)
@section('article_url', route('poll', ['id' => $article->id, 'title' => str_slug($article->title, '-')]))
@section('site_name', 'Barrilete')
@section('created_at', $article->created_at)
@section('updated_at', $article->updated_at)
@section('article_section', $article->section->name)
<meta name="_token" content="{{ csrf_token() }}">
@section('content')
<div class="pubContainer">
    <article class="pub">
        <h1>{{ $article->title }}</h1>
        <p class="copete">{{ $article->article_desc }}</p>
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
        <article class="pollOptions">
            @if ($status)
            <h2>{{ $status }}</h2>
            <hr />
            @forelse ($poll_options as $option)
            <p class="options">{{ $option->option }} ({{ $option->votes }})</p>
            <div class="resultContainer">
                <p class="barResult" style="width: {{ ($option->votes * 100) / $totalVotes }}%">{{ round(($option->votes * 100) / $totalVotes ) }}%</p>
            </div>
            @empty
            <h1>No hay opciones</h1>
            @endforelse
            <hr />
            <p class="totalVotes">Votos: {{$totalVotes}}</p>
            @else
            <form action="{{route('poll-vote')}}" method="post">
                @forelse ($poll_options as $option)
                <label class="radio-container" for="{{$option->id}}">{{ $option->option }}
                    <input type="radio" name="id_opcion" id="{{$option->id}}" value="{{$option->id}}" required />
                    <span class="checkmark"></span>
                </label>
                @empty
                <h1>No hay opciones</h1>
                @endforelse
                <hr />
                @csrf
                <input type="hidden" name="id_encuesta" value="{{$article->id}}" />
                <input type="hidden" name="ip" value="{{Request::ip()}}" />
                <input type="hidden" name="titulo_encuesta" value="{{str_slug($article->title,'-')}}" />
                <input type="submit" name="submit" value="Votar" class="button primary" />
            </form>
            @endif
        </article>
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
    <aside class="pubSeccion">
        @forelse ($morePolls as $more)
        <article class="morePolls">
            <p>{{ucfirst($more->created_at->diffForHumans())}}</p>
            <a href="{{route('poll',['id'=>$more->id,'title'=>str_slug($more->title,'-')])}}">{{$more->title}}</a>
        </article>
        @empty
        @endforelse
    </aside>
</div>
<script type="text/javascript" src="{{asset('js/article-reaction.js')}}"></script>
@endsection
