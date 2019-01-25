<div id="Article_Form">
    <h1>Cargar encuesta</h1>
    <form method="post" enctype="multipart/form-data" id="createArticle" action="{{ route('createOptions') }}">
        <fieldset>
            <legend>Información</legend>
            <div id="errors"></div>
            <p><b>Autor</b>: {{ $poll->author }}</p>
            <p><b>Fecha de publicación</b>: {{ $poll->created_at->diffForHumans() }}</p> 
            <p><b>Título:</b> {{ $poll->title }}</p>            
            <p><b>Copete:</b> {{ $poll->article_desc }}</p>           
        </fieldset>
        <input type="button" id="agregarCampo" value="+ AGREGAR OPCIÓN" />
        <div id="campos"></div>
        <input type="submit" id="submit" value="GUARDAR" class="disabled" disabled  />
        <input type="hidden" name="poll_id" value="{{ $poll->id }}" />
        @csrf
    </form>
    <br />
</div>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form-submit.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    //AGREGAR OPCIONES
    var MaxInputs = 5;
    var contenedor = $('#campos');
    var AddButton = $('#agregarCampo');
    var x = $('#campos div').length + 1;
    var FieldCount = x - 1;

    $(AddButton).click(function(event){
        event.stopPropagation();
        event.preventDefault;
            
        if (x > 2) {
            $('input#submit').removeAttr('disabled').removeClass('disabled').addClass('primary');
        }
    
        if (x <= MaxInputs) {         
            FieldCount++;
            $('<fieldset><input type="text" name="option[]" id="campo_' + FieldCount + '" placeholder="Opción N°' + FieldCount + '" required /><img src="/svg/delete.svg" class="eliminar" title="Eliminar" /></fieldset>').appendTo(contenedor).hide().fadeIn(400, 'linear');
            x++;
        }
        return false;
    });
        
    $('img.eliminar').on('click', function(event){
        event.stopPropagation();
        event.preventDefault;
        
        if (x > 1) {
            $(this).parent('fieldset').fadeOut(400, 'linear');
            x--;
        }
        return false;
    });
});
</script>