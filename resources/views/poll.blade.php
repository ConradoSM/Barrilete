@extends('layout')
@section('title', $poll->titulo)
@section('content')
<div class="pubContainer">
    <article class="pub">
        <p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $poll->fecha }}</p>
        <h2>{{ $poll->titulo }}</h2>
        <p class="info">
            <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $poll->autor }}
            <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $poll->visitas }} lecturas
        </p>
        <hr />
        <form action="{{ route('poll-vote') }}" method="post">
            <article class="pollOptions">
                @forelse ($options as $option)
                <input type="radio" name="idOpcion" value="{{$option->id}}" required />
                <span>{{ $option->opcion }}</span>
                @empty
                <h1>No hay opciones</h1>
                @endforelse
            </article>
            {{ csrf_field() }}
            <input type="hidden" name="id_encuesta" value="{{ $poll->id }}" />
            <input type="hidden" name="ip" value="{{Request::ip()}}" />
            <input type="submit" name="submit" value="Votar" />
        </form>
        <div class="fb-comments" data-href="{{url()->current()}}" data-width="100%" data-numposts="5"></div>
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v3.2';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
    </article>
    <aside class="pubSeccion">
        @forelse ($morePolls as $more)
        <article class="pubArticle">
            <p>{{ $more -> fecha }}</p>
            <a href="{{ route('poll', ['id' => $more -> id, 'titulo' => str_slug($more -> titulo, '-')]) }}">{{ $more -> titulo }}</a>   
        </article> 
        @empty
        @endforelse
    </aside>
</div>
@endsection