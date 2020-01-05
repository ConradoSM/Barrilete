<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando galería de fotos...
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
<h1>Cargar imágenes</h1>
    <form method="post" enctype="multipart/form-data" action="{{ route('createPhotos') }}">
        <fieldset>
            <legend>Información</legend>
            <div id="errors"></div>
            <p><b>Autor</b>: {{ $gallery->author }}</p>
            <p><b>Fecha de publicación</b>: {{ $gallery->created_at->diffForHumans() }}</p>
            <p><b>Título:</b> {{ $gallery->title }}</p>
            <p><b>Copete:</b> {{ $gallery->article_desc }}</p>
            <input type="file" name="photo[]" id="photo" class="jfilestyle" data-inputSize="500px" data-placeholder="Imagen Principal (*) Oligatoria, sólo imágenes JPG, JPEG, PNG" accept=".png, .jpg, .jpeg" multiple required />
        </fieldset>
        <div id="error"></div>
        <div id="preview"></div>
        <input type="submit" id="submit" value="Guardar" class="button disabled" disabled />
        <input type="hidden" name="gallery_id" value="{{ $gallery->id }}" />
        @csrf
    </form>
    <br />
</div>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $(':file').jfilestyle({ buttonText: 'EXAMINAR...'});
    $('#photo').on('change', function() {
        $('#preview').html('');
        const files = document.getElementById('photo').files;
        const browser = window.URL || window.webkitURL;
        for(x=0; x<files.length; x++) {
            const type = files[x].type;
            const name = files[x].name;
            if(type !== 'image/jpeg' && type !== 'image/jpg' && type !== 'image/png') {
                $('#error').append('<p class="alert feedback-error">El archivo <b>'+name+'</b> no es del tipo de imagen permitida, sólo se admiten archivos JPG, JPEG o PNG, por favor selecciona los archivos nuevamente</p>');
                $('input#submit').attr('disabled','disabled');
                return false;
            } else {
                const object_url = browser.createObjectURL(files[x]);
                $('#preview').append('<fieldset><img src="'+object_url+'"><input name="title[]" type="text" required value="" placeholder="Título: éste es el principal título de la foto (*) Mínimo 20 caracteres" /></fieldset>');
                $('input#submit').removeAttr('disabled').removeClass('disabled').addClass('primary');
            }
        }
    });
});
</script>
