@php
$query = Request::get('query')
@endphp
<h1>Buscar Contenido</h1>
<div id="user-articles-list">
    <p class="searchFilter">
        <a class="{{ Request::get('sec') == 'articulos' ? 'active' : 'searchFilterLink' }}" href="{{route('searchAuth',['query'=>$query,'sec'=>'articulos','author'=>Auth::user()->id])}}">Artículos</a>
        <a class="{{ Request::get('sec') == 'galerias' ? 'active' : 'searchFilterLink' }}" href="{{route('searchAuth',['query'=>$query,'sec'=>'galerias','author'=>Auth::user()->id])}}">Galerías</a>
        <a class="{{ Request::get('sec') == 'encuestas' ? 'active' : 'searchFilterLink' }}" href="{{route('searchAuth',['query'=>$query,'sec'=>'encuestas','author'=>Auth::user()->id])}}">Encuestas</a>
    </p>
    <p>Se encontraron {{$result ? $result->total() : 0}} resultados para la búsqueda: <b>{{$query}}</b></p>
    @forelse ($result as $pub)
    <fieldset>
        <p class="article-status">
            @if ($pub->status == 'DRAFT')
            <img src="{{ asset('svg/forbidden.svg') }}" title="No publicado" alt="No publicado" />
            @else
            <img src="{{ asset('svg/checked.svg') }}" title="Publicado" alt="Publicado" />
            @endif
            {{ $pub->created_at->diffForHumans() }}
        </p>
        @if (Request::get('sec') == 'articulos')
        <h3><a href="{{ route('previewArticle', ['id'=>$pub->id]) }}">{{$pub->title}}</a></h3>
        @elseif (Request::get('sec') == 'galerias')
        <h3><a href="{{ route('previewGallery', ['id'=>$pub->id]) }}">{{$pub->title}}</a></h3>
        @elseif (Request::get('sec') == 'encuestas')
        <h3><a href="{{ route('previewPoll', ['id'=>$pub->id]) }}">{{$pub->title}}</a></h3>
        @endif
        <p>{{$pub->article_desc}}</p>
    </fieldset>
    @empty
    <hr />
    <p>No hay artículos para mostrar</p>
    @endforelse
    {{$result ? $result->links() : ''}}
</div>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
