@if (isset($success))
    <p class="alert feedback-success">{{ $success }}</p>
@endif
@if (isset($error))
    <p class="alert feedback-error">{{ $error }}</p>
@endif
<h1>Administrar encuesta</h1>
<p class="article-actions">
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
</p>
<hr />
@php($fromDate = \Carbon\Carbon::parse($poll->valid_from))
@php($toDate = \Carbon\Carbon::parse($poll->valid_to))
@php($now = \Carbon\Carbon::now())
@if($toDate->isPast())
    <p class="alert feedback-error"><span>Ésta encuesta se encuentra cerrada, para activarla haz click en <b>Editar</b> y actualiza la fecha de validez.</span></p>
@elseif($fromDate->isFuture())
<p class="alert feedback-warning"><span>Ésta encuesta no se encuentra activa aún, para verla publicada debes esperar a la fecha indicada.</span></p>
@elseif($now->between($fromDate, $toDate))
<p class="alert feedback-success"><span>Ésta encuesta se encuentra activa.</span></p>
@endif
<article class="preview">
    <h1>{{ $poll->title }}</h1>
    <p class="article-description">{{ $poll->article_desc }}</p>
    <p class="article-info">
        <img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{$poll->created_at->diffForHumans()}}
        <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$poll->user->name}}
        <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$poll->views}} lecturas
    </p>
</article>
<hr />
<fieldset>
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
</fieldset>
<br />
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
