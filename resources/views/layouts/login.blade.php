<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <meta name="msapplication-TileColor" content="#34495e">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title')</title>
    <link rel="image_src" href="@yield('image')">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="author" content="Conrado Maranguello">
    <meta name="description" content="@yield('description')">
    <meta name="copyright" content="Barrilete.com.ar">
    <!-- Hojas de estilo en cascada -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alerts-messages.css') }}">
    <!-- Scripts js -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
</head>
<body>
<div class="dashboard-container">
    <img src="{{asset('img/login-background-01.jpg')}}" class="img-background-login" />
    <div class="logo-container">
        <a href="{{route('default')}}"><img src="{{asset('svg/logo_barrilete.svg')}}" /></a>
    </div>
    <div class="dashboard-login">
        @yield('content')
    </div>
    <div>
        <p>
            @if (!Route::is('password.request'))<a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a> - @endif
            @if (!Route::is('login'))<a href="{{ route('login') }}">{{ __('Login') }}</a> -@endif
            @if (!Route::is('register'))<a href="{{ route('register') }}">{{ __('Register') }}</a> -@endif
            © 2020 - Todos los derechos reservados
        </p>
    </div>
</div>
</body>
</html>
