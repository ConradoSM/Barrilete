<div id="Article-container">
    @if (isset($success))
    <p class="alert feedback-success">{{ $success }}</p>
    @endif
    @if (isset($error))
    <p class="alert feedback-error">{{ $error }}</p>
    @endif
    <h1>Administra el artículo</h1>
    <p class="article-actions">
        @if (Auth::user()->authorizeRoles([\barrilete\User::ADMIN_USER_ROLE]))
            @if ($article->status == "DRAFT")
            <a href="{{ route('publishArticle',['id'=>$article->id]) }}" class="button success" data-confirm="¿Estás seguro que deseas publicar el artículo?">Publicar</a>
            @else
            <span class="button disabled">Publicado</span>
            <a href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}" target="_blank" class="button primary" data-ajax="false">Ver artículo</a>
            @endif
            <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="button success">Editar</a>
            <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="button danger" data-confirm="¿Estás seguro que deseas eliminar el artículo?">Eliminar</a>
        @else
            @if ($article->status == "PUBLISHED")
            <span class="button disabled">Publicado</span>
            <a href="{{ route('article',['id'=>$article->id,'section'=>str_slug($article->section->name),'title'=>str_slug($article->title,'-')]) }}" target="_blank" class="button primary" data-ajax="false">Ver artículo</a>
            <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="button success">Editar</a>
            <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="button danger" data-confirm="¿Estás seguro que deseas eliminar el artículo?">Eliminar</a>
            @else
            <span class="button disabled">Publicado</span>
            <a href="{{ route('formUpdateArticle',['id'=>$article->id]) }}" class="button success">Editar</a>
            <a href="{{ route('deleteArticle',['id'=>$article->id]) }}" class="button danger" data-confirm="¿Estás seguro que deseas eliminar el artículo?">Eliminar</a>
            @endif
        @endif
    </p>
    <article class="preview">
        <img class="article-main-image" src="{{ asset('img/articles/images/'.$article->photo) }}" title="{{ $article->title }}" alt="{{ $article->title }}" />
        <h1>{{ $article -> title }}</h1>
        <p class="article-description">{{ $article->article_desc }}</p>
        <p class="article-info">
            <img class="svg" src="{{ asset('svg/calendar.svg') }}" /> {{ $article->created_at->diffForHumans() }}
            <img class="svg" src="{{ asset('svg/user_black.svg') }}" /> {{ $article->user->name }}
            <img class="svg" src="{{ asset('svg/eye.svg') }}" /> {{ $article->views }} lecturas
        </p>
        <hr />
        {!! $article->article_body !!}
    </article>
</div>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
