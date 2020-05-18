@extends('layouts.barrilete')
@section('title', 'Miembros')
<meta name="_token" content="{{ csrf_token() }}">
@section('content')
    <div id="users-menu">
        <div class="user-info">
            <img src="{{Auth::user()->photo ? asset('img/users/'.Auth::user()->photo) : asset('svg/user-blue.svg')}}" alt="{{Auth::user()->name}}">
            <p>{{Auth::user()->name}}</p>
        </div>
        <hr>
        <ul class="menu">
            <li title="Mi Actividad">
                <p><img src="{{asset('svg/report.svg')}}" alt="Mi Actividad">Mi Actividad</p>
            </li>
            <li title="Mis Mensajes">
                <p><img src="{{asset('svg/chat.svg')}}" alt="Mis Mensajes">Mis Mensajes<img src="{{asset('svg/arrow.svg')}}" class="arrow" alt="sub-menu"></p>
                <ul class="sub-menu">
                    <li title="Ver Todos" data-link="{{route('myMessagesInbox')}}">Ver Todos</li>
                    <li title="Redactar Nuevo" data-link="{{route('writeMessage')}}">Redactar Nuevo</li>
                </ul>
            </li>
            @if(Auth::user()->hasRole('admin'))
            <li title="Dashboard" onclick="location.href='{{route('dashboard')}}'">
                <p><img src="{{asset('svg/dashboard.svg')}}" alt="Dashboard">Dashboard</p>
            </li>
            @endif
            <li title="Configuración">
                <p><img src="{{asset('svg/settings.svg')}}" alt="Configuración">Configuración<img src="{{asset('svg/arrow.svg')}}" class="arrow" alt="sub-menu"></p>
                <ul class="sub-menu">
                    <li title="Editar Cuenta" data-link="{{route('editUser', ['id' => Auth::id(), 'home' => '1'])}}">Editar Cuenta</li>
                    <li title="Privacidad" data-link="{{route('editMyPrivacy')}}">Privacidad</li>
                    <li title="Cambiar Contraseña" data-link="{{route('editMyPassword')}}">Cambiar Contraseña</li>
                </ul>
            </li>
            <li title="Salir" onclick="location.href='{{route('logout')}}'">
                <p><img src="{{asset('svg/logout.svg')}}" alt="Salir">Salir</p>
            </li>
        </ul>
    </div>
    <div id="users-content"></div>
    <script>
        if (window.location.search) {
            const url_string = window.location.href;
            const url = new URL(url_string);
            if (url.searchParams.get('conversation_id') && !isNaN(url.searchParams.get('conversation_id'))) {
                $.ajax({
                    type: 'GET',
                    url: '/dashboard/myaccount/messages/message/' + url.searchParams.get('conversation_id'),
                    beforeSend: function () {
                        $(document).scrollTop(0);
                        $('div#users-content').html('<img id="loader" src="/img/loader.gif" />');
                    },
                    success: function (data) {
                        $('div#users-content').html(data.view);
                    },
                    error: function (xhr) {
                        $('div#users-content').html('<p class="alert feedback-error">' + xhr.statusText + '</p>');
                    }
                });
            }
        }
    </script>
@endsection
