<div id="Article-container">
    <h1>Administra el artículo</h1>
    <div class="article-admin">
        @if (Auth::user()->is_admin)
            @if ($article->status == "DRAFT")
                <a href="{{ route('publishArticle',['id'=>$article->id]) }}" class="edit" id="publish">Publicar</a>
            @else
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}" target="_blank" class="edit">Ver artículo</a>
            @endif      
            <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="edit" id="edit">Editar</a>
            <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="delete" id="delete">Eliminar</a>
        @else
            @if ($article->status == "PUBLISHED")
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}" target="_blank" class="edit">Ver artículo</a>
                <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="edit" id="edit">Editar</a>
                <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="delete" id="delete">Eliminar</a>
            @else
                <a href="#" class="disabled">No publicado</a>
                <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="edit" id="edit">Editar</a>
                <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="delete" id="delete">Eliminar</a>
            @endif
        @endif
    </div>
    <article class="pub-preview">
        <img src="{{ asset('img/articles/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" />
        <p class="info"><img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $article->date }}</p>
        <h2>{{ $article -> title }}</h2>
        <p class="copete">{{ $article->article_desc }}</p>
        <p class="info">
        <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $article->user->name }}
        <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $article->views }} lecturas
        </p>
        <hr />
        {!! $article->article_body !!}
    </article>
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