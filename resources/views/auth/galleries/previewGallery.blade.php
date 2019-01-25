<div id="Article-container">
    @if (isset($Exito))
    <p class="alert-success">{{ $Exito }}</p>
    @endif
    <h1>Administrar galería de fotos</h1>
    <div class="article-admin">
        @if (Auth::user()->is_admin)
            @if ($gallery->status == "DRAFT")
                <a href="{{ route('publishGallery',['id'=>$gallery->id]) }}" class="success" id="publish">Publicar</a>
            @else
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('gallery',['id'=>$gallery->id,'section'=>str_slug($gallery->section->name),'title'=>str_slug($gallery->title,'-')]) }}" target="_blank" class="primary">Ver artículo</a>
            @endif      
            <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="success" id="edit">Editar</a>
            <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="danger" id="delete">Eliminar</a>
        @else
            @if ($gallery->status == "PUBLISHED")
                <a href="#" class="disabled">Publicado</a>
                <a href="{{ route('gallery',['id'=>$gallery->id,'section'=>str_slug($gallery->section->name),'title'=>str_slug($gallery->title,'-')]) }}" target="_blank" class="primary">Ver artículo</a>
                <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="success" id="edit">Editar</a>
                <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="danger" id="delete">Eliminar</a>
            @else
                <a href="#" class="disabled">No publicado</a>
                <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="success" id="edit">Editar</a>
                <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="danger" id="delete">Eliminar</a>
            @endif
        @endif
    </div>
    <hr />
    <article class="pub_galeria">
        <h2>{{$gallery->title}}</h2>        
        <p class="copete">{{$gallery->article_desc}}</p>
        <p class="info">
        <img class="svg" src="{{asset('svg/calendar.svg')}}" /> {{$gallery->created_at->diffForHumans()}}
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
</div>
<script type="text/javascript" src="{{ asset('js/dashboard-admin-links.js') }}"></script>