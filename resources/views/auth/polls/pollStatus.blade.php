<h2>Borrar encuesta</h2>
@if ($status == 'success')
	<p class="alert-success">La encuesta se ha eliminado correctamente del sistema.</p>
@elseif ($status == 'error_delete')
	<p class="invalid-feedback">Ha ocurrido un error al borrar la encuesta.</p>
@elseif ($status == 'error_find')
	<p class="invalid-feedback">La encuesta no existe.</p>
@endif