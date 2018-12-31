<div id="Article-container">
    <h1>Administrar galería de fotos</h1>
    <div class="article-admin">
        @if (Auth::user()->is_admin)
            @if ($gallery->status == "DRAFT")
                <a href="{{ route('publishGallery',['id'=>$gallery->id]) }}" class="edit" id="publish">Publicar</a>
            @else
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('gallery',['id'=>$gallery->id,'section'=>str_slug($gallery->section->name),'title'=>str_slug($gallery->title,'-')]) }}" target="_blank" class="edit">Ver artículo</a>
            @endif      
            <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="edit" id="edit">Editar</a>
            <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="delete" id="delete">Eliminar</a>
        @else
            @if ($gallery->status == "PUBLISHED")
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('gallery',['id'=>$gallery->id,'section'=>str_slug($gallery->section->name),'title'=>str_slug($gallery->title,'-')]) }}" target="_blank" class="edit">Ver artículo</a>
                <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="edit" id="edit">Editar</a>
                <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="delete" id="delete">Eliminar</a>
            @else
                <a href="#" class="disabled">No publicado</a>
                <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="edit" id="edit">Editar</a>
                <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="delete" id="delete">Eliminar</a>
            @endif
        @endif
    </div>
    <article class="pub_galeria">
        <p class="info"><img class="svg" src="{{asset('svg/calendar.svg')}}" /> {{$gallery->date}}</p>
        <h2>{{$gallery->title}}</h2>
        <p class="copete">{{$gallery->article_desc}}</p>
        <p class="info">
        <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$gallery->user->name}}
        <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$gallery->views}} lecturas
        </p>
        <br />
    </article>
    @forelse ($photos as $photo)
    <article class="fotos">
        <img src="{{ asset('img/galleries/'.$photo->photo)}}" />
        <p>{{$photo->title}}</p>
    </article>
    @empty
        <p>No hay fotos</p>
    @endforelse
    <script type="text/javascript">
        $(document).ready(function () {
            
            //BOTÓN PUBLICAR ARTÍCULO
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

            //BOTÓN BORRAR ARTÍCULO
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

            //BOTÓN EDITAR ARTÍCULO
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