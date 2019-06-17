@if (Auth::user()->is_admin)
<h1>Lista de secciones</h1>
<a href="{{ route('newSection') }}" class="primary" title="Nueva sección" id="crear">+ Nueva sección</a>
@if (session('success'))
<p class="alert-success">{{ session('success') }}</p>
@elseif (session('error'))
<p class="invalid-feedback">{{ session('error') }}</p>
@endif
<table class="table">
  <thead>
    <tr>
      <th width="10%">ID</th>
      <th width="22%">Nombre</th>
      <th width="50%">Prioridad</th>
      <th width="18%">Acción</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($sections as $section)
    <tr>
      <td>{{ $section->id }}</td>
      <td>{{ ucfirst($section->name) }}</td>
      <td>{{ $section->prio }}</td>
      <td>
          <a href="{{ route('editSection', ['id' => $section->id]) }}" class="success-small" title="Editar sección" id="editar">Editar</a>
          <a href="{{ route('deleteSection', ['id' => $section->id]) }}" class="danger-small" title="Borrar sección" id="borrar">Borrar</a>
      </td>
    </tr>
    @empty
    <tr>
        <td>No hay secciones</td>
    </tr>
    @endforelse
  </tbody>
</table>
<script type="text/javascript" src="{{asset('js/dashboard-admin-users.js')}}"></script>
@else
<p class="invalid-feedback">Error: no eres administrador del sistema</p>
@endif