<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ckeditor4.11.1/ckeditor.js') }}"></script>
<script type="text/javascript">
// esperamos que el DOM cargue
$(document).ready(function () {
// definimos las opciones del plugin AJAX FORM
    var opciones = {
        beforeSerialize: getckeditor,
        beforeSubmit: mostrarLoader, //funcion que se ejecuta antes de enviar el form
        success: mostrarRespuesta, //funcion que se ejecuta una vez enviado el formulario
        data: $('#createArticle').serialize(),
        datatype: 'json'
    };
    //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
    $('#createArticle').ajaxForm(opciones);
    //lugar donde defino las funciones que utilizo dentro de "opciones"

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
    
$(':file').jfilestyle({placeholder: 'Imagen principal (*)'});

CKEDITOR.replace('article_body');
});
</script>
<div id="Article_Form">
    <h1>Cargar artículo</h1>
    <form method="post" enctype="multipart/form-data" id="createArticle" action="{{ route('createArticle') }}">
        <fieldset>
            <legend>Información</legend>
            <p><b>Autor</b>: {{ Auth::user()->name }}</p>
            <p><b>Fecha de publicación</b>: {{ date('Y-m-d h:i') }}</p>
            <select name="section_id" size="1" id="seccion" required>
                <option value="" selected>Seleccionar Sección</option>
                @foreach ($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>   
            <input type="file" class="jfilestyle" data-placeholder="Imagen Principal" id="foto" name="photo" accept="image/*" required />
            <label class="check-container" for="video">La publicación contiene video de Youtube u otras fuentes
                <input type="checkbox" name="video" id="video" value="1" />
                <span class="check-mark"></span>
            </label>  
        </fieldset>
        <br />
        <fieldset>
            <legend>Título y Copete</legend>
            <input type="text" name="title" value="" placeholder="Título: éste es el principal título del articulo (*)" required />            
            <textarea name="article_desc" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*)" required></textarea>
        </fieldset>
        <br />
        <fieldset>
            <legend>Contenido</legend>
            <textarea name="article_body" id="article_body"></textarea>
        </fieldset>   
        @csrf
        <input type="submit" value="ENVIAR PUBLICACION" id="enviar" />
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
        <input type="hidden" name="date" value="{{ date('Y-m-d h:i') }}" />
        <input type="hidden" name="author" value="{{ Auth::user()->name }}" />
    </form>
</div>