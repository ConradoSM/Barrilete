<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando galería de fotos...
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >  
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
    <h1>Editar usuario</h1>
    <div id="action">
        @if (Auth::user()->is_admin)
        <a href="{{ route('users') }}" class="primary">Volver al listado</a>
        @else
        <a href="{{ route('account', ['id' => Auth::user()->id]) }}" class="primary">Ver perfil</a>
        @endif
        <a href="#" class="danger">Borrar</a>
    </div>
    <hr />
    <fieldset>   
        <legend>Editar Información</legend>
        <div id="errors"></div>
        <form action="{{ route('updateUser') }}" enctype="multipart/form-data" method="post" id="createArticle">
            <input type="text" name="name" value="{{ $user->name }}" placeholder="Nombre" required />
            <input type="email" name="email" value="{{ $user->email }}" placeholder="Correo electrónico (*) Obligatorio" required /> 
            <input type="file" name="photo" class="jfilestyle" data-inputSize="500px" data-placeholder="Foto, 1MB Max. sólo imágenes JPG, JPEG, PNG" accept=".png, .jpg, .jpeg" />
            <hr />
            <input type="text" name="birthday" value="{{ $user->birthday }}" placeholder="Fecha de nacimiento" />
            <input type="text" name="phone" value="{{ $user->phone }}" placeholder="Número de teléfono" />
            <input type="text" name="address" value="{{ $user->address }}" placeholder="Dirección" />
            <input type="text" name="city" value="{{ $user->city }}" placeholder="Ciudad" />
            <input type="text" name="state" value="{{ $user->state }}" placeholder="Provincia o Estado" />
            <input type="text" name="country" value="{{ $user->country }}" placeholder="País" />
            <hr />
            <textarea name="description" placeholder="Acerca de mí">{{ $user->description }}</textarea>
            <input type="submit" class="success" value="Actualizar" />
            <input type="reset" class="primary" value="Restablecer" />
            <input type="hidden" name="id" value="{{ $user->id }}" />
            @csrf
        </form>
    </fieldset>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.filestyle.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form-galleries.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){        
        $('div#action').find('a').each(function () {
            var href = $(this).attr('href');
            $(this).attr({href: '#'});            
            $(this).click(function(){
                $('#loader').fadeIn('fast');
                $('#user-content').hide(0, function () {
                    $('#user-content').load(href, function () {
                        $('#loader').fadeOut('fast', function () {
                            $('#user-content').fadeIn('fast');
                        });
                    });
                });
            });
        });
    });
</script>