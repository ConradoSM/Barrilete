<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando encuesta...
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
    <h1>Cargar encuesta</h1>
    <form method="post" enctype="multipart/form-data" action="{{ route('createPoll') }}">
        <fieldset>
            <legend>Información</legend>
            <p><b>Autor</b>: {{ Auth::user()->name }}</p>
            <p><b>Fecha de publicación</b>: {{ ucwords(now()->formatLocalized('%A %d %B %Y')) }}</p>
        </fieldset>
        <fieldset>
            <legend>Título y Copete</legend>
            <div id="errors"></div>
            <input type="text" name="title" value="" placeholder="Título: éste es el principal título de la encuesta (*) Mínimo 20 caracteres" required />
            <textarea name="article_desc" placeholder="Copete: puedes incluir el primer párrafo de tu encuesta (*) Mínimo 50 caracteres" required></textarea>
            <input type="submit" value="Siguiente »" id="enviar" class="button primary" />
            <input type="reset" class="button default" value="Restablecer" />
        </fieldset>
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
        <input type="hidden" name="author" value="{{ Auth::user()->name }}" />
        <input type="hidden" name="section_id" value="{{ $section->id }}" />
    </form>
</div>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
