@extends('layouts.login')
@section('title',  __('Login'))
@section('content')
<h2><img alt="login" class="dashboard-title" src="{{asset('svg/login.svg')}}" />{{ __('Login') }}</h2>
@if (session('success'))
<p class="alert feedback-success">{{ session('success') }}</p>
@endif
<form method="post" action="{{ route('login') }}" id="login">
    @csrf
    @if ($errors->has('email'))
    <p class="alert feedback-error" role="alert">{{ $errors->first('email') }}</p>
    @endif
    @if ($errors->has('password'))
    <p class="alert feedback-error" role="alert">{{ $errors->first('password') }}</p>
    @endif
    <label for="email">{{ __('E-Mail Address') }}:</label>
    <input id="email" type="email" class="{{ $errors->has('email') ? ' error' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
    <label for="password">{{ __('Password') }}:</label>
    <input id="password" type="password" class="{{ $errors->has('password') ? ' error' : '' }}" name="password" required>
    <input type="submit" value="Ingresar" class="button primary" />
    <label class="check-container" for="remember">{{ __('Remember Me') }}
        <input type="checkbox" checked="checked" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <span class="check-mark"></span>
    </label>
    @if (Request::has('previous'))
        <input type="hidden" name="referrer" value="{{ Request::get('referrer') }}">
    @else
        <input type="hidden" name="referrer" value="{{ Crypt::encrypt(URL::previous()) }}">
    @endif
</form>
<script>
    $('form#login').validate({
        rules: {
            field: {
                required: true,
                email: true,
            }
        },
        messages: {
            email: {
                required: 'Éste campo es requerido',
                email: 'La dirección ingresada no es válida'
            },
            password: {
                required: 'Éste campo es requerido'
            }
        },
        errorElement: 'p',
        errorPlacement: function (error, element) {
            element.after(error);
        }
    });
</script>
@endsection
