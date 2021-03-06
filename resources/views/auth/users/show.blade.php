<h1>Detalle del usuario</h1>
@if (isset($success))
    <p class="alert feedback-success">{{ $success }}</p>
@endif
<div id="action">
    @if (Auth::user()->authorizeRoles([\barrilete\User::ADMIN_USER_ROLE]))
        <a href="{{ route('users') }}" title="Volver la lista de usuarios del sitio" class="button primary">Volver al listado</a>
        <a href="{{ route('editUser', ['id' => $user->id]) }}" title="Editar perfil del usuario" class="button success">Editar</a>
        @if (!(Auth::user()->id == $user->id))
        <a href="{{ route('deleteUser', ['id' => $user->id]) }}" title="Borrar usuario del sistema" class="button danger" data-confirm="¿Estás seguro que quieres borrar éste usuario?">Borrar</a>
        @endif
        @else
        <a href="{{ route('options') }}" class="button primary" title="Ver opciones">Opciones</a>
        <a href="{{ route('editUser', ['id' => $user->id]) }}" title="Editar mi perfil" class="button success">Editar</a>
    @endif
</div>
<fieldset>
    <div class="user-info">
        @if ($user->photo)
        <img class="user-photo" src="{{ asset('img/users/images/'.$user->photo) }}" alt="{{ $user->name }}" />
        @else
        <img class="user-photo" src="{{ asset('svg/avatar.svg') }}" alt="{{ $user->name }}" />
        @endif
        <div>
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }} - <b>Rol</b>: {{ ucfirst($user->roles->first()->name) }}</p>
        </div>
    </div>
</fieldset>
<h3>Información</h3>
<fieldset>
    <p><b>Edad</b>: <span class="info">{{ Carbon\Carbon::parse($user->birthday)->age.' años' }}</span></p>
    <p><b>Teléfono</b>: <span class="info">{{ $user->phone }}</span></p>
    <p><b>Dirección</b>: <span class="info">{{ $user->address }}</span></p>
    <p><b>Ciudad</b>: <span class="info">{{ $user->city }}</span></p>
    <p><b>Provincia</b>: <span class="info">{{ $user->state }}</span></p>
    <p><b>País</b>: <span class="info">{{ $user->country }}</span></p>
    <hr />
    <p><b>Acerca de mi</b>: <span class="info">{{ $user->description }}</span></p>
</fieldset>
<h3>Actividad</h3>
<fieldset>
    <p><b>Artículos</b>: <span class="info">{{ $user->articles->count() }}</span></p>
    <p><b>Galerías</b>: <span class="info">{{ $user->gallery->count() }}</span></p>
    <p><b>Encuestas</b>: <span class="info">{{ $user->poll->count() }}</span></p>
</fieldset>
<script type="text/javascript" src="{{asset('js/dashboard.js')}}"></script>
