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
@section('content')
<div class="pub-container">
    <h1 class="article-main-title">{{ $article -> title }}</h1>
    <p class="article-main-description">{{ $article->article_desc }}</p>
    <div class="reactions-container">
        <div id="reactions">
            <img alt="Visitas" class="icon article-views" src="{{ asset('svg/eye.svg') }}" /><span>{{ $article->views }}</span>
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
    </div>
    <article class="pub">
        <div class="info">
            <img alt="Fecha" class="icon" src="{{ asset('svg/calendar.svg') }}" /><span>{{ ucfirst($article->created_at->diffForHumans()) }}</span>
            <img alt="Autor" class="icon" src="{{ asset('svg/user_black.svg') }}" /><span>{{ $article->user->name }}</span>
        </div>
        <article class="pollOptions">
            @if ($status)
            <h2>{{ $status }}</h2>
            <hr />
            @forelse ($poll_options as $option)
                @php($votesPercent = $totalVotes > 0 ? round(($option->votes * 100) / $totalVotes) : 0)
            <p class="options"><span>{{ $option->votes }}</span>{{ $option->option }}</p>
            <div class="resultContainer">
                <p class="barResult" style="width: {{ $votesPercent }}%">{{ $votesPercent }}%</p>
            </div>
            @empty
            <h2>No hay opciones</h2>
            @endforelse
            <hr />
            <p class="totalVotes">Votos: {{$totalVotes}}</p>
            @else
            <form action="{{route('poll-vote')}}" method="post" id="poll">
                @forelse ($poll_options as $option)
                <label class="radio-container" for="{{$option->id}}">{{ $option->option }}
                    <input type="radio" name="id_opcion" id="{{$option->id}}" value="{{$option->id}}" required />
                    <span class="checkmark"></span>
                </label>
                @empty
                <h2>No hay opciones</h2>
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
    <aside class="pub-aside">
        @if($morePolls->first())
            <h2>Más encuestas</h2>
            @forelse ($morePolls as $more)
            @php($fromDate = \Carbon\Carbon::parse($more->valid_from))
            @php($toDate = \Carbon\Carbon::parse($more->valid_to))
            @php($now = \Carbon\Carbon::now())
            <article class="pub-article">
                <p>
                    {{ucfirst($more->created_at->diffForHumans())}}
                    @if($toDate->isPast())
                        · <text style="color: #922B21">Encuesta cerrada</text>
                    @elseif($now->between($fromDate, $toDate))
                        · <text style="color: #1D8348">Encuesta activa</text>
                    @endif
                </p>
                <a href="{{route('poll',['id'=>$more->id,'title'=>str_slug($more->title,'-')])}}">{{$more->title}}</a>
            </article>
            @empty
            @endforelse
        @endif
    </aside>
</div>
<script type="text/javascript" src="{{asset('js/article-reaction.js')}}"></script>
@endsection
