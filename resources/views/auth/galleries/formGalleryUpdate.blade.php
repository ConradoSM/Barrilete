<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando galería...
    <div class="progress">
        <div class="bar"></div>
        <div class="percent">0%</div>
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
    @if (isset($success))
        <p class="alert feedback-success">{{ $success }}</p>
    @endif
    @if (isset($error))
        <p class="alert feedback-error">{{ $error }}</p>
    @endif
    @if (!$photos)
        <p class="alert feedback-warning">Ésta galería no posee fotos</p>
    @endif
    <h1>Actualizar galería de fotos</h1>
    <p class="article-actions">
        <a href="{{ route('morePhotos', ['id' => $gallery->id]) }}" class="button primary">+ Agregar Imágenes</a>
        <a href="{{ route('previewGallery', ['id' => $gallery->id]) }}" class="button primary">Terminar Edición</a>
    </p>
    <hr />
    <div class="article-preview-container">
        <h3>Información</h3>
        <fieldset>
            <p><b>Autor</b>: {{ $gallery->author }}</p>
            <p><b>Creación</b>: {{ $gallery->created_at->diffForHumans() }} - <b>Última actualización:</b> {{ $gallery->updated_at->diffForHumans() }}</p>
        </fieldset>
        <h3>Título y Copete</h3>
        <fieldset>
            <div id="errors"></div>
            <form method="post" class="data" action="{{ route('updateGallery') }}">
                <input type="text" name="title" value="{{ $gallery->title }}" placeholder="Título: éste es el principal título del articulo (*)" required />
                <textarea name="article_desc" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*)" required>{{ $gallery->article_desc }}</textarea>
                <input type="submit" value="Actualizar" class="button success" />
                @csrf
                <input type="hidden" name="id" value="{{ $gallery->id }}" />
                <input type="hidden" name="user_id" value="{{ $gallery->user_id }}" />
                <input type="hidden" name="author" value="{{ $gallery->author }}" />
                <input type="hidden" name="section_id" value="{{ $gallery->section_id }}" />
            </form>
        </fieldset>
        @forelse ($photos as $photo)
        <fieldset data-id="{{ $photo->id }}">
            <div class="status"></div>
            <div class="photo-gallery-container">
                <form action="{{ route('updatePhoto') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" class="jfilestyle" data-inputSize="320px" data-placeholder="Seleccionar imagen" name="photo" accept="image/*" required />
                    <input type="submit" class="button success" value="Actualizar" />
                    <input type="hidden" name="id" value="{{ $photo->id }}" />
                    <input type="hidden" name="actual_photo" value="{{ $photo->photo }}" />
                </form>
                <img class="update-image-button" src="{{ asset('svg/update.svg') }}" title="Actualizar imagen" />
                <a href="{{ route('deletePhoto', ['id' => $photo->id]) }}" data-confirm="¿Estás seguro que desear eliminar ésta imagen?"><img class="delete-image-button" src="{{ asset('svg/delete.svg') }}" title="Borrar imagen" /></a>
                <img class="photo-gallery" src="{{ asset('img/galleries/images/'.$photo->photo) }}" />
            </div>
            <div class="status"></div>
            <form method="post" class="data" action="{{ route('updateTitlePhotoGallery') }}">
                <input type="text" name="title" value="{{ $photo->title }}" placeholder="Título: éste es el principal título de la foto (*)" required />
                <input type="submit" class="button success" value="Actualizar" />
                <input type="hidden" name="id" value="{{ $photo->id }}" />
                @csrf
            </form>
        </fieldset>
        @empty
        @endforelse
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-edit-form.js') }}"></script>
