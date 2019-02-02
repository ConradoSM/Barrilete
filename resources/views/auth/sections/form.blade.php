@if (Auth::user()->is_admin)
<div id="Article_Form">
    <h1>{{ isset($section) ? 'Editar sección' : 'Crear sección' }}</h1>
    <a href="{{ route('sectionsIndex') }}" class="primary" title="Volver al listado de secciones" id="crear">Lista de secciones</a>
    <fieldset>
        <div id="errors"></div>
        <form action="{{ isset($section) ? route('updateSection', ['id' => $section->id]) : route('createSection') }}" method="post" id="createArticle">
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
            <input type="submit" class="primary" value="{{ isset($section) ? 'Actualizar' : 'Cargar' }}" />
            <input type="reset" class="default" value="Restablecer" />
            @csrf
        </form>
    </fieldset>
</div>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dashboard-form-submit.js') }}"></script>
<script type="text/javascript" src="{{asset('js/dashboard-admin-users.js')}}"></script>
@else
<p class="invalid-feedback">Error: no eres administrador del sistema</p>
@endif