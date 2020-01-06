@extends('layouts.barrilete')
@section('title',  __('Login'))
@section('content')
<div class="dashboard-container">
    <div class="dashboard-login">
        <p><img src="{{asset('svg/log-in.svg')}}" />{{ __('Login') }}</p>
        @if (session('success'))
        <p class="alert feedback-success"><img src="{{ asset('svg/ajax-success.svg') }}" alt="Exito"/>{{ session('success') }}</p>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if ($errors->has('email'))
            <p class="alert feedback-error" role="alert">{{ $errors->first('email') }}</p>
            @endif
            @if ($errors->has('password'))
            <p class="alert feedback-error" role="alert">{{ $errors->first('password') }}</p>
            @endif
            <input id="email" type="email" class="{{ $errors->has('email') ? ' error' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required autofocus>
            <input id="password" type="password" class="{{ $errors->has('password') ? ' error' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
            @endif
            <input type="submit" value="Ingresar" class="button primary" />
            <label class="check-container" for="remember">{{ __('Remember Me') }}
                <input type="checkbox" checked="checked" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="check-mark"></span>
            </label>
        </form>
    </div>
</div>
@endsection
