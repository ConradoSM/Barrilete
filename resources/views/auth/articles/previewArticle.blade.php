<div id="Article-container">
    @if (isset($success))
    <p class="alert-success"><img src="/svg/ajax-success.svg" alt="Exito"/>{{ $success }}</p>
    @endif
    @if (isset($error))
        <p class="invalid-feedback"><img src="/svg/ajax-error.svg" alt="Error"/>{{ $error }}</p>
    @endif
    <h1>Administra el artículo</h1>
    <div id="action">
        @if (Auth::user()->is_admin)
            @if ($article->status == "DRAFT")
                <a href="{{ route('publishArticle',['id'=>$article->id]) }}" class="success" data-confirm="¿Estás seguro que deseas publicar el artículo?">Publicar</a>
            @else
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}" target="_blank" class="primary" data-ajax="false">Ver artículo</a>
            @endif
            <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="success">Editar</a>
            <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="danger" data-confirm="¿Estás seguro que deseas eliminar el artículo?">Eliminar</a>
        @else
            @if ($article->status == "PUBLISHED")
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}" target="_blank" class="primary" data-ajax="false">Ver artículo</a>
                <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="success">Editar</a>
                <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="danger" data-confirm="¿Estás seguro que deseas eliminar el artículo?">Eliminar</a>
            @else
                <a href="#" class="disabled">No publicado</a>
                <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="success">Editar</a>
                <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="danger" data-confirm="¿Estás seguro que deseas eliminar el artículo?">Eliminar</a>
            @endif
        @endif
    </div>
    <article class="pub-preview">
        <img src="{{ asset('img/articles/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" />
        <h1>{{ $article -> title }}</h1>
        <p class="copete">{{ $article->article_desc }}</p>
        <p class="info">
            <img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $article->created_at->diffForHumans() }}
            <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $article->user->name }}
            <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $article->views }} lecturas
        </p>
        <hr />
        {!! $article->article_body !!}
    </article>
</div>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
