<div id="Article_Form">
<script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
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
    //selección de fotos
    $(":file").jfilestyle({
        placeholder: "Elije las fotos (*)",
	buttonText: "Seleccionar Archivos"
    });		
    $(function(){  
	$('#photo').on('change', function(){
	/* Limpiar vista previa */
            $('#vista-previa').html('');
	    var archivos = document.getElementById('photo').files;
	    var navegador = window.URL || window.webkitURL;
	/* Recorrer los archivos */
	    for(x=0; x<archivos.length; x++) {
	/* Validar tamaño y tipo de archivo */
	    var size = archivos[x].size;
	    var type = archivos[x].type;
	    var name = archivos[x].name;
	               
	if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
	    
            $('#error').append('<p class="invalid-feedback" role="alert">El archivo <b>'+name+'</b> no es del tipo de imagen permitida, sólo se admiten archivos JPG, JPEG o PNG, por favor selecciona los archivos nuevamente</p>'); 
            $('input#submit').attr('disabled', 'disabled');
		return false;
	} else {
	    var objeto_url = navegador.createObjectURL(archivos[x]);
	    $('#vista-previa').append('<br /><fieldset><img src="'+objeto_url+'"><input name="title[]" type="text" required value="" placeholder="Título: éste es el principal título de la foto (*)" /></fieldset>');
            $('input#submit').removeAttr('disabled'); }
            }
        });
    });
});
</script>
<h1>Cargar galería</h1>
    <form method="post" enctype="multipart/form-data" id="createArticle" action="{{ route('createPhotos') }}">
        <fieldset>
            <legend>Información</legend>
            <p><b>Autor</b>: {{ $gallery->author }}</p>
            <p><b>Fecha de publicación</b>: {{ $gallery->date }}</p> 
            <p><b>Título:</b> {{ $gallery->title }}</p>            
            <p><b>Copete:</b> {{ $gallery->article_desc }}</p>
            <input type="file" name="photo[]" id="photo" class="jfilestyle" accept="image/*" multiple required />
        </fieldset>
        <div id="error"></div>
        <div id="vista-previa"></div>
        <input type="submit" id="submit" value="CARGAR GALERÍA DE FOTOS" />
        <input type="hidden" name="gallery_id" value="{{ $gallery->id }}" />
        @csrf
    </form>
    <br />
</div>