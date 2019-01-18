<!doctype html>
<html lang="es">
    <head>
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="author" content="Conrado Maranguello" />
        <meta name="description" content="@yield('description')" />
        <meta name="keywords" content="@yield('keywords')" />
        <meta name="copyright" content="Barrilete.com.ar">
        <!-- Open Graph data -->
        <meta property="og:title" content="@yield('article_title')" />
        <meta property="og:type" content="@yield('article_type')" />
        <meta property="og:description" content="@yield('article_desc')" />
        <meta property="og:url" content="@yield('article_url')" />
        <meta property="og:image" content="@yield('article_photo')" />
        <meta property="og:site_name" content="@yield('site_name')" />
        <meta property="article:published_time" content="@yield('created_at')" />
        <meta property="article:modified_time" content="@yield('updated_at')" />
        <meta property="article:section" content="@yield('article_section')" />
        <!-- Hojas de estilo en cascada -->
        <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/contenido.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/titularesIndex.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/forms.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
        <!-- Scripts js -->
        <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
    </head>
    <body>
        <header class="relative">
            <div class="navContainer">
                <div id="search">
                    <form action="{{ route('search') }}" method="get" id="formSearch">
                        <input id="inputText" type="search" value="" name="query" placeholder="Buscar en el sitio" />
                        <img src="{{ asset('svg/search.svg') }}" title="Buscar" onClick="document.getElementById('formSearch').submit();" />
                        <input type="hidden" value="articulos" name="sec" />
                    </form>
                </div>
                <img id="logo" class="logo" onclick="location.href ='{{ route('default') }}'" src="{{ asset('svg/logo_barrilete.svg') }}" title="Home" />
                <nav class="none">
                    <ul>
                        @forelse ($sections as $section)
                        <li><a href="{{ route('section',['seccion'=>$section->name]) }}" title="{{ $section->name }}">{{ $section->name }}</a></li>
                        @empty
                        @endforelse 
                    </ul>
                </nav>
                <img id="search-btn" class="search-btn" src="{{ asset('svg/search.svg') }}" />
                <a id="menu-btn" class="menu-btn" title="Menú">
                    <div class="menu-btn-block top"></div>
                    <div class="menu-btn-block middle"></div>
                    <div class="menu-btn-block bottom"></div>
                </a>                
            </div>
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
                        <li><a href="{{ route('section',['seccion'=>$section->name]) }}" title="{{ $section->name }}">{{ $section->name }}</a></li>
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
                    <p class="footerCopyright">Conrado Maranguello, responsable editorial</p>
                    <p class="footerCopyright">©2016 - 2019 todos los derechos reservados<br />Versión: 2.0 02122018 build 18.30 BETA</p>
                </div>
                <div class="footerSocialContainer">
                    <a href="https://www.facebook.com/barrilete.info/" target="_blank"><img title="Barrilete en Facebook" src="{{ asset('svg/facebook.svg') }}" /></a>
                    <a href="https://www.twitter.com/Barrilete_Info/" target="_blank"><img title="Barrilete en Twitter" src="{{ asset('svg/twitter.svg') }}" /></a>
                </div>
            </div>                
        </footer>
    </body>
</html>