<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando encuesta...
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
@if (isset($success))
    <p class="alert feedback-success">{{ $success }}</p>
@endif
@if (isset($error))
    <p class="alert feedback-error">{{ $error }}</p>
@endif
@if (!$options)
    <p class="alert feedback-warning">Ésta encuesta no posee opciones</p>
@endif
<h1>Actualizar encuesta</h1>
    <p class="article-actions">
        <a href="{{ route('addOptions', ['id' => $poll->id]) }}" class="button primary">+ Agregar opciones</a>
        <a href="{{ route('previewPoll', ['id' => $poll->id]) }}" class="button primary">Terminar Edición</a>
    </p>
    <hr />
    <h3>Información</h3>
    <fieldset>
        <p><b>Autor</b>: {{ $poll->author }}</p>
        <p><b>Fecha de publicación</b>: {{ $poll->created_at->diffForHumans() }}</p>
    </fieldset>
    <h3>Título y Copete</h3>
    <form method="post" class="data" enctype="multipart/form-data" action="{{ route('updatePoll') }}">
        <fieldset>
            <div id="errors"></div>
            <input type="text" class="input" name="title" value="{{ $poll->title }}" placeholder="Título: éste es el principal título de la encuesta (*) Mínimo 20 caracteres" required />
            <textarea name="article_desc" class="input" placeholder="Copete: puedes incluir el primer párrafo de tu encuesta (*) Mínimo 50 caracteres" required>{{ $poll->article_desc }}</textarea>
            @csrf
            <input type="hidden" name="id" value="{{ $poll->id }}" />
            <input type="hidden" name="user_id" value="{{ $poll->user_id }}" />
            <input type="hidden" name="author" value="{{ $poll->author }}" />
            <input type="hidden" name="section_id" value="{{ $poll->section_id }}" />
        </fieldset>
        <input type="submit" value="Actualizar" class="button success" />
    </form>
    <h3>Editar Opciones</h3>
    @forelse ($options as $option)
        <form method="post" class="data" enctype="multipart/form-data" action="{{ route('updatePollOption') }}">
            <fieldset>
                <input type="text" class="input" name="option" value="{{ $option->option }}" placeholder="Opción de la encuesta" required />
                @csrf
                <input type="hidden" name="poll_id" value="{{ $poll->id }}" />
                <input type="hidden" name="id" value="{{ $option->id }}" />
            </fieldset>
            <input type="submit" value="Actualizar" class="button success" />
            <a href="{{ route('deleteOption',['id' => $option->id]) }}" class="button danger" data-confirm="¿Estás seguro que deseas eliminar ésta opción?">Eliminar</a>
        </form>
        <br />
    @empty
        <p class="alert feedback-warning">Ésta encuesta no posee opciones</p>
    @endforelse
</div>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-edit-form.js') }}"></script>
