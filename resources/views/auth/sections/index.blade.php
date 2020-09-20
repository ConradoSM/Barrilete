<h1>Lista de secciones</h1>
<div id="action">
    <a href="{{ route('newSection') }}" class="button primary" title="Nueva sección">+ Nueva sección</a>
</div>
@if (isset($success))
<p class="alert feedback-success">{{ $success }}</p>
@elseif (isset($error))
<p class="alert feedback-error">{{ $error }}</p>
@endif
<table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Prioridad</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($sections as $section)
    <tr>
      <td>{{ $section->id }}</td>
      <td>{{ ucfirst($section->name) }}</td>
      <td>{{ $section->prio }}</td>
      <td>
          <a href="{{ route('editSection', ['id' => $section->id]) }}" class="button success small" title="Editar sección">Editar</a>
          <a href="{{ route('deleteSection', ['id' => $section->id]) }}" class="button danger small" title="Borrar sección" data-confirm="¿Estás seguro que deseas borrar la sección?">Borrar</a>
      </td>
    </tr>
    @empty
    <tr>
        <td colspan="4">No hay secciones</td>
    </tr>
    @endforelse
  </tbody>
</table>
<script type="text/javascript" src="{{asset('js/dashboard.js')}}"></script>
