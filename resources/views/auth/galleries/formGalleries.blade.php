<div id="Article_Form">
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript">
// esperamos que el DOM cargue
$(document).ready(function () {
// definimos las opciones del plugin AJAX FORM
    var opciones = {
        beforeSubmit: mostrarLoader, //funcion que se ejecuta antes de enviar el form
        success: mostrarRespuesta, //funcion que se ejecuta una vez enviado el formulario
        data: $('#createArticle').serialize(),
        datatype: 'json'
    };
    //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
    $('#createArticle').ajaxForm(opciones);
    //lugar donde defino las funciones que utilizo dentro de "opciones"

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
});
</script>
<h1>Cargar galería</h1>
    <form method="post" enctype="multipart/form-data" id="createArticle" action="{{ route('createGallery') }}">
        <fieldset>
            <legend>Información</legend>
            <p><b>Autor</b>: {{ Auth::user()->name }}</p>
            <p><b>Fecha de publicación</b>: {{ date('Y-m-d h:i') }}</p> 
        </fieldset>
        <br />
        <fieldset>
            <legend>Título y Copete</legend>
            <input type="text" name="title" value="" placeholder="Título: éste es el principal título del articulo (*)" required />            
            <textarea name="article_desc" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*)" required></textarea>
        </fieldset>
        @csrf
        <input type="submit" value="SIGUIENTE >>" id="enviar" />
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
        <input type="hidden" name="date" value="{{ date('Y-m-d h:i') }}" />
        <input type="hidden" name="author" value="{{ Auth::user()->name }}" />
        <input type="hidden" name="section_id" value="{{ $section->id }}" />
    </form>
</div>