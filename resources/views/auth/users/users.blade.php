@if (Auth::user()->is_admin)
<h1>Lista de usuarios</h1>
@if (session('success'))
<p class="alert-success">{{ session('success') }}</p>
@elseif (session('error'))
<p class="invalid-feedback">{{ session('error') }}</p>
@endif
<table class="table">
  <thead>
    <tr>
      <th>Rol</th>
      <th>Nombre</th>
      <th>Correo electrónico</th>
      <th width="23%">Acción</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($users as $user)
    <tr>
      <td>@if ($user->is_admin) Admin @else User @endif</td>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td>
          <a href="{{ route('showUser', ['id' => $user->id]) }}" class="primary-small" title="Ver usuario" id="ver">Ver</a>
          <a href="{{ route('editUser', ['id' => $user->id]) }}" class="success-small" title="Editar usuario" id="editar">Editar</a>
          @if (!(Auth::user()->id == $user->id))
          <a href="{{ route('deleteUser', ['id' => $user->id]) }}" class="danger-small" title="Borrar usuario" id="borrar">Borrar</a>
          @else
          <a href="{{ route('options') }}" class="danger-small" title="Borrar cuenta" id="borrar">Borrar</a>
          @endif
      </td>
    </tr>
    @empty
    <tr>
        <td>No hay usuarios</td>
    </tr>
    @endforelse
  </tbody>
</table>
<script type="text/javascript" src="{{asset('js/dashboard-admin-users.js')}}"></script>
@else
<p class="invalid-feedback">Error: no eres administrador del sistema</p>
@endif