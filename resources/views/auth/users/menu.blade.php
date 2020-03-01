@auth
    <div class="notify" onclick="location.href='{{ route('dashboard') }}'">
        <p class="data-notification">Mi cuenta</p>
    </div>
    <div class="notify" onclick="location.href='{{ route('logout') }}'">
        <p class="data-notification">Salir</p>
    </div>
@else
    <div class="notify" onclick="location.href='{{ route('login') }}'">
        <p class="data-notification">Ingresar</p>
    </div>
    <div class="notify" onclick="location.href='{{ route('register') }}'">
        <p class="data-notification">Registrarse</p>
    </div>
    <div class="notify" onclick="location.href='{{ route('password.request') }}'">
        <p class="data-notification">Olvidé mi contraseña</p>
    </div>
@endauth
