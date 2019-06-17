<h1>Opciones</h1>
<fieldset class="ajax">
    <img class="avatar" src="{{ asset('svg/admin.svg') }}" />
    <div class="user-info">
        <a href="{{ route('account', ['id' => Auth::user()->id]) }}">General</a>
        <p>Actualiza tus datos personales y preferencias</p>
    </div>
</fieldset>
@if (Auth::user()->is_admin)
<fieldset class="ajax">
    <img class="avatar" src="{{ asset('svg/avatar.svg') }}" />
    <div class="user-info">
        <a href="{{ route('users') }}">Usuarios</a>
        <p>Ver todos los usuarios del sitio, administrar roles</p>
    </div>
</fieldset>
<fieldset class="ajax">
    <img class="avatar" src="{{ asset('svg/clipboard.svg') }}" />
    <div class="user-info">
        <a href="{{ route('sectionsIndex') }}">Secciones</a>
        <p>Ver, agregar, modificar las secciones del sitio</p>
    </div>
</fieldset>
@endif
<fieldset>
    <img class="avatar" src="{{ asset('svg/delete-account.svg') }}" />
    <div class="user-info">
        <a href="{{ route('deleteUser', ['id' => Auth::user()->id]) }}" onclick="return confirm('¿Estás seguro que quieres eliminar tu cuenta?')">Eliminar cuenta</a>
        <p>Dar de baja al perfil, ten en cuenta que tus artículos no se borraran</p>
    </div>
</fieldset>
<script type="text/javascript">
    $(document).ready(function(){
        $('fieldset.ajax').find('a').click(function(event){
            event.preventDefault();
            var href = $(this).attr('href');
            $(this).attr({href: '#'});
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
</script>