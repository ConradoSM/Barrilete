<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="robots" content="noindex,nofollow,nosnippet,noarchive" />
        <meta name="googlebot" content="noindex,nofollow,nosnippet,noarchive" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{asset('css/dashboard/main.css')}}" />
        <link rel="stylesheet" href="{{asset('css/dashboard/form.css')}}">
        <link rel="stylesheet" href="{{asset('css/jquery-confirm.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/alerts-messages.css')}}">
    </head>
    <body>
        <div id="app"></div>
        <section class="dashboard">
            <aside class="tools-bar">
                <img class="logo" src="{{ asset('svg/logo_barrilete.svg') }}" title="Dashboard" alt="Home" />
                <div id="main-search">
                    <form action="{{ route('searchAuth') }}" method="get" id="search">
                        <input type="search" value="" name="query" id="query" placeholder="Buscar contenido" />
                        <button type="submit" title="Buscar" id="search-button"><img src="{{asset('svg/search.svg')}}" /></button>
                        <input type="hidden" value="articulos" name="sec" id="sec" />
                        <input type="hidden" value="{{ Auth::user()->id }}" name="author" id="author" />
                    </form>
                </div>
                <ul class="menu">
                    <li title="Mis Publicaciones">
                        <p><img src="{{ asset('svg/docs.svg') }}" alt="Mis Publicaciones">Mis Publicaciones<img src="{{asset('svg/down-arrow.svg')}}" class="arrow" alt="sub-menu"></p>
                        <ul class="sub-menu">
                            <li title="Artículos" data-link="{{ route('viewArticles',['id' => Auth::user()->id]) }}">Artículos</li>
                            <li title="Galerías" data-link="{{ route('viewGalleries',['id' => Auth::user()->id]) }}">Galerías</li>
                            <li title="Encuestas" data-link="{{ route('viewPolls',['id' => Auth::user()->id]) }}">Encuestas</li>
                        </ul>
                    </li>
                    <li title="Cargar Contenido">
                        <p><img src="{{ asset('svg/write.svg') }}" alt="Cargar Contenido">Cargar Contenido<img src="{{asset('svg/down-arrow.svg')}}" class="arrow" alt="sub-menu"></p>
                        <ul class="sub-menu">
                            <li title="Artículos" data-link="{{ route('formCreateArticle') }}">Artículos</li>
                            <li title="Galerías" data-link="{{ route('formGallery') }}">Galerías</li>
                            <li title="Encuestas" data-link="{{ route('formPoll') }}">Encuestas</li>
                        </ul>
                    </li>
                    @if(Auth::user()->hasRole('admin'))
                    <li title="Revisar Publicaciones">
                        <p><img src="{{ asset('svg/view.svg') }}" alt="Revisar Publicaciones">Revisar Publicaciones<img src="{{asset('svg/down-arrow.svg')}}" class="arrow" alt="sub-menu"></p>
                        <ul class="sub-menu">
                            <li title="Artículos" data-link="{{ route('unpublishedArticles') }}">Artículos</li>
                            <li title="Galerías" data-link="{{ route('unpublishedGalleries') }}">Galerías</li>
                            <li title="Encuestas" data-link="{{ route('unpublishedPolls') }}">Encuestas</li>
                        </ul>
                    </li>
                    @endif
                    <li title="Sistema">
                        <p><img src="{{ asset('svg/gear.svg') }}" alt="Sistema">Sistema<img src="{{asset('svg/down-arrow.svg')}}" class="arrow" alt="sub-menu"></p>
                        <ul class="sub-menu">
                            <li title="Opciones" data-link="{{ route('options') }}">Opciones</li>
                            <li title="Ir al sitio" onclick="window.location = '{{ route('default') }}'">Ir al sitio</li>
                            <li title="Salir" onclick="window.location = '{{ route('logout') }}'">Salir</li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <div id="loader"><img src="{{ asset('img/loader.gif') }}" /></div>
            <div id="user-content"></div>
        </section>
        <footer>
            <div>
                <p>© 2020 todos los derechos reservados - v2.5.1</p>
                <p><a href="mailto:info@barrilete.com.ar" data-ajax="false">info@barrilete.com.ar</a></p>
            </div>
            <div>
                <img src="{{ asset('svg/logo_barrilete.svg') }}" />
            </div>
        </footer>
        <!-- scripts -->
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
        <script>
            /** Load main articles **/
            $(document).ready(function() {
                $('div#loader').hide();
                const link = '{{ URL::route('viewArticles',['id' => Auth::user()->id], false) }}';
                $.get(link).done(function(data) {
                    $('#user-content').html(data.view).fadeIn('fast');
                });
            });
        </script>
    </body>
</html>
