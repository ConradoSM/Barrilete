<div id="Article-container">
    <h1>Administrar encuestas</h1>
    <div class="article-admin">
        @if (Auth::user()->is_admin)
            @if ($poll->status == "DRAFT")
                <a href="{{ route('publishPoll',['id'=>$poll->id]) }}" class="edit" id="publish">Publicar</a>
            @else
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('poll',['id'=>$poll->id,'section'=>str_slug($poll->section->name),'title'=>str_slug($poll->title,'-')]) }}" target="_blank" class="edit">Ver artículo</a>
            @endif      
            <a href="#" class="edit" id="edit">Editar</a>
            <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="delete" id="delete">Eliminar</a>
        @else
            @if ($poll->status == "PUBLISHED")
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('poll',['id'=>$poll->id,'section'=>str_slug($poll->section->name),'title'=>str_slug($poll->title,'-')]) }}" target="_blank" class="edit">Ver artículo</a>
                <a href="#" class="edit" id="edit">Editar</a>
                <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="delete" id="delete">Eliminar</a>
            @else
                <a href="#" class="disabled">No publicado</a>
                <a href="#" class="edit" id="edit">Editar</a>
                <a href="{{ route('deletePoll',['id'=>$poll->id]) }}" class="delete" id="delete">Eliminar</a>
            @endif
        @endif
    </div>
    <hr />
    <article class="pub_galeria">
    <p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{$poll->date}}</p>
    <h2>{{ $poll->title }}</h2>
    <p class="copete">{{ $poll->article_desc }}</p>
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
<script type="text/javascript">
        $(document).ready(function () {
            
            //BOTÓN PUBLICAR ENCUESTA
            $('div.article-admin a#publish').each(function () {

                var href = $(this).attr('href');
                $(this).attr({href: '#'});

                $(this).on('click', function () {
                    
                    if (confirm("¿Estás seguro que quieres publicar el artículo?")) {

                        $('#loader').fadeIn('fast', 'linear');
                        $('#Article-container').hide(0, function () {
                            $('#loader').fadeOut('fast', 'linear', function () {
                                $('#user-content').load(href, function () {                                
                                    $('#user-content').fadeIn('slow', 'linear');
                                });
                            });
                        });
                    } return false;
                });
            });

            //BOTÓN BORRAR ENCUESTA
            $('div.article-admin a#delete').each(function () {

                var href = $(this).attr('href');
                $(this).attr({href: '#'});

                $(this).click(function () {
                    
                    if (confirm("¿Estás seguro que quieres eliminar el artículo?")) {

                        $('#loader').fadeIn('fast', 'linear');
                        $('#Article-container').hide(0, function () {
                            $('#loader').fadeOut('fast', 'linear', function () {
                                $('#user-content').load(href, function () {                               
                                    $('#user-content').fadeIn('slow', 'linear');
                                });
                            });
                        });
                    } return false;
                });
            });

            //BOTÓN EDITAR ENCUESTA
            $('div.article-admin a#edit').each(function () {

                var href = $(this).attr('href');
                $(this).attr({href: '#'});

                $(this).click(function () {

                    $('#loader').fadeIn('fast', 'linear');
                    $('#Article-container').hide(0, function () {
                        $('#loader').fadeOut('fast', 'linear', function () {
                            $('#user-content').load(href, function () {                           
                                $('#user-content').fadeIn('slow', 'linear');
                            });
                        });
                    });
                });
            });
        });
    </script>
</div>