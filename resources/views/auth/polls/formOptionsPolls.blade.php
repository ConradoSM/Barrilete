<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando encuesta...
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
    <h1>Cargar encuesta - Agregar opciones</h1>
    <form method="post" enctype="multipart/form-data" id="createArticle" action="{{ route('createOptions') }}">
        <fieldset>
            <legend>Información</legend>
            <div id="errors"></div>
            <p><b>Autor</b>: {{ $poll->author }}</p>
            <p><b>Fecha de publicación</b>: {{ $poll->created_at->diffForHumans() }}</p>
            <p><b>Título:</b> {{ $poll->title }}</p>
            <p><b>Copete:</b> {{ $poll->article_desc }}</p>
        </fieldset>
        <input type="button" id="add-field" value="+ Agregar opción" class="button primary" />
        <div id="fields"></div>
        <input type="submit" id="submit" value="Guardar" class="button disabled" disabled  />
        <input type="hidden" name="poll_id" value="{{ $poll->id }}" />
        @csrf
    </form>
    <br />
</div>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-add-poll-options.js') }}"></script>
