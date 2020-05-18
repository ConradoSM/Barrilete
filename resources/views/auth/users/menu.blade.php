<ul>
    @auth
    <li onclick="location.href='{{ route('users.dashboard') }}'">Mi cuenta</li>
    <li onclick="location.href='{{ route('logout') }}'">Salir</li>
    @else
    <li onclick="location.href='{{ route('login') }}'">Ingresar</li>
    <li onclick="location.href='{{ route('register') }}'">Registrarse</li>
    <li onclick="location.href='{{ route('password.request') }}'">Olvidé mi contraseña</li>
    @endauth
</ul>
