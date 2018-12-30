<div id="Article_Form">
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor4.11.1/ckeditor.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    var opciones = {
        beforeSerialize: getckeditor,
        beforeSubmit: mostrarLoader,
        success: mostrarRespuesta,
        error: mostrarError,
        data: $('#createArticle').serialize(),
        datatype: 'json'
    };

    $('#createArticle').ajaxForm(opciones);

    function getckeditor() {
        var section_notes_data = CKEDITOR.instances.article_body.getData();
        $('#article_body').val(section_notes_data);
    };
    function mostrarLoader() {

        $('#loader').fadeIn('slow');
        $('#Article_Form').css('display', 'none');
    };
    function mostrarRespuesta(responseText) {

        $('#loader').fadeOut('slow', function () {
            $('#Article_Form').css('display', 'none');
            $('#user-content').html(responseText).fadeIn('normal');
        });
    };
    function mostrarError(xhr) {
        
        var errors = xhr.responseJSON.errors;
        
        $('#loader').fadeOut('slow', function () {
            
            $('#Article_Form').fadeIn('slow');
            $.each(errors, function(key,value) {
                $('#errors').append('<p class="invalid-feedback">'+value+'</p>');       
            });
        });
        console.log(errors);
    };   
    $('#enviar').on('click', function() {
        $('p.invalid-feedback').hide();
    });
    $(':file').jfilestyle({placeholder: 'Imagen principal (*)'
    });
    CKEDITOR.replace('article_body');
});
</script>
<h1>Cargar artículo</h1>
    <form method="post" enctype="multipart/form-data" id="createArticle" action="{{ isset($article) ? route('updateArticle', ['id' => $article->id]) : route('createArticle') }}">
        <fieldset>
            <legend>Información</legend>
            <div id="errors"></div>
            <p><b>Autor</b>: {{ isset($article) ? $article->author : Auth::user()->name }}</p>
            <p><b>Fecha de publicación</b>: {{ isset($article) ? $article->date : date('Y-m-d h:i') }}</p>
            <select name="section_id" size="1" id="seccion" required>
                <option value="{{ isset($article) ? $article->section->id : '' }}" selected>{{ isset($article) ? $article->section->name : 'Seleccionar Sección' }}</option>
                @foreach ($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>   
            <input type="file" class="jfilestyle" data-placeholder="Imagen Principal" id="foto" name="photo" accept="image/*" {{ isset($article) ? '' : 'required' }} />
            <label class="check-container" for="video">La publicación contiene video de Youtube u otras fuentes
                <input type="checkbox" name="video" id="video" value="1" {{ isset($article) && $article->video == true  ? 'checked' : '' }} />
                <span class="check-mark"></span>
            </label>  
        </fieldset>
        <fieldset>
            <legend>Título y Copete</legend>
            <input type="text" name="title" value="{{ isset($article) ? $article->title : '' }}" placeholder="Título: éste es el principal título del articulo (*)" required />            
            <textarea name="article_desc" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*)" required>{{ isset($article) ? $article->article_desc : '' }}</textarea>
        </fieldset>
        <fieldset>
            <legend>Contenido</legend>
            <textarea name="article_body" id="article_body">{{ isset($article) ? $article->article_body : '' }}</textarea>
            <input type="submit" value="ENVIAR PUBLICACION" id="enviar" />
        </fieldset>   
        @csrf
        <input type="hidden" name="user_id" value="{{ isset($article) ? $article->user->id : Auth::user()->id }}" />
        <input type="hidden" name="date" value="{{ isset($article) ? $article->date : date('Y-m-d h:i') }}" />
        <input type="hidden" name="author" value="{{ isset($article) ? $article->author : Auth::user()->name }}" />
        <input type="hidden" name="id" value="{{ isset($article) ? $article->id : '' }}" />
    </form>
</div>