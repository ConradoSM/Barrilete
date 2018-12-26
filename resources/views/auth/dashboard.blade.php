@extends('layouts.barrilete')
@section('title', 'Dashboard')
@section('content')
<div class="dashboard">
    <div class="tools">
        <div class="dashboard-title"><h1>Administración</h1></div>
        <div class="user-options">
            <p>{{ Auth::user()->name }}<img src="{{ asset('svg/arrow-down.svg') }}" /></p>
            <div id="user-nav-options">
                <a href="#"><img src="{{ asset('svg/options.svg') }}" />Opciones</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img src="{{ asset('svg/log-out.svg') }}" />{{ __('Logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </div>
        </div>                   
    </div>
    <div class="tools-bar">
        <h2>Mis publicaciones</h2>
        <a href="{{ route('viewArticles',['id' => Auth::user() -> id]) }}" class="selected"><img src="{{ asset('svg/file.svg') }}" />Artículos</a>
        <a href="{{ route('viewGalleries',['id' => Auth::user() -> id]) }}"><img src="{{ asset('svg/image.svg') }}" />Galerías</a>
        <a href="{{ route('viewPolls',['id' => Auth::user() -> id]) }}"><img src="{{ asset('svg/analytics.svg') }}" />Encuestas</a>
        <h2>Cargar publicaciones</h2>
        <a href="{{ route('formArticle') }}"><img src="{{ asset('svg/add-file.svg') }}" />Artículos</a>
        <a href="#"><img src="{{ asset('svg/image.svg') }}" />Galerías</a>
        <a href="#"><img src="{{ asset('svg/note.svg') }}" />Encuestas</a>
    </div>
    <div id="loader"><center><img src="{{ asset('img/loader.gif') }}" /></center></div>
    <div id="user-content"></div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $('#user-content').load('{{ route('viewArticles',['id' => Auth::user() -> id]) }}').prepend('<div id="loader"><center><img src="{{ asset('img / loader.gif') }}" /></center></div>').hide().fadeIn('normal');

        $('div.tools-bar a').each(function () {

            var href = $(this).attr('href');
            $(this).attr({href: '#'});

            $(this).click(function () {

                $('div.tools-bar a').removeClass('selected');
                $(this).addClass('selected');
                $('#loader').fadeIn('fast', 'linear');

                $('#user-content').hide(0, function () {

                    $('#user-content').load(href, function () {
                        $('#loader').fadeOut('fast', 'linear', function () {
                            $('#user-content').fadeIn('slow', 'linear');
                        });
                    });
                });
            });
        });
    });
</script>
@endsection