<h1>Opciones</h1>
<fieldset>
    <div class="user-options">
        <img src="{{ asset('svg/admin.svg') }}" alt="General" />
        <p><a href="{{ route('account', ['id' => Auth::user()->id]) }}">General</a> Actualiza tus datos personales y preferencias</p>
    </div>
</fieldset>
@if (Auth::user()->authorizeRoles([\barrilete\User::ADMIN_USER_ROLE]))
<fieldset>
    <div class="user-options">
        <img src="{{ asset('svg/avatar.svg') }}" alt="Usuarios" />
        <p><a href="{{ route('users') }}">Usuarios</a> Ver todos los usuarios del sitio, administrar roles</p>
    </div>
</fieldset>
<fieldset>
    <div class="user-options">
        <img src="{{ asset('svg/clipboard.svg') }}" alt="Secciones"/>
        <p><a href="{{ route('sectionsIndex') }}">Secciones</a> Ver, agregar, modificar las secciones del sitio</p>
    </div>
</fieldset>
@endif
<fieldset>
    <div class="user-options">
        <img src="{{ asset('svg/delete-account.svg') }}" alt="Eliminar Cuenta"/>
        <p><a href="{{ route('deleteUser', ['id' => Auth::user()->id]) }}" data-confirm="¿Estás seguro que quieres eliminar tu cuenta?">Eliminar cuenta</a> Dar de baja al perfil, ten en cuenta que tus artículos no se borraran</p>
    </div>
</fieldset>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
