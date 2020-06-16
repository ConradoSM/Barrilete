<div id="progressBar-Container">
    <img src="{{ asset('img/loading.gif') }}" /> Cargando sección...
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    <p>Por favor no actualizar ni cerrar ésta ventana mientras dure el proceso de carga.</p>
</div>
<div id="status">
    <h1>{{ isset($section) ? 'Editar sección' : 'Crear sección' }}</h1>
    <div id="action">
        <a href="{{ route('sectionsIndex') }}" class="button primary" title="Volver al listado de secciones">Lista de secciones</a>
    </div>
    @if (session('success'))
        <p class="alert feedback-success">{{ session('success') }}</p>
    @elseif (session('error'))
        <p class="alert feedback-error">{{ session('error') }}</p>
    @endif
    <form action="{{ isset($section) ? route('updateSection', ['id' => $section->id]) : route('createSection') }}" method="post">
        <fieldset>
            <div id="errors"></div>
            <input type="text" name="name" value="{{ isset($section) ? $section->name : '' }}" placeholder="Nombre de la sección (*) Requerido" required />
            <select name="prio" size="1" required>
                <option value="{{ isset($section) ? $section->prio : '' }}" selected>{{ isset($section) ? $section->prio : 'Seleccionar prioridad' }}</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            @csrf
        </fieldset>
        <input type="submit" class="button primary" value="{{ isset($section) ? 'Actualizar' : 'Cargar' }}" />
        <input type="reset" class="button default" value="Restablecer" />
    </form>
</div>
<script type="text/javascript" src="{{ asset('js/dashboard-form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard.js')}}"></script>
