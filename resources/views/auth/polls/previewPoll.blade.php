@if (isset($success))
    <p class="alert-success"><img src="/svg/ajax-success.svg" alt="Exito"/>{{ $success }}</p>
@endif
@if (isset($error))
    <p class="invalid-feedback"><img src="/svg/ajax-error.svg" alt="Error"/>{{ $error }}</p>
@endif
<h1>Administrar encuesta</h1>
<div id="action">
    @if (Auth::user()->authorizeRoles([\barrilete\User::ADMIN_USER_ROLE]))
        @if ($poll->status == "DRAFT")
            <a href="{{ route('publishPoll',['id'=>$poll->id]) }}" class="success" data-confirm="¿Estás seguro que deseas publicar la encuesta?">Publicar</a>
        @else
            <a href="#" class="disabled">Publicado</a>
            <a href="{{ route('poll',['id'=>$poll->id,'section'=>str_slug($poll->section->name),'title'=>str_slug($poll->title,'-')]) }}" target="_blank" data-ajax="false" class="primary">Ver artículo</a>
        @endif
        <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="success">Editar</a>
        <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="danger" data-confirm="¿Estás seguro que deseas borrar la encuesta?">Eliminar</a>
    @else
        @if ($poll->status == "PUBLISHED")
            <a href="#" class="disabled">Publicado</a>
            <a href="{{ route('poll',['id'=>$poll->id,'section'=>str_slug($poll->section->name),'title'=>str_slug($poll->title,'-')]) }}" target="_blank" data-ajax="false" class="primary">Ver artículo</a>
            <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="success">Editar</a>
            <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="danger" data-confirm="¿Estás seguro que deseas borrar la encuesta?">Eliminar</a>
        @else
            <a href="#" class="disabled">No publicado</a>
            <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="success">Editar</a>
            <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="danger" data-confirm="¿Estás seguro que deseas borrar la encuesta?">Eliminar</a>
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
            <p class="alert-warning"><img src="/svg/ajax-warning.svg" alt="Advertencia"/>Ésta encuesta no posee opciones</p>
        @endforelse
        <input type="submit" value="VOTAR" class="disabled" disabled />
    </form>
</article>
<br />
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
