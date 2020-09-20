<p class="user-title"><img src="{{asset('svg/padlock.svg')}}" alt="Cambiar Contraseña">Cambiar Contraseña</p>
<fieldset>
    <div id="status"></div>
    <form action="{{route('updatePassword')}}" method="post" id="update-password">
        <label for="current_password">Contraseña actual:</label>
        <input type="password" name="current_password" id="current_password" required />
        <label for="password">Contraseña nueva:</label>
        <input type="password" name="password" id="password" required />
        <label for="password_confirm">Confirmar contraseña:</label>
        <input type="password" name="password_confirm" id="password_confirm" required />
        <input type="submit" class="button primary" value="Actualizar contraseña" />
        @csrf
    </form>
</fieldset>
<script type="text/javascript" src="{{ asset('js/dashboard-users.js') }}"></script>
