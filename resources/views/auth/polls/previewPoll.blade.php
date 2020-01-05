@if (isset($success))
    <p class="alert feedback-success">{{ $success }}</p>
@endif
@if (isset($error))
    <p class="alert feedback-error">{{ $error }}</p>
@endif
<h1>Administrar encuesta</h1>
<div id="action">
    @if (Auth::user()->authorizeRoles([\barrilete\User::ADMIN_USER_ROLE]))
        @if ($poll->status == "DRAFT")
            <a href="{{ route('publishPoll',['id'=>$poll->id]) }}" class="button success" data-confirm="¿Estás seguro que deseas publicar la encuesta?">Publicar</a>
        @else
            <span class="button disabled">Publicado</span>
            <a href="{{ route('poll',['id'=>$poll->id,'section'=>str_slug($poll->section->name),'title'=>str_slug($poll->title,'-')]) }}" target="_blank" data-ajax="false" class="button primary">Ver artículo</a>
        @endif
        <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="button success">Editar</a>
        <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="button danger" data-confirm="¿Estás seguro que deseas borrar la encuesta?">Eliminar</a>
    @else
        @if ($poll->status == "PUBLISHED")
            <span class="button disabled">Publicado</span>
            <a href="{{ route('poll',['id'=>$poll->id,'section'=>str_slug($poll->section->name),'title'=>str_slug($poll->title,'-')]) }}" target="_blank" data-ajax="false" class="button primary">Ver artículo</a>
            <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="button success">Editar</a>
            <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="button danger" data-confirm="¿Estás seguro que deseas borrar la encuesta?">Eliminar</a>
        @else
            <span class="button disabled">Publicado</span>
            <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="button success">Editar</a>
            <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="button danger" data-confirm="¿Estás seguro que deseas borrar la encuesta?">Eliminar</a>
        @endif
    @endif
</div>
<hr />
<article class="pub_galeria">
    <h1>{{ $poll->title }}</h1>
    <p class="copete">{{ $poll->article_desc }}</p>
    <p class="info">
        <img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{$poll->created_at->diffForHumans()}}
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
            <p class="alert feedback-warning">Ésta encuesta no posee opciones</p>
        @endforelse
        <input type="submit" value="VOTAR" class="button disabled" disabled />
    </form>
</article>
<br />
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
