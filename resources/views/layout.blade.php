<!doctype html>
<html lang="es">
    <head>
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link rel="stylesheet" href="{{asset('css/main.css')}}" />
        <link rel="stylesheet" href="{{asset('css/contenido.css')}}" />
        <link rel="stylesheet" href="{{asset('css/titularesIndex.css')}}" />
        <link rel="stylesheet" href="{{asset('css/forms.css')}}" />
        <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/scripts.js')}}"></script>
    </head>
    <body>
        <header class="relative">
            <div class="navContainer">
                <div id="search">
                    <form action="{{ route('search') }}" method="get" id="formSearch">
                        <input id="inputText" type="search" value="" name="query" placeholder="Buscar..." />
                        <img src="{{asset('svg/search.svg')}}" title="Buscar" onClick="document.getElementById('formSearch').submit();" />
                        <input type="hidden" value="articulos" name="sec" />
                    </form>
                </div>
                <img id="logo" class="logo" onclick="location.href ='{{ route('home') }}'" src="{{ asset('svg/logo_barrilete.svg') }}" title="Home" />
                <nav class="none">
                    <ul>
                        <li><a href="{{ route('section', ['seccion' => 'sociedad']) }}" title="Sociedad">SOCIEDAD</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'politica']) }}" title="Política">POLITICA</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'economia']) }}" title="Economía">ECONOMIA</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'internacionales']) }}" title="Mundo">MUNDO</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'deportes']) }}" title="Deportes">DEPORTES</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'cultura']) }}"title="Cultura">CULTURA</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'tecno']) }}" title="Tecnología">TECNOLOGIA</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'editoriales']) }}" title="Editoriales">EDITORIALES</a></li>
                        <li><a href="{{ route('galleries') }}">GALERIAS</a>
                    </ul>
                </nav>
            <img id="search-btn" class="search-btn" src="{{asset('svg/search.svg')}}" />
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
                        <li><a href="{{ route('section', ['seccion' => 'sociedad']) }}" title="Sociedad">SOCIEDAD</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'politica']) }}" title="Política">POLITICA</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'economia']) }}" title="Economía">ECONOMIA</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'internacionales']) }}" title="Mundo">MUNDO</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'deportes']) }}" title="Deportes">DEPORTES</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'cultura']) }}"title="Cultura">CULTURA</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'tecno']) }}" title="Tecnología">TECNOLOGIA</a></li>
                        <li><a href="{{ route('section', ['seccion' => 'editoriales']) }}" title="Editoriales">EDITORIALES</a></li>
                        <li><a href="{{ route('galleries') }}">GALERIAS</a>
                    </ul>   
                </div>
                <div>
                    <h2>Ingreso al sistema</h2>
                    <ul>
                        <li><a href="#">LOGIN</a></li>
                        <li><a href="#">PANEL DE CONTROL</a></li>
                        <li><a href="#">REGISTRO</a></li>
                        <li><a href="#">OLVIDÉ MI CONTRASEÑA</a></li>
                    </ul>
                    <h2>Contacto</h2>
                    <ul>
                        <li><a href="mailto:info@barrilete.com.ar">info@barrilete.com.ar</a></li>
                    </ul>
                </div>
                <div>
                    <h2>Herramientas</h2>
                    <ul>
                        <li><a href="#">BUSCADOR</a></li>
                    </ul>
                    <h2>Institucional</h2>
                    <p class="footerCopyright">Conrado Maranguello, responsable editorial</p>
                    <p class="footerCopyright">©2016 - 2019 todos los derechos reservados<br />Versión: 2.0 02122018 build 18.30 BETA</p>
                </div>
                <div class="footerSocialContainer">
                    <img src="{{asset('svg/logo_barrilete.svg')}}" />
                    <img src="{{asset('svg/facebook.svg')}}" class="footerSocialFacebook" />
                    <img src="{{asset('svg/twitter.svg')}}" class="footerSocialTwitter" />
                </div>
            </div>                
        </footer>
    </body>
</html>