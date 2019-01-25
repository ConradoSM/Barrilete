<div id="Article-container">
    @if (isset($Exito))
    <p class="alert-success">{{ $Exito }}</p>
    @endif
    <h1>Administrar encuestas</h1>
    <div class="article-admin">
        @if (Auth::user()->is_admin)
            @if ($poll->status == "DRAFT")
                <a href="{{ route('publishPoll',['id'=>$poll->id]) }}" class="success" id="publish">Publicar</a>
            @else
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('poll',['id'=>$poll->id,'section'=>str_slug($poll->section->name),'title'=>str_slug($poll->title,'-')]) }}" target="_blank" class="primary">Ver artículo</a>
            @endif      
            <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="success" id="edit">Editar</a>
            <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="danger" id="delete">Eliminar</a>
        @else
            @if ($poll->status == "PUBLISHED")
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('poll',['id'=>$poll->id,'section'=>str_slug($poll->section->name),'title'=>str_slug($poll->title,'-')]) }}" target="_blank" class="primary">Ver artículo</a>
                <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="success" id="edit">Editar</a>
                <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="danger" id="delete">Eliminar</a>
            @else
                <a href="#" class="disabled">No publicado</a>
                <a href="{{ route('formUpdatePoll',['id'=>$poll->id]) }}" class="success" id="edit">Editar</a>
                <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="danger" id="delete">Eliminar</a>
            @endif
        @endif
    </div>
    <hr />
    <article class="pub_galeria">
    <h2>{{ $poll->title }}</h2>
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
            <h1>No hay opciones</h1>
            @endforelse
            <input type="submit" value="VOTAR" disabled />
        </form>
    </article>
<br />
</div>
<script type="text/javascript" src="{{ asset('js/dashboard-admin-links.js') }}"></script>