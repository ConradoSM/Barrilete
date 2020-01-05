@if (isset($success))
    <p class="alert feedback-success">{{ $success }}</p>
@endif
@if (isset($error))
    <p class="alert feedback-error">{{ $error }}</p>
@endif
@if (!$photos)
    <p class="alert feedback-warning">Ésta galería no posee fotos</p>
@endif
<h1>Administrar galería de fotos</h1>
<div id="action">
    @if (Auth::user()->authorizeRoles([\barrilete\User::ADMIN_USER_ROLE]))
        @if ($gallery->status == "DRAFT")
            <a href="{{ route('publishGallery',['id'=>$gallery->id]) }}" class="button success" data-confirm="¿Estás seguro que desea publicar ésta galería de fotos?">Publicar</a>
        @else
            <span class="button disabled">Publicado</span>
            <a href="{{ route('gallery',['id'=>$gallery->id,'title'=>str_slug($gallery->title,'-')]) }}" class="button primary" data-ajax="false" target="_blank">Ver artículo</a>
        @endif
        <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="button success">Editar</a>
        <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="button danger" data-confirm="¿Estás seguro que desea borrar ésta galería de fotos?">Eliminar</a>
    @else
        @if ($gallery->status == "PUBLISHED")
            <span class="button disabled">Publicado</span>
            <a href="{{ route('gallery',['id'=>$gallery->id,'title'=>str_slug($gallery->title,'-')]) }}" class="button primary" data-ajax="false" target="_blank">Ver artículo</a>
            <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="button success">Editar</a>
            <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="button danger" data-confirm="¿Estás seguro que desea borrar ésta galería de fotos?">Eliminar</a>
        @else
            <span class="button disabled">Publicado</span>
            <a href="{{ route('formUpdateGallery',['id'=>$gallery->id]) }}" class="button success">Editar</a>
            <a href="{{ route('deleteGallery',['id'=>$gallery->id]) }}" class="button danger" data-confirm="¿Estás seguro que desea borrar ésta galería de fotos?">Eliminar</a>
        @endif
    @endif
</div>
<hr />
<article class="pub_galeria">
    <h1>{{$gallery->title}}</h1>
    <p class="copete">{{$gallery->article_desc}}</p>
    <p class="info">
    <img class="svg" src="{{asset('svg/calendar.svg')}}" /> {{$gallery->created_at->diffForHumans()}}
    <img class="svg" src="{{asset('svg/user_black.svg')}}" /> {{$gallery->user->name}}
    <img class="svg" src="{{asset('svg/eye.svg')}}" /> {{$gallery->views}} lecturas
    </p>
    <hr />
</article>
@forelse ($photos as $photo)
<article class="fotos">
    <img src="{{ asset('img/galleries/'.$photo->photo)}}" />
    <p>{{$photo->title}}</p>
</article>
@empty
@endforelse
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
