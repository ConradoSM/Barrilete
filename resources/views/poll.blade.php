@extends('layouts.barrilete')
@section('title', $poll->title)
@section('description', $poll->article_desc)
@section('article_title', $poll->title)
@section('article_type', 'poll')
@section('article_desc', $poll->article_desc)
@section('article_url', route('poll', ['id' => $poll->id, 'title' => str_slug($poll->title, '-')]))
@section('site_name', 'Barrilete')
@section('created_at', $poll->created_at)
@section('updated_at', $poll->updated_at)
@section('article_section', $poll->section->name)
@section('content')
<div class="pubContainer">
    <article class="pub">
        <h2>{{ $poll->title }}</h2>
        <p class="copete">{{ $poll->article_desc }}</p>
        <p class="info">
            <img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{$poll->created_at->diffForHumans()}}
            <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$poll->user->name}}
            <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$poll->views}} lecturas
        </p>
        <hr />
        <article class="pollOptions">
            @if ($status)
            <h2>{{$status}}</h2>
            @forelse ($poll_options as $option)
            <p class="options">{{$option->option}} ({{$option->votes}})</p>
            <p class="barResult" style="width: {{ ($option->votes * 100) / $totalVotes}}%">{{round(($option->votes * 100) / $totalVotes )}}%</p>           
            @empty
            <h1>No hay opciones</h1>
            @endforelse
            <p>Votos: {{$totalVotes}}</p>
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
                @csrf
                <input type="hidden" name="id_encuesta" value="{{$poll->id}}" />
                <input type="hidden" name="ip" value="{{Request::ip()}}" />
                <input type="hidden" name="titulo_encuesta" value="{{str_slug($poll->title,'-')}}" />
                <input type="submit" name="submit" value="VOTAR" />
            </form>
            @endif
        </article>
        <div class="fb-comments" data-href="{{url()->current()}}" data-width="100%" data-numposts="5"></div>
        <div id="fb-root"></div>
        <script>
            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v3.2';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    </article>
    <aside class="pubSeccion">
        @forelse ($morePolls as $more)
        <article class="morePolls">
            <p>{{$more->date}}</p>
            <a href="{{route('poll',['id'=>$more->id,'title'=>str_slug($more->title,'-')])}}">{{$more->title}}</a>   
        </article> 
        @empty
        @endforelse
    </aside>
</div>
@endsection