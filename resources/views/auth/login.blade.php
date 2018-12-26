@extends('layouts.barrilete')
@section('title', 'Entrar al sistema')
@section('content')
<div class="dashboard-container">
    <div class="dashboard-login">
        <p><img src="{{asset('svg/log-in.svg')}}" /> {{ __('Login') }}</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if ($errors->has('email'))
            <p class="invalid-feedback" role="alert">{{ $errors->first('email') }}</p>
            @endif
            @if ($errors->has('password'))
            <p class="invalid-feedback" role="alert">{{ $errors->first('password') }}</p>
            @endif
            
            <input id="email" type="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required autofocus>
            <input id="password" type="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
            @endif
            <input type="submit" value="Ingresar" />
            
            <label class="check-container" for="remember">{{ __('Remember Me') }}
                <input type="checkbox" checked="checked" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="check-mark"></span>
            </label>
        </form>
    </div>
</div>
@endsection
