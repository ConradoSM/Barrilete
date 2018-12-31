<h2>Galería status</h2>
@if ($status == 'success_delete')
	<p class="alert-success">La galería de fotos se ha eliminado correctamente del sistema.</p>
@elseif ($status == 'error_delete')
	<p class="invalid-feedback">Ha ocurrido un error al borrar la galería de fotos.</p>
@elseif ($status == 'error_find')
	<p class="invalid-feedback">La galería no existe.</p>
@elseif ($status == 'error_publish')
	<p class="invalid-feedback">Debes ser administrador del sitio para publicar contenido.</p>
@endif