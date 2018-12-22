@extends('layouts.barrilete')
@section('content')
<div class="dashboard-main-container">
        <div class="user-bar">
            @if (session('status'))
            <div>{{ session('status') }}</div>
            @endif
            <div class="menu-user">
                <p>{{ Auth::user()->name }}<img src="/svg/arrow-down.svg" /></p>
                <nav>
                    <ul>
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a></li>
                    </ul>               
                </nav>
            </div>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
</div>
@endsection