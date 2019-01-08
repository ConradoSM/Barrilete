<!doctype html>
<html lang="es">
    <head>
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="author" content="Conrado Maranguello" />
        <meta name="description" content="@yield('description')" />
        <meta name="keywords" content="@yield('keywords')" />
        <link rel="stylesheet" href="{{asset('css/main.css')}}" />
        <link rel="stylesheet" href="{{asset('css/contenido.css')}}" />
        <link rel="stylesheet" href="{{asset('css/titularesIndex.css')}}" />
        <link rel="stylesheet" href="{{asset('css/forms.css')}}" />
        <link rel="stylesheet" href="{{asset('css/dashboard.css')}}" />
        <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/scripts.js')}}"></script>
    </head>
    <body>
        <section class="dashboard">
            @yield('content')
        </section>
        <footer>
            <div class="footerContainer">
                <div>
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
                    <img src="{{asset('svg/logo_barrilete.svg')}}" />
                    <img src="{{asset('svg/facebook.svg')}}" class="footerSocialFacebook" />
                    <img src="{{asset('svg/twitter.svg')}}" class="footerSocialTwitter" />
                </div>
            </div>                
        </footer>
        @yield('scripts')
    </body>
</html>