<h1>Preview de la encuesta</h1>
<p>Así se verá tu encuesta cuando se publique</p>
<hr />
<article class="pub_galeria">
<p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{$poll->date}}</p>
<h2>{{ $poll->title }}</h2>
<p class="info">
    <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$poll->user->name}}
    <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$poll->views}} lecturas
</p>
</article>
<hr />
<article class="pollOptions">
    <form action="#" method="post">            
        @forelse ($poll_options as $option)
        <label class="radio-container" for="{{$option->id}}">{{ $option->option }}
            <input type="radio" name="id_opcion" id="{{$option->id}}" value="{{$option->id}}" required />
            <span class="checkmark"></span>
        </label>
        @empty
        <h1>No hay opciones</h1>
        @endforelse
        <input type="submit" value="VOTAR" disabled />
    </form>
</article>
<br />
