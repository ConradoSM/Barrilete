<div id="Article-container">
    <h1>Actualizar galería de fotos</h1>
        <fieldset>
            <legend>Información</legend>
            <p><b>Autor</b>: {{ $gallery->user->name }}</p>
            <p><b>Fecha de publicación</b>: {{ $gallery->date }}</p> 
        </fieldset>
    <form id="titulo">
        @csrf
        <input type="hidden" name="user_id" value="{{ $gallery->user->id }}" />
        <input type="hidden" name="date" value="{{ $gallery->date }}" />
        <input type="hidden" name="author" value="{{ $gallery->author }}" />
        <input type="hidden" name="section_id" value="{{ $gallery->section_id }}" />
        <input type="hidden" name="id" value="{{ $gallery->id }}" />
        <input type="hidden" name="article_desc" value="{{ $gallery->article_desc }}" />
        <fieldset>
            <legend>Título y Copete</legend>
            <div id="errors"></div>
            <div id="container-gallery-title">
                <p onclick="verInputTitleGallery()" id="gallery-title" title="Editar título" class="photo-title">{{ $gallery->title }}</p>
            </div>
            <div id="container-gallery-title-input" class="title-photo">
                <img class="update-button" src="/svg/update.svg" onclick="actualizarTituloGaleria({{$gallery->id}})" title="Actualizar título" />                            
                <input type="text" name="title" id="title" value="{{ $gallery->title }}" placeholder="Título: éste es el principal título de la foto (*)" required />
            </div>           
        </fieldset>
    </form>
    <form id="copete">
        @csrf
        <input type="hidden" name="user_id" value="{{ $gallery->user->id }}" />
        <input type="hidden" name="date" value="{{ $gallery->date }}" />
        <input type="hidden" name="author" value="{{ $gallery->author }}" />
        <input type="hidden" name="section_id" value="{{ $gallery->section_id }}" />
        <input type="hidden" name="id" value="{{ $gallery->id }}" />
        <input type="hidden" name="title" value="{{ $gallery->title }}" />
        <fieldset>
            <div id="container-gallery-copete">
                <p onclick="verInputCopeteGallery()" id="copete" title="Editar copete" class="photo-title">{{ $gallery->article_desc }}</p>
            </div>
            <div id="container-gallery-copete-input" class="title-photo">                                            
                <textarea name="article_desc" id="copete" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*)" required>{{ $gallery->article_desc }}</textarea>
                <a class="edit" href="#" onclick="actualizarCopeteGaleria({{$gallery->id}})" title="Actualizar copete">Actualizar</a>
            </div> 
        </fieldset>
    </form>
        @forelse ($photos as $photo)
        <fieldset id="{{$photo->id}}">
            <img class="delete-button" src="/svg/delete.svg" onclick="eliminarFoto({{$photo->id}})" title="Eliminar foto" />
            <div id="errors"></div>
            <img class="photo-gallery" src="/img/galleries/{{ $photo->photo }}" />
            <div id="title-{{$photo->id}}">
                <p onclick="verInputTitleFoto({{$photo->id}})" id="p-{{$photo->id}}" title="Editar título" class="photo-title">{{ $photo->title }}</p>
            </div>
            <div id="input-{{$photo->id}}" class="title-photo">
                <img class="update-button" src="/svg/update.svg" onclick="actualizarTituloFoto({{$photo->id}})" title="Actualizar título" />                            
                <input type="text" name="title" id="input-title-{{$photo->id}}" value="{{ $photo->title }}" placeholder="Título: éste es el principal título de la foto (*)" required />
            </div>            
        </fieldset>
        @empty
        <p>No hay fotos</p>
        @endforelse
    <script type="text/javascript">
        //MOSTRAR INPUT TITULO
        function verInputTitleGallery() {
            $('div#container-gallery-title').fadeOut('fast', function(){
                $('div#container-gallery-title-input').fadeIn('fast', function(){ 
                   $('input#title').focus();
                });    
            });
            
            $('input#title').focusout(function(){
                $('div#container-gallery-title-input').fadeOut('fast', function(){ 
                   $('div#container-gallery-title').fadeIn();
                });               
            });
        } 
        //MOSTRAR TEXTAREA COPETE
        function verInputCopeteGallery(id) {
            $('div#container-gallery-copete').fadeOut('fast', function(){
                $('div#container-gallery-copete-input').fadeIn('fast', function(){ 
                   $('textarea#copete').focus(); 
                });    
            });
            
            $('textarea#copete').focusout(function(){
                $('div#container-gallery-copete-input').fadeOut('fast', function(){ 
                   $('div#container-gallery-copete').fadeIn();
                });               
            });
        }
        //MOSTRAR INPUT FOTOS
        function verInputTitleFoto(id) {
            $('div#title-'+id).fadeOut('fast', function(){
                $('div#input-'+id).fadeIn('fast', function(){ 
                   $('input#input-title-'+id).focus(); 
                });    
            });
            
            $('input#input-title-'+id).focusout(function(){
                $('div#input-'+id).fadeOut('fast', function(){ 
                   $('div#title-'+id).fadeIn();
                });               
            });
        }
        //ACTUALIZAR TITULO GALERIA
        function actualizarTituloGaleria(id) {
            $.ajax({
                url: '/dashboard/update/gallery/title/'+id,
                type: 'post',
                data: $('form#titulo').serialize(),
                error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key,value) {
                            $('#errors').append('<p class="invalid-feedback">'+value+'</p>');       
                        });
                    console.log(errors);
                },
                success: function(response) {
                    $('p#gallery-title').remove();
                    $('div#container-gallery-title-input').fadeOut('fast', function() {                       
                        $('div#container-gallery-title').append('<p onclick="verInputTitleGallery()" id="gallery-title" title="Editar título" class="photo-title">'+response+'</p>').show();    
                        }); 
                    console.log(response);
                }
            });
            $('p.invalid-feedback').hide();
        }
        //ACTUALIZAR COPETE GALERIA
        function actualizarCopeteGaleria(id) {
            $.ajax({
                url: '/dashboard/update/gallery/article_desc/'+id,
                type: 'post',
                data: $('form#copete').serialize(),
                error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key,value) {
                            $('#errors').append('<p class="invalid-feedback">'+value+'</p>');       
                        });
                    console.log(errors);
                },
                success: function(response) {
                    $('p#copete').remove();
                    $('div#container-gallery-copete-input').fadeOut('fast', function() {                       
                        $('div#container-gallery-copete').append('<p onclick="verInputCopeteGallery()" id="copete" title="Editar copete" class="photo-title">'+response+'</p>').show();    
                        }); 
                    console.log(response);
                }
            });
            $('p.invalid-feedback').hide();
        }
        //ACTUALIZAR TITULO FOTOS
        function actualizarTituloFoto(id) {
            $.ajax({
                url: '/dashboard/update/gallery/photo/title/'+id,
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                type: 'post',
                data: $('#input-title-'+id).serialize(),
                success: function(response) {
                    $('p#p-'+id).remove();
                    $('div#input-'+id).fadeOut('fast', function() {                       
                        $('div#title-'+id).append('<p onclick="verInputTitleFoto('+id+')" id="p-'+id+'" title="Editar título" class="photo-title">'+response+'</p>').show();    
                        }); 
                    console.log(response);
                }
            });
        }        
        //ELIMINAR FOTO Y TITULO
        function eliminarFoto(id) {
            if (confirm("¿Estás seguro que quieres eliminar la foto?")) {
                $.ajax({
                   url: '/dashboard/delete/gallery/photo/' + id,
                   headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                   type: 'post',
                   success: function(response) {
                       $('fieldset#'+id).slideUp('slow');
                       console.log(response);
                   }
                });
            } return false;
        }
    </script>
</div>