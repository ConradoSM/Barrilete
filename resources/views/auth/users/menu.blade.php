<ul>
    @guest
        <li><a href="{{ route('login') }}">Ingresar</a></li>
        <li><a href="{{ route('register') }}">Registrarse</a></li>
        <li><a href="{{ route('password.request') }}">Olvidé mi contraseña</a></li>
    @else
        <li><a href="{{ route('dashboard') }}">Mi cuenta</a></li>
        <li><a href="{{ route('logout') }}">Salir</a> </li>
    @endguest
</ul>
