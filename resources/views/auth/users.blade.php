@extends('layouts.barrilete')
@section('title', 'Miembros')
@section('content')
    <div id="users-menu">
        <div class="user-info">
            <img src="{{Auth::user()->photo ? asset('img/users/images/'.Auth::user()->photo) : asset('svg/user-blue.svg')}}" alt="{{Auth::user()->name}}">
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
                    <li title="Recibidos" data-link="{{route('myMessages', ['box' => 'inbox'])}}">Recibidos</li>
                    <li title="Enviados" data-link="{{route('myMessages', ['box' => 'outbox'])}}">Enviados</li>
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
                    <li title="Cambiar Contraseña" data-link="{{route('editMyPassword')}}">Cambiar Contraseña</li>
                    <li title="Opciones" data-link="{{route('MyAccountConfig')}}">Opciones</li>
                </ul>
            </li>
            <li title="Salir" onclick="location.href='{{route('logout')}}'">
                <p><img src="{{asset('svg/logout.svg')}}" alt="Salir">Salir</p>
            </li>
        </ul>
    </div>
    <div id="users-content">
        <img id="loader" class="loader" src="{{asset('svg/loader.svg')}}" alt="Cargando..." title="Cargando..." />
        <div id="container"></div>
    </div>
    <script type="text/javascript" src="{{ asset('js/dashboard-users.js') }}"></script>
@endsection
