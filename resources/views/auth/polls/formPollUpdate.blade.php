<div id="Article_Form">
<h1>Actualizar encuesta</h1>  
    <fieldset>
        <legend>Información</legend>
        <p><b>Autor</b>: {{ $poll->author }}</p>
        <p><b>Fecha de publicación</b>: {{ $poll->created_at->diffForHumans() }}</p> 
    </fieldset>
    <fieldset>
        <legend>Título y Copete</legend>
        <div class="status"></div>
            <form method="post" class="data" enctype="multipart/form-data" action="{{ route('updatePoll') }}">
                <p title="Editar">{{ $poll->title }}</p>
                <input type="text" class="input" name="title" value="{{ $poll->title }}" placeholder="Título: éste es el principal título de la encuesta (*) Mínimo 20 caracteres" required />
                <p title="Editar">{{ $poll->article_desc }}</p>
                <textarea name="article_desc" class="input" placeholder="Copete: puedes incluir el primer párrafo de tu encuesta (*) Mínimo 50 caracteres" required>{{ $poll->article_desc }}</textarea>
                <input type="submit" value="ACTUALIZAR" class="success" />
                @csrf
                <input type="hidden" name="id" value="{{ $poll->id }}" />
                <input type="hidden" name="user_id" value="{{ $poll->user_id }}" />
                <input type="hidden" name="author" value="{{ $poll->author }}" />
                <input type="hidden" name="section_id" value="{{ $poll->section_id }}" />   
            </form>
    </fieldset>
    <div class="status"></div>
    <input type="button" id="mas-opciones" data-id="{{ $poll->id }}" value="+ AGREGAR MAS OPCIONES" />
    @forelse ($options as $option)
    <div class="status"></div>
    <fieldset>
        <div class="status"></div>
        <form method="post" class="data" enctype="multipart/form-data" action="{{ route('updatePollOption') }}">
            <p title="Editar">{{ $option->option }}</p>
            <input type="text" class="input" name="option" value="{{ $option->option }}" placeholder="Opción de la encuesta" required />
            <input type="submit" value="ACTUALIZAR" class="success" />
            <input type="button" class="danger" data-id="{{ $option->id }}" value="BORRAR" />
            @csrf
            <input type="hidden" name="poll_id" value="{{ $poll->id }}" /> 
            <input type="hidden" name="id" value="{{ $option->id }}" />
        </form>
    </fieldset>    
    @empty
    <p>No hay opciones cargadas</p>
    @endforelse   
</div>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript">       
$(document).ready(function () {                                            

    //MOSTRAR FORMULARIOS
    $('fieldset form p').click(function(){

        var parrafo = $(this);
        var input = $(parrafo).next();
        var submit = $(parrafo).parent('form.data').children('input[type=submit]');
        var deleteBtn = $(parrafo).parent('form.data').children('input[type=button]');

        $(this).fadeOut('fast', function(){
            $(submit).css('display','inline-block');
            $(deleteBtn).css('display','inline-block');
            $(input).fadeIn('fast', function(){                           
                $(input).focus();
                $(input).on('keyup', function(){
                    var valor = $(this).val();
                    $(parrafo).text(valor);
                });
            });
        });

        $(input).on('focusout', function(){
            $(submit).fadeOut('fast');
            $(deleteBtn).fadeOut('fast');
            $(input).fadeOut('fast', function(){ 
                $(parrafo).fadeIn('fast');                               
            });
        });
    });

    //BORRAR OPCIÓN
    $('input[type=button].danger').on('click', function () {

        var id = $(this).data('id');
        var fieldset = $(this).parents('fieldset');
        var divStatus = $(fieldset).prev('div.status');

        if (confirm("¿Estás seguro que quieres borrar la opción de la encuesta?")) {

            $.ajax({
                success: mostrarRespuesta,
                error: mostrarError,
                data: {'id': id, '_token': '{!! csrf_token() !!}'},
                url: '{!! route('deletePollOption') !!}',
                type: 'post',
                datatype: 'json',
                async: true
            });

            function mostrarRespuesta(data){ 

                if(data['Error']){

                    $(divStatus).hide().append('<p class="invalid-feedback">'+data['Error']+'</p>').fadeIn('fast'); 

                } else {

                    $(divStatus).hide().append('<p class="alert-success">'+data['Exito']+'</p>').fadeIn('slow'); 
                    $(fieldset).hide(0, function(){
                        $(this).remove();
                    });                      
                    setTimeout(function(){
                        $('p.alert-success').fadeOut('slow', function(){
                            $('p.alert-success').remove();
                        });
                    }, 2000);  
                }
            };

            function mostrarError(xhr){

                var errors = xhr.responseJSON.errors;                   
                $.each(errors, function(key,value){
                    $(divStatus).hide().append('<p class="invalid-feedback">'+value+'</p>').fadeIn('slow');      
                });
            }; 
            $('p.invalid-feedback').remove();                      
        } return false;
        
    });

    //ACTUALIZAR DATOS
    $('input[type=submit]').on('click', function(event){
        event.stopPropagation();
        event.preventDefault();

        var form = $(this).parent('form');
        var input = $(form).children('.input');
        var parrafoTitle = $(form).children('p');
        var parrafoArticleDesc = $(parrafoTitle).next().next();
        var divStatus = $(form).prev('div.status');

        $.ajax({
            success: mostrarRespuesta,
            error: mostrarError,
            data: $(form).serialize(),
            url: $(form).prop('action'),
            type: $(form).prop('method'),
            datatype: 'json',
            async: true
        });

        function mostrarRespuesta(data){ 

            if(data['Error']){

                $(divStatus).hide().append('<p class="invalid-feedback">'+data['Error']+'</p>').fadeIn('fast'); 

            } else if(data['article_desc']) {

                $(divStatus).hide().append('<p class="alert-success">'+data['Exito']+'</p>').fadeIn('slow'); 
                $(input).hide(0, function(){
                    $(this).focusout();
                    $(parrafoTitle).text(data['title']);
                    $(parrafoArticleDesc).text(data['article_desc']); 
                });                      
                setTimeout(function(){
                    $('p.alert-success').fadeOut('slow', function(){
                        $('p.alert-success').remove();
                    });
                }, 2000);  

            } else if(data['option']) {
                $(divStatus).hide().append('<p class="alert-success">'+data['Exito']+'</p>').fadeIn('slow'); 
                $(input).hide(0, function(){
                    $(this).focusout();                         
                    $(parrafoTitle).text(data['option']);
                });                      
                setTimeout(function(){
                    $('p.alert-success').fadeOut('slow', function(){
                        $('p.alert-success').remove();
                    });
                }, 2000);
            }
        };

        function mostrarError(xhr){

            var errors = xhr.responseJSON.errors;                   
            $.each(errors, function(key,value){
                $(divStatus).hide().append('<p class="invalid-feedback">'+value+'</p>').fadeIn('slow');      
            });
        };               
        $('p.invalid-feedback').remove();
    });

    //AGREGAR MAS OPCIONES
    $('#mas-opciones').on('click', function(event){
        
        event.preventDefault();

        var divStatus = $(this).prev('div.status');
        var id = $(this).data('id');

        $.ajax({
            beforeSend: mostrarLoader,
            success: mostrarRespuesta,
            error: mostrarError,
            data: {'id': id, '_token': '{!! csrf_token() !!}'},
            url: '{!! route('addMorePollOption') !!}',
            type: 'post',
            datatype: 'json',
            async: true
        });
        function mostrarLoader() {

            $('#Article_Form').hide(0);
            $('#loader').fadeIn('slow');
        };

        function mostrarRespuesta(data){ 

            if(data['Error']) {
                $(divStatus).hide().append('<p class="invalid-feedback">'+data['Error']+'</p>').fadeIn('fast');

            } else {
                $('#loader').fadeOut('slow', function () {
                    $('#user-content').html(data).fadeIn('fast'); 
                });              
            }
        };

        function mostrarError(xhr){

            var errors = xhr.responseJSON.errors;                   
            $.each(errors, function(key,value){
                $(divStatus).hide().append('<p class="invalid-feedback">'+value+'</p>').fadeIn('slow');      
            });
        };               
        $('p.invalid-feedback').remove();
    });
});
</script>