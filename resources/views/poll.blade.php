@extends('layouts.barrilete')
@section('title', $poll->title)
@section('content')
<div class="pubContainer">
    <article class="pub">
        <p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $poll->date }}</p>
        <h2>{{ $poll->title }}</h2>
        <p class="info">
            <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $poll->user->name }}
            <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $poll->views }} lecturas
        </p>
        <hr />
        <article class="pollOptions">
            @if ($status)
            <h2>{{ $status }}</h2>
            @forelse ($poll_options as $option)
            <p class="options">{{ $option->option }} ({{$option->votes}})</p>
            <p class="barResult" style="width: {{ ($option->votes * 100) / $totalVotos }}%">{{ round(($option->votes * 100) / $totalVotos )}}%</p>           
            @empty
            <h1>No hay opciones</h1>
            @endforelse
            <p>Votos: {{ $totalVotos }}</p>
            @else
            <form action="{{ route('poll-vote') }}" method="post">            
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
            <p>{{$more->fecha}}</p>
            <a href="{{route('poll',['id'=>$more->id,'titulo'=>str_slug($more->titulo,'-')])}}">{{$more->titulo}}</a>   
        </article> 
        @empty
        @endforelse
    </aside>
</div>
@endsection