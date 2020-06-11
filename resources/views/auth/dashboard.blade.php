<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="robots" content="noindex,nofollow,nosnippet,noarchive" />
        <meta name="googlebot" content="noindex,nofollow,nosnippet,noarchive" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{asset('css/contenido.css')}}" />
        <link rel="stylesheet" href="{{asset('css/titularesIndex.css')}}" />
        <link rel="stylesheet" href="{{asset('css/forms.css')}}" />
        <link rel="stylesheet" href="{{asset('css/dashboard.css')}}" />
        <link rel="stylesheet" href="{{asset('css/alerts-messages.css')}}">
    </head>
    <body>
        <div id="app"></div>
        <header>
            <img class="logo" src="{{ asset('svg/logo_barrilete_OLD.svg') }}" onclick="window.location = '{{ route('default') }}'" title="Home" alt="Home" />
            <div id="main-search">
                <form action="{{ route('searchAuth') }}" method="get" id="search">
                    <input type="search" value="" name="query" id="query" placeholder="Buscar contenido" />
                    <button type="submit" title="Buscar" id="search-button"><img src="{{asset('svg/search-black.svg')}}" /></button>
                    <input type="hidden" value="articulos" name="sec" id="sec" />
                    <input type="hidden" value="{{ Auth::user()->id }}" name="author" id="author" />
                </form>
            </div>
            <div id="user-menu">
                <p>{{ Auth::user()->name }}<img src="{{ asset('svg/arrow-down.svg') }}" /></p>
                <div id="user-options">
                    <a href="{{ route('options') }}" id="options"><img src="{{ asset('svg/options.svg') }}" />Opciones</a>
                    <a href="#" onclick="window.location = '{{ route('logout') }}'" data-ajax="false"><img src="{{ asset('svg/log-out.svg') }}" />{{ __('Logout') }}</a>
                </div>
            </div>
            <div id="user-notifications" style="display:none;">
               <img src="{{ asset('svg/chat.svg') }}" />
               <img src="{{ asset('svg/notification.svg') }}" />
               <img src="{{ asset('svg/info.svg') }}" />
            </div>
        </header>
        <section class="dashboard">
            <aside class="tools-bar">
                <h2>Mis publicaciones</h2>
                <a href="{{ route('viewArticles',['id' => Auth::user() -> id]) }}" class="selected"><img src="{{ asset('svg/file.svg') }}" />Artículos</a>
                <a href="{{ route('viewGalleries',['id' => Auth::user() -> id]) }}"><img src="{{ asset('svg/image.svg') }}" />Galerías</a>
                <a href="{{ route('viewPolls',['id' => Auth::user() -> id]) }}"><img src="{{ asset('svg/analytics.svg') }}" />Encuestas</a>
                <h2>Cargar publicaciones</h2>
                <a href="{{ route('formCreateArticle') }}"><img src="{{ asset('svg/add-file.svg') }}" />Artículos</a>
                <a href="{{ route('formGallery') }}"><img src="{{ asset('svg/image.svg') }}" />Galerías</a>
                <a href="{{ route('formPoll') }}"><img src="{{ asset('svg/note.svg') }}" />Encuestas</a>
                @if (Auth::user()->authorizeRoles([\barrilete\User::ADMIN_USER_ROLE]))
                <h2>Contenido sin publicar</h2>
                <a href="{{ route('unpublishedArticles') }}"><img src="{{ asset('svg/add-file.svg') }}" />Artículos</a>
                <a href="{{ route('unpublishedGalleries') }}"><img src="{{ asset('svg/image.svg') }}" />Galerías</a>
                <a href="{{ route('unpublishedPolls') }}"><img src="{{ asset('svg/note.svg') }}" />Encuestas</a>
                @endif
            </aside>
            <div id="loader"><center><img src="{{ asset('img/loader.gif') }}" /></center></div>
            <div id="user-content"></div>
        </section>
        <footer>
            <div class="footerContainer">
                <div>
                    <h2>Contacto</h2>
                    <ul>
                        <li><a href="mailto:info@barrilete.com.ar" data-ajax="false">info@barrilete.com.ar</a></li>
                    </ul>
                </div>
                <div>
                    <h2>Institucional</h2>
                    <p class="footerCopyright">Conrado Maranguello, creador</p>
                    <p class="footerCopyright">© 2019 todos los derechos reservados<br />v2.5</p>
                </div>
                <div class="footerSocialContainer">
                    <img src="{{ asset('svg/logo_barrilete.svg') }}" />
                </div>
            </div>
        </footer>
        <!-- scripts -->
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/dashboard.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                /**
                 * LOAD MAIN ARTICLES
                 */
                const link = '{{ URL::route('viewArticles',['id' => Auth::user()->id], false) }}';
                $.get(link, function(data) {
                    $('#user-content').html(data.view)
                        .prepend('<div id="loader"><center><img src="{{ asset("img/loader.gif") }}" /></center></div>')
                        .hide().fadeIn('normal');
                });

                /**
                 * SEARCH FORM
                 */
                $('button#search-button').on('click', function(e) {
                    const button = $(this);
                    const href = $('form#search').attr('action');
                    getAjaxRequest(e, button, href, null);
                });

                /**
                 * DROPDOWN USER OPTIONS
                 */
                $('div#user-menu').on('click', function(e) {
                    e.preventDefault();
                    $('div#user-options').slideToggle('fast');
                    $(this).addClass('focus');
                });
                $(document).on('click', function(e){
                    const trigger = $('div#user-menu');
                    if(trigger !== e.target && !trigger.has(e.target).length) {
                        $('div#user-options').slideUp('fast');
                        $(trigger).removeClass('focus');
                    }
                });
            });
        </script>
    </body>
</html>
