<div id="Article-container">
    <h1>Actualizar galería de fotos</h1>
    <fieldset>
        <legend>Información</legend>
        <p><b>Autor</b>: {{ $gallery->author }}</p>
        <p><b>Fecha de publicación</b>: {{ $gallery->created_at->diffForHumans() }}</p> 
    </fieldset>
    <fieldset>
        <legend>Título y Copete</legend>
        <div class="status"></div>
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
    <input type="button" value="+ Agregar imágenes" id="enviar" class="primary" />
    @forelse ($photos as $photo)
    <fieldset data-id="{{ $photo->id }}">
        <div class="status"></div>                  
        <div class="photo-gallery-container">           
            <form action="{{ route('updatePhoto') }}" method="post" enctype="multipart/form-data">
                @csrf                    
                <input type="file" class="jfilestyle" data-placeholder="Seleccionar imagen" name="photo" accept="image/*" required />
                <input type="submit" class="actualizar-foto primary" value="Actualizar" />
                <input type="hidden" name="id" value="{{ $photo->id }}" />
                <input type="hidden" name="actual_photo" value="{{ $photo->photo }}" />
            </form>
            <img class="update-image-button" src="{{ asset('svg/update.svg') }}" title="Actualizar imagen" />
            <img class="delete-image-button" src="{{ asset('svg/delete.svg') }}" title="Borrar imagen" />
            <img class="photo-gallery" src="{{ asset('img/galleries/'.$photo->photo) }}" />
        </div> 
        <div class="status"></div>               
        <form method="post" class="data" action="{{ route('updateTitlePhotoGallery') }}">
            <p title="Editar">{{ $photo->title }}</p>                                              
            <input type="text" name="title" class="input" value="{{ $photo->title }}" placeholder="Título: éste es el principal título de la foto (*)" required />
            <input type="submit" class="actualizar-datos primary" value="Actualizar" /> 
            <input type="hidden" name="id" value="{{ $photo->id }}" />
            @csrf
        </form>                  
    </fieldset>
    @empty
    <p>No hay fotos</p>
    @endforelse
</div>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript">       
    $(document).ready(function () {                                            

        //MOSTRAR FORMULARIOS
        $('form').find('p').click(function(){

            var parrafo = $(this);
            var input = $(parrafo).next();
            var submit = $(parrafo).parent('form.data').children('input[type=submit]').css('display','none');

            $(this).fadeOut('fast', function(){
                $(submit).fadeIn('fast');
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
                $(input).fadeOut('fast', function(){ 
                    $(parrafo).fadeIn('fast');                               
                });
            });
        });

        //MOSTRAR FORM ACTUALIZAR FOTO
        $('img.update-image-button').on('click', function(){

            var form = $(this).parent('div').children('form');
            $(form).toggle('linear');
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

                } else {
                    $(divStatus).hide().append('<p class="alert-success">'+data['Exito']+'</p>').fadeIn('slow'); 
                    $(input).hide(0, function(){
                        $(this).focusout();                         
                        $(parrafoTitle).text(data['title']);
                    });                      
                    setTimeout(function(){
                        $('p.alert-success').fadeOut('slow', function(){
                            $('p.alert-success').remove();
                        });
                    }, 2000);
                }
                console.log(data);
            };

            function mostrarError(xhr){

                var errors = xhr.responseJSON.errors;                   
                $.each(errors, function(key,value){
                    $(divStatus).hide().append('<p class="invalid-feedback">'+value+'</p>').fadeIn('slow');      
                }); 
                console.log(errors);
            };               
            $('p.invalid-feedback').remove();
        });

        //ACTUALIZAR FOTO
        $('input[type=submit].actualizar-foto').click(function(){

            var div = $(this).parents('div.photo-gallery-container');
            var form = $(div).children('form');
            var formData = new FormData(form[0]);
            var divStatus = $(div).prev('div.status');
            var fotoActual =  $(div).children('img.photo-gallery'); 

            $.ajax({
                success: mostrarRespuesta,
                error: mostrarError,
                data: formData,
                url: $(form).prop('action'),
                type: $(form).prop('method'),
                processData: false,
                contentType: false,
                async: true
            });

            function mostrarRespuesta(data){ 

                if(data['Error']){

                    $(divStatus).hide().append('<p class="invalid-feedback">'+data['Error']+'</p>').fadeIn('fast'); 

                } else {

                    $(divStatus).hide().append('<p class="alert-success">'+data['Exito']+'</p>').fadeIn('slow'); 
                    $(form).hide(0, function(){
                        $(fotoActual).remove();
                        $(form).children('input[name=actual_photo]').attr('value', data['Imagen']);
                        $(div).prepend('<img class="photo-gallery" src="img/galleries/'+data['Imagen']+'" />');
                    });                      
                    setTimeout(function(){
                        $('p.alert-success').fadeOut('slow', function(){
                            $('p.alert-success').remove();
                        });
                    }, 2000);                        
                } console.log(data);
            };

            function mostrarError(xhr){

                var errors = xhr.responseJSON.errors;                   
                $.each(errors, function(key,value){
                    $(divStatus).hide().append('<p class="invalid-feedback">'+value+'</p>').fadeIn('slow');      
                }); 
                console.log(errors);
            };               
            $('p.invalid-feedback').remove();
        });  

        //ELIMINAR FOTO
        $('img.delete-image-button').click(function(){

            $('p.invalid-feedback').remove();

            var fieldset = $(this).parents('fieldset');
            var id = $(fieldset).data('id');

            if (confirm("¿Estás seguro que quieres eliminar la imagen?")) {

                $.ajax({
                    url: '{!! route('deletePhotoGallery') !!}',
                    type: 'POST',
                    data: { 'id': id, '_token': '{!! csrf_token() !!}' },
                    success: function(data){
                        if(data['Error']){

                            $('<p class="invalid-feedback">'+data['Error']+'</p>').prependTo(fieldset).hide().fadeIn('slow');

                        } else {

                            $(fieldset).fadeOut('slow', function(){
                                $(fieldset).before('<p class="alert-success">'+data['Exito']+'</p>').fadeIn('slow');
                                $(fieldset).remove();                               
                            });
                            setTimeout(function(){
                                $('p.alert-success').fadeOut('slow', function(){
                                    $('p.alert-success').remove();
                                });
                            }, 2000);
                        }
                    },
                    error: function(data){
                        $('<p class="invalid-feedback">'+data['Error']+'</p>').prependTo(fieldset).hide().fadeIn('slow');
                    }
                });

            } return false;
        });

        //CARGAR MAS FOTOS
        $('input#enviar').click(function(){

            $('p.invalid-feedback').remove();
            $.ajax({
                beforeSend: function(){

                    $('#Article-container').hide(0);
                    $('#loader').fadeIn('slow');
                },
                url: '{!! route('morePhotos') !!}',
                type: 'POST',
                data: { 'id': '{!! $gallery->id !!}', '_token': '{!! csrf_token() !!}' },                  
                success: function(data){
                    if(data['Error']){
                        $('<p class="invalid-feedback">'+data['Error']+'</p>').prependTo('fieldset#cargar').hide().fadeIn('slow');
                    } else {
                        $('#loader').fadeOut('slow', function () {                        
                            $('#user-content').html(data);
                        });
                    }
                },
                error: function(data){
                    $('<p class="invalid-feedback">'+data['Error']+'</p>').prependTo('fieldset#cargar').hide().fadeIn('slow');
                }
            });
        });
    });
</script>