@if (Auth::user()->is_admin)
<h1>Lista de usuarios</h1>
<table class="table">
  <thead>
    <tr>
      <th>Rol</th>
      <th>Nombre</th>
      <th>Correo electrónico</th>
      <th>Acción</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($users as $user)
    <tr>
      <td>@if ($user->is_admin) Admin @else User @endif</td>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td>
          <a href="{{ route('showUser', ['id' => $user->id]) }}" class="primary-small" title="Ver usuario">Ver</a>
          <a href="{{ route('editUser', ['id' => $user->id]) }}" class="success-small" title="Editar usuario">Editar</a>
          <a href="#" class="danger-small" title="Borrar usuario">Borrar</a>
      </td>
    </tr>
    @empty
    <tr>
        <td>No hay usuarios</td>
    </tr>
    @endforelse
  </tbody>
</table>
@else
<p class="invalid-feedback">Error: no eres administrador del sistema</p>
@endif
<script type="text/javascript">
    $(document).ready(function(){        
        $('td').find('a').each(function () {
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