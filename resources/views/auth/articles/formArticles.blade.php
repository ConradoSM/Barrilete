<h1>Cargar artículo</h1>
<form enctype="multipart/form-data" id="Content_Insert" action="/System/ScriptsPHP/Content_Insert.php" method="post">
	<fieldset>
		<legend>Información</legend>
			<p><b>Autor</b>: {{ Auth::user()->name }}</p>
			<p><b>Fecha de publicación</b>: </p>
			<select required name="seccion" size="1" id="seccion">
				<option value="" selected hidden>Seleccionar Sección</option>
				<option value="Sociedad">Sociedad</option>
				<option value="Politica">Política</option>
				<option value="Economia">Economía</option>
				<option value="Internacionales">Internacionales</option>
				<option value="Deportes">Deportes</option>
				<option value="Cultura">Cultura</option>
				<option value="Tecno">Tecno</option>
				<option value="Editoriales">Editoriales</option>
			</select>
			<input type="file" class="jfilestyle" data-placeholder="Imagen Principal" id="foto" name="foto[]" required />
			<p><input type="checkbox" name="video" id="video" value="1" /> El artículo tiene video incrustado de YouTube u otras fuentes.</p>
	</fieldset>
<hr />
	<fieldset>
		<legend>Título y Copete</legend>
			<input name="titulo" type="text" required="required" id="titulo" value="" placeholder="Título: éste es el principal título del articulo (*)" />            
			<textarea name="copete" required id="copete" class="textarea" placeholder="Copete: puedes incluir el primer párrafo de tu artículo (*)"></textarea>
	</fieldset>
<hr />
	<fieldset>
		<legend>Contenido</legend>
			<textarea name="cuerpo" id="cuerpo"></textarea>
	</fieldset>   
<hr />
    <input type="submit" value="ENVIAR PUBLICACION" id="enviar" />
	<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
</form>