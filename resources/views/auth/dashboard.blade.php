@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('content')
    <div class="tools">
        <div class="dashboard-title"><img src="{{ asset('svg/logo_barrilete.svg') }}" onclick="location.href ='{{ route('default') }}'" title="Home" alt="Home" /></div>
        <div id="MainSearch">
            <form action="{{ route('searchAuth') }}" method="get" id="search">
                <input id="inputText" type="search" value="" name="query" placeholder="Buscar contenido" />
                <button type="submit" title="Buscar" id="search-button"><img src="{{asset('svg/search.svg')}}" /></button>
                <input type="hidden" value="articulos" name="sec" />
                <input type="hidden" value="{{ Auth::user()->id }}" name="author" />
            </form>
        </div>
        <div id="user-menu">
            <p>{{ Auth::user()->name }}<img src="{{ asset('svg/arrow-down.svg') }}" /></p>
            <div id="user-options">
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
        <a href="{{ route('formCreateArticle') }}"><img src="{{ asset('svg/add-file.svg') }}" />Artículos</a>
        <a href="{{ route('formGallery') }}"><img src="{{ asset('svg/image.svg') }}" />Galerías</a>
        <a href="{{ route('formPoll') }}"><img src="{{ asset('svg/note.svg') }}" />Encuestas</a>
    </div>
    <div id="loader"><center><img src="{{ asset('img/loader.gif') }}" /></center></div>
    <div id="user-content"></div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function () {
    
    // LOAD CONTENIDO PAGINA PRINCIPAL DASHBOARD
    $('#user-content').load('{{ route('viewArticles',['id' => Auth::user() -> id]) }}').prepend('<div id="loader"><center><img src="{{ asset("img/loader.gif") }}" /></center></div>').hide().fadeIn('normal');

    // NAVEGACIÓN CONTENIDO PRINCIPAL DEL DASHBOARD
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

    // BUSCADOR CONTENIDO USUARIOS
    $('button#search-button').on('click', function(e){            
        e.preventDefault();
        var form = $('#search');
        var formAction = $(form).attr('action');           
        $.ajax({
            type: 'get',
            url: formAction,
            data: $(form).serialize(),
            beforeSend: function(){
                $('#loader').fadeIn('fast', 'linear');
            }
        }).done(function(responseText){ 
            $('#loader').fadeOut('fast', 'linear', function(){
                $('#user-content').html(responseText).fadeIn('slow', 'linear');
                $(form).trigger('reset');
            });        
        }).fail(function(xhr, statusText){
            $('#loader').fadeOut('fast', 'linear', function(){
                $('#user-content').empty();
                $('#user-content').prepend('<p class="invalid-feedback">Error: '+xhr.statusText+'</p>'); 
            });
        });
    });           
});
</script>
@endsection