<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando artículo...
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
    <h1>{{ isset($article) ? 'Actualizar artículo' : 'Cargar artículo' }}</h1>
    <div id="errors"></div>
    <p><b>Autor</b>: {{ isset($article) ? $article->author : Auth::user()->name }}</p>
    <p><b>Fecha de publicación</b>: {{ isset($article) ? $article->created_at->diffForHumans() : ucwords(now()->formatLocalized('%A %d %B %Y')) }}</p>
    <hr />
    <form method="post" enctype="multipart/form-data" action="{{ isset($article) ? route('updateArticle', ['id' => $article->id]) : route('createArticle') }}">
        <h3>Imagen y Sección</h3>
        <fieldset>
            <select name="section_id" size="1" id="seccion" required>
                <option value="{{ isset($article) ? $article->section->id : '' }}" selected>{{ isset($article) ? ucfirst($article->section->name) : 'Seleccionar Sección' }}</option>
                @foreach ($sections as $section)
                <option value="{{ $section->id }}">{{ ucfirst($section->name) }}</option>
                @endforeach
            </select>
            <input type="file" class="jfilestyle" data-inputSize="500px" data-placeholder="Imagen Principal (*) Oligatoria, sólo imágenes JPG, JPEG, PNG" id="foto" name="photo" accept=".png, .jpg, .jpeg" {{ isset($article) ? '' : 'required' }} />
        </fieldset>
        <h3>Opciones</h3>
        <fieldset>
            <label class="check-container" for="video">La publicación contiene video de Youtube u otras fuentes
                <input type="checkbox" name="video" id="video" value="1" {{ isset($article) && $article->video == true  ? 'checked' : '' }} />
                <span class="check-mark"></span>
            </label>
            <br>
            <label class="check-container" for="is_breaking">Es noticia de último momento
                <input type="checkbox" name="is_breaking" id="is_breaking" value="1" {{ isset($article) && $article->is_breaking == true  ? 'checked' : '' }} />
                <span class="check-mark"></span>
            </label>
        </fieldset>
        <h3>Título y Copete</h3>
        <fieldset>
            <input type="text" name="title" value="{{ isset($article) ? $article->title : '' }}" placeholder="Título: éste es el principal título del articulo (*) Mínimo 20 caracteres" required />
            <textarea name="article_desc" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*) Mínimo 50 caracteres" required>{{ isset($article) ? $article->article_desc : '' }}</textarea>
        </fieldset>
        <h3>Contenido</h3>
        <fieldset>
            <textarea name="article_body" id="article_body">{{ isset($article) ? $article->article_body : '' }}</textarea>
        </fieldset>
        @csrf
        <input type="hidden" name="user_id" value="{{ isset($article) ? $article->user->id : Auth::user()->id }}" />
        <input type="hidden" name="author" value="{{ isset($article) ? $article->author : Auth::user()->name }}" />
        <input type="hidden" name="id" value="{{ isset($article) ? $article->id : '' }}" />
        <input type="submit" value="Guardar" id="enviar" class="button primary" />
        <input type="reset" class="button default" value="Restablecer" />
    </form>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js')}}"></script>
<script type="text/javascript">
    // Create editor instance
    CKEDITOR.replace('article_body', {
        allowedContent: true
    });
</script>
