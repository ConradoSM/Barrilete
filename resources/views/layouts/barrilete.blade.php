<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8" />
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/site.webmanifest">
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
        <!-- Hojas de estilo en cascada -->
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/contenido.css') }}">
        <link rel="stylesheet" href="{{ asset('css/titularesIndex.css') }}">
        <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
        <link rel="stylesheet" href="{{ asset('css/jquery-confirm.min.css') }}">
        <!-- Scripts js -->
        <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('js/jquery-confirm.min.js') }}"></script>
        <script src="{{ asset('js/jquery.form.js') }}"></script>
        <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('js/jquery.lazy.min.js') }}"></script>
        <script src="{{ asset('js/home-scripts.js') }}"></script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129739451-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-129739451-1');
        </script>
    </head>
    <body>
        <header class="relative">
            <div class="navContainer">
                <img id="logo" class="big" onclick="location.href ='{{ route('default') }}'" src="{{ asset('svg/logo_barrilete.svg') }}" title="Home" />
                <div id="search">
                    <form action="{{ route('search') }}" method="get" id="formSearch">
                        <input id="inputText" type="search" value="" name="query" placeholder="Buscar en el sitio" class="big" />
                        <img src="{{ asset('svg/search.svg') }}" title="Buscar" onClick="document.getElementById('formSearch').submit();" class="big" />
                        <input type="hidden" value="articulos" name="sec" />
                    </form>
                </div>
                <img id="search-btn" class="big" src="{{ asset('svg/search.svg') }}" />
                <a id="menu-btn" class="big" title="Menú">
                    <div class="menu-btn-block top"></div>
                    <div class="menu-btn-block middle"></div>
                    <div class="menu-btn-block bottom"></div>
                </a>
            </div>
            <nav class="none">
                <ul>
                    @forelse ($sections as $section)
                    <li><a href="{{ route('section',['name'=>str_slug($section->name)]) }}" class="{{ Request::path() == 'sec/'.$section->name ? 'active' : '' }}" title="{{ $section->name }}">{{ $section->name }}</a></li>
                    @empty
                    @endforelse
                </ul>
            </nav>
            <div id="glass" class="hide"></div>
        </header>
        <section class="mainSection">
            @yield('content')
        </section>
        <footer>
            <div class="footerContainer">
                <div>
                    <h2>Secciones</h2>
                    <ul>
                        @forelse ($sections as $section)
                        <li><a href="{{ route('section',['name'=>str_slug($section->name)]) }}" title="{{ $section->name }}">{{ $section->name }}</a></li>
                        @empty
                        @endforelse
                    </ul>
                </div>
                <div>
                    <h2>Ingreso al sistema</h2>
                    <ul>@guest
                        <li><a href="{{ route('login') }}">LOGIN</a></li>
                        <li><a href="{{ route('register') }}">REGISTRO</a></li>
                        <li><a href="{{ route('password.request') }}">OLVIDÉ MI CONTRASEÑA</a></li>
                        @else
                        <li><a href="{{ route('dashboard') }}">PANEL DE CONTROL</a></li>
                        @endguest
                    </ul>
                    <h2>Contacto</h2>
                    <ul>
                        <li><a href="mailto:info@barrilete.com.ar">info@barrilete.com.ar</a></li>
                    </ul>
                </div>
                <div>
                    <h2>Institucional</h2>
                    <p class="footerCopyright">Conrado Maranguello, creador</p>
                    <p class="footerCopyright">© 2019 todos los derechos reservados<br />v2.5</p>
                </div>
                <div class="footerSocialContainer">
                    <a href="https://www.facebook.com/barrilete.info/" target="_blank"><img title="Barrilete en Facebook" src="{{ asset('svg/facebook.svg') }}" /></a>
                    <a href="https://www.twitter.com/Barrilete_Info/" target="_blank"><img title="Barrilete en Twitter" src="{{ asset('svg/twitter.svg') }}" /></a>
                </div>
            </div>
        </footer>
    </body>
</html>
