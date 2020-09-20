<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando encuesta...
    <div class="progress">
        <div class="bar"></div>
        <div class="percent">0%</div>
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
    <h1>Cargar encuesta</h1>
    <p><b>Autor</b>: {{ Auth::user()->name }}</p>
    <p><b>Fecha de publicación</b>: {{ ucwords(now()->formatLocalized('%A %d %B %Y')) }}</p>
    <hr />
    <form method="post" enctype="multipart/form-data" action="{{ route('createPoll') }}">
        <h3>Título y Copete</h3>
        <fieldset>
            <div id="errors"></div>
            <input type="text" name="title" value="" placeholder="Título: éste es el principal título de la encuesta (*) Mínimo 20 caracteres" required />
            <textarea name="article_desc" placeholder="Copete: puedes incluir el primer párrafo de tu encuesta (*) Mínimo 50 caracteres" required></textarea>
        </fieldset>
        <h3>Validez</h3>
        <fieldset>
            <label for="valid_from">Desde:</label>
            <input type="datetime-local" name="valid_from" id="valid_from" required />
            <label for="valid_to">Hasta:</label>
            <input type="datetime-local" name="valid_to" id="valid_to" required />
        </fieldset>
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
        <input type="hidden" name="author" value="{{ Auth::user()->name }}" />
        <input type="hidden" name="section_id" value="{{ $section->id }}" />
        <input type="submit" value="Siguiente »" id="enviar" class="button primary" />
        <input type="reset" class="button default" value="Restablecer" />
    </form>
</div>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
