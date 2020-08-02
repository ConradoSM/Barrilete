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
        <!-- Open Graph data -->
        <meta property="og:title" content="@yield('article_title')">
        <meta property="og:type" content="@yield('article_type')">
        <meta property="og:description" content="@yield('article_desc')">
        <meta property="og:url" content="@yield('article_url')">
        <meta property="og:image" content="@yield('article_photo')">
        <meta property="og:site_name" content="@yield('site_name')">
        <meta property="article:published_time" content="@yield('created_at')">
        <meta property="article:modified_time" content="@yield('updated_at')">
        <meta property="article:section" content="@yield('article_section')">
        <meta name="user_id" content="{{ Auth::check() ? Auth::user()->id : ''}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="app" id="app">
        <!-- Scripts js -->
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <!-- Hojas de estilo en cascada -->
        <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/contenido.css') }}">
        <link rel="stylesheet" href="{{ asset('css/titularesIndex.css') }}">
        <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
        <link rel="stylesheet" href="{{ asset('css/jquery-confirm.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/alerts-messages.css') }}">
        <link rel="stylesheet" href="{{ asset('css/users-dashboard.css') }}">
        <!-- Global site tag (gtag.js) - Google Analytics
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129739451-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-129739451-1');
        </script>-->
    </head>
    <body>
        <header>
            <!-- HEADER CONTAINER -->
            <div id="header-container">
                <!-- DIV LOGO -->
                <img class="logo" onclick="location.href ='{{ route('default') }}'" src="{{ asset('svg/logo_barrilete.svg') }}" title="Home" alt="Home" />
                <!-- DIV SEARCH -->
                <div id="search">
                    <form action="{{ route('search') }}" method="get" id="formSearch">
                        <input id="search-input" type="search" value="" name="query" placeholder="Buscar en el sitio" />
                        <img src="{{ asset('svg/search.svg') }}" onClick="document.getElementById('formSearch').submit();" alt="Buscar" title="Buscar" class="search-button"/>
                        <input type="hidden" value="articulos" name="sec" />
                    </form>
                    <div id="results"></div>
                </div>
                <!-- BAR USER -->
                <div id="user-bar">
                    @auth
                        <div class="notifications comments" id="comments">
                            <span>{{Auth::user()->getUnreadCommentNotificationsCount()}}</span>
                        </div>
                        <div class="notifications messages" id="messages">
                            <span>{{Auth::user()->getUnreadMessageNotificationsCount()}}</span>
                        </div>
                    @endauth
                    <img src="{{asset('svg/user-blue.svg')}}" data-bind="{{route('user-menu')}}" title="Menú" alt="Menú" />
                    <img src="{{asset('svg/alarm.svg')}}" data-bind="{{route('notifyReactions')}}" title="Notificaciones" alt="Notificaciones" />
                    <img src="{{asset('svg/chat.svg')}}" data-bind="{{route('notifyMessages')}}" title="Mensajes" alt="Mensajes" />
                    <img src="{{asset('svg/research.svg')}}" class="search-mobile" />
                    <div id="user-menu"></div>
                </div>
                <!-- MENU BUTTON -->
                <a id="menu-btn" title="Menú" class="display">
                    <div class="menu-btn-block top"></div>
                    <div class="menu-btn-block middle"></div>
                    <div class="menu-btn-block bottom"></div>
                </a>
            </div>
            <!-- DIV SECTION CONTAINER -->
            <div id="nav-container">
                <hr />
                <nav>
                    <ul>
                        @forelse ($sections as $section)
                        <li><a href="{{ route('section',['name'=>str_slug($section->name)]) }}" class="{{ Request::path() == 'sec/'.$section->name ? 'active' : '' }}" title="{{ $section->name }}">{{ $section->name }}</a></li>
                        @empty
                        @endforelse
                    </ul>
                </nav>
            </div>
        </header>
        <section id="main-container">
            @yield('content')
        </section>
        <footer>
            <div id="footer-container">
                <section>
                    <h2>Categorías</h2>
                    @forelse ($sections as $section)
                        <p><a href="{{ route('section',['name'=>str_slug($section->name)]) }}" title="{{ $section->name }}">{{ ucfirst($section->name) }}</a></p>
                    @empty
                    @endforelse
                </section>
                <section>
                    <h2>Ingreso al sistema</h2>
                    @guest
                    <p><a href="{{ route('login') }}">Login</a></p>
                    <p><a href="{{ route('register') }}">Registro</a></p>
                    <p><a href="{{ route('password.request') }}">Olvidé Mi Contraseña</a></p>
                    @else
                    <p><a href="{{ route('users.dashboard') }}">Mi Cuenta</a></p>
                    <p><a href="{{ route('logout') }}">Salir</a></p>
                    @endguest
                </section>
                <section>
                    <h2>Acerca de</h2>
                    <p>© 2020 todos los derechos reservados - v2.6</p>
                    <p><a href="mailto:info@barrilete.com.ar">info@barrilete.com.ar</a></p>
                </section>
                <section>
                    <h2>Redes Sociales</h2>
                    <p>
                        <a href="https://www.facebook.com/barrilete.info/" target="_blank"><img alt="Facebook" class="social" title="Barrilete en Facebook" src="{{ asset('svg/facebook.svg') }}" /></a>
                        <a href="https://www.twitter.com/Barrilete_Info/" target="_blank"><img alt="Facebook" class="social" title="Barrilete en Twitter" src="{{ asset('svg/twitter.svg') }}" /></a>
                        <img alt="Instagram" class="social" title="Barrilete en Instagram" src="{{ asset('svg/instagram.svg') }}" />
                        <img alt="Instagram" class="social" title="Barrilete en Instagram" src="{{ asset('svg/youtube.svg') }}" />
                    </p>
                </section>
                <img class="logo-footer" alt="Barrilete" src="{{ asset('svg/logo_barrilete.svg') }}" />
            </div>
        </footer>
        <script type="text/javascript" src="{{ asset('js/home-scripts.js') }}"></script>
    </body>
</html>
