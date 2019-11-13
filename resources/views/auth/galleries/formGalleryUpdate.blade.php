<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando artículo...
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
    @if (isset($success))
        <p class="alert-success"><img src="/svg/ajax-success.svg" alt="Exito"/>{{ $success }}</p>
    @endif
    @if (isset($error))
        <p class="invalid-feedback"><img src="/svg/ajax-error.svg" alt="Error"/>{{ $error }}</p>
    @endif
    @if (!$photos)
        <p class="alert-warning"><img src="/svg/ajax-warning.svg" alt="Advertencia"/>Ésta galería no posee fotos</p>
    @endif
    <h1>Actualizar galería de fotos</h1>
    <fieldset>
        <legend>Información</legend>
        <p><b>Autor</b>: {{ $gallery->author }}</p>
        <p><b>Creación</b>: {{ $gallery->created_at->diffForHumans() }} - <b>Última actualización:</b> {{ $gallery->updated_at->diffForHumans() }}</p>
    </fieldset>
    <fieldset>
        <legend>Título y Copete</legend>
            <div id="errors"></div>
            <form method="post" class="data" action="{{ route('updateGallery') }}">
                <p title="Editar">{{ $gallery->title }}</p>
                <input type="text" class="input" name="title" value="{{ $gallery->title }}" placeholder="Título: éste es el principal título del articulo (*)" required />
                <p title="Editar">{{ $gallery->article_desc }}</p>
                <textarea name="article_desc" class="input" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*)" required>{{ $gallery->article_desc }}</textarea>
                <input type="submit" value="Actualizar" class="success" />
                @csrf
                <input type="hidden" name="id" value="{{ $gallery->id }}" />
                <input type="hidden" name="user_id" value="{{ $gallery->user_id }}" />
                <input type="hidden" name="author" value="{{ $gallery->author }}" />
                <input type="hidden" name="section_id" value="{{ $gallery->section_id }}" />
            </form>
    </fieldset>
    <a href="{{ route('morePhotos', ['id' => $gallery->id]) }}" class="primary">+ Agregar Imágenes</a>
    @forelse ($photos as $photo)
    <fieldset data-id="{{ $photo->id }}">
        <div class="status"></div>
        <div class="photo-gallery-container">
            <form action="{{ route('updatePhoto') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" class="jfilestyle" data-placeholder="Seleccionar imagen" name="photo" accept="image/*" required />
                <input type="submit" class="success" value="Actualizar" />
                <input type="hidden" name="id" value="{{ $photo->id }}" />
                <input type="hidden" name="actual_photo" value="{{ $photo->photo }}" />
            </form>
            <img class="update-image-button" src="{{ asset('svg/update.svg') }}" title="Actualizar imagen" />
            <a href="{{ route('deletePhoto', ['id' => $photo->id]) }}" data-confirm="¿Estás seguro que desear eliminar ésta imagen?"><img class="delete-image-button" src="{{ asset('svg/delete.svg') }}" title="Borrar imagen" /></a>
            <img class="photo-gallery" src="{{ asset('img/galleries/'.$photo->photo) }}" />
        </div>
        <div class="status"></div>
        <form method="post" class="data" action="{{ route('updateTitlePhotoGallery') }}">
            <p title="Editar">{{ $photo->title }}</p>
            <input type="text" name="title" class="input" value="{{ $photo->title }}" placeholder="Título: éste es el principal título de la foto (*)" required />
            <input type="submit" class="success" value="Actualizar" />
            <input type="hidden" name="id" value="{{ $photo->id }}" />
            @csrf
        </form>
    </fieldset>
    @empty
    @endforelse
</div>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-edit-form.js') }}"></script>
