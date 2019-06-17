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
        <h1>{{ $poll->title }}</h1>
        <p class="copete">{{ $poll->article_desc }}</p>
        <p class="info">
            <img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{$poll->created_at->diffForHumans()}}
            <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$poll->user->name}}
            <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$poll->views}}
        </p>
        <hr />
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
                <input type="hidden" name="id_encuesta" value="{{$poll->id}}" />
                <input type="hidden" name="ip" value="{{Request::ip()}}" />
                <input type="hidden" name="titulo_encuesta" value="{{str_slug($poll->title,'-')}}" />
                <input type="submit" name="submit" value="Votar" class="primary" />
            </form>
            @endif
        </article>
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
@endsection