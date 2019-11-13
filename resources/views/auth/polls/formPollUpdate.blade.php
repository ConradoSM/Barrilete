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
    <p class="alert-success"><img src="/svg/ajax-success.svg" alt="Exito"/>{{ $success }}</p>
@endif
@if (isset($error))
    <p class="invalid-feedback"><img src="/svg/ajax-error.svg" alt="Error"/>{{ $error }}</p>
@endif
@if (!$options)
    <p class="alert-warning"><img src="/svg/ajax-warning.svg" alt="Advertencia"/>Ésta encuesta no posee opciones</p>
@endif
<h1>Actualizar encuesta</h1>
    <fieldset>
        <legend>Información</legend>
        <p><b>Autor</b>: {{ $poll->author }}</p>
        <p><b>Fecha de publicación</b>: {{ $poll->created_at->diffForHumans() }}</p>
    </fieldset>
    <fieldset>
        <legend>Título y Copete</legend>
            <div id="errors"></div>
            <form method="post" class="data" enctype="multipart/form-data" action="{{ route('updatePoll') }}">
                <p title="Editar">{{ $poll->title }}</p>
                <input type="text" class="input" name="title" value="{{ $poll->title }}" placeholder="Título: éste es el principal título de la encuesta (*) Mínimo 20 caracteres" required />
                <p title="Editar">{{ $poll->article_desc }}</p>
                <textarea name="article_desc" class="input" placeholder="Copete: puedes incluir el primer párrafo de tu encuesta (*) Mínimo 50 caracteres" required>{{ $poll->article_desc }}</textarea>
                <input type="submit" value="Actualizar" class="success" />
                @csrf
                <input type="hidden" name="id" value="{{ $poll->id }}" />
                <input type="hidden" name="user_id" value="{{ $poll->user_id }}" />
                <input type="hidden" name="author" value="{{ $poll->author }}" />
                <input type="hidden" name="section_id" value="{{ $poll->section_id }}" />
            </form>
    </fieldset>
    <fieldset>
        <legend>Opciones</legend>
    @forelse ($options as $option)
        <form method="post" class="data" enctype="multipart/form-data" action="{{ route('updatePollOption') }}">
            <p title="Editar">{{ $option->option }}</p>
            <input type="text" class="input" name="option" value="{{ $option->option }}" placeholder="Opción de la encuesta" required />
            <input type="submit" value="Actualizar" class="success" />
            <a href="{{ route('deleteOption',['id' => $option->id]) }}" class="danger" data-confirm="¿Estás seguro que deseas eliminar ésta opción?">Eliminar</a>
            @csrf
            <input type="hidden" name="poll_id" value="{{ $poll->id }}" />
            <input type="hidden" name="id" value="{{ $option->id }}" />
        </form>
    @empty
        <p class="alert-warning"><img src="/svg/ajax-warning.svg" alt="Advertencia"/>Ésta encuesta no posee opciones</p>
    @endforelse
        <hr />
        <a href="{{ route('addOptions', ['id' => $poll->id]) }}" class="primary">+ Agregar opciones</a>
    </fieldset>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-edit-form.js') }}"></script>
