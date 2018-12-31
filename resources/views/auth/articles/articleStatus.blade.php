<h2>Artículo status</h2>
@if ($status == 'success')
	<p class="alert-success">El artículo se ha eliminado correctamente del sistema.</p>
@elseif ($status == 'error_delete')
	<p class="invalid-feedback">Ha ocurrido un error al borrar el artículo.</p>
@elseif ($status == 'error_find')
	<p class="invalid-feedback">El artículo no existe.</p>
@elseif ($status == 'error_publish')
	<p class="invalid-feedback">Debes ser administrador del sitio para publicar contenido.</p>       
@endif