@extends('layouts.login')
@section('title',  __('Login'))
@section('content')
<p class="dashboard-title"><img src="{{asset('svg/log-in.svg')}}" />{{ __('Login') }}</p>
@if (session('success'))
<p class="alert feedback-success"><img src="{{ asset('svg/ajax-success.svg') }}" alt="Exito"/>{{ session('success') }}</p>
@endif
<form method="post" action="{{ route('login') }}" id="login">
    @csrf
    @if ($errors->has('email'))
    <p class="alert feedback-error" role="alert">{{ $errors->first('email') }}</p>
    @endif
    @if ($errors->has('password'))
    <p class="alert feedback-error" role="alert">{{ $errors->first('password') }}</p>
    @endif
    <input id="email" type="email" class="{{ $errors->has('email') ? ' error' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required autofocus>
    <input id="password" type="password" class="{{ $errors->has('password') ? ' error' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
    <input type="submit" value="Ingresar" class="button primary" />
    <label class="check-container" for="remember">{{ __('Remember Me') }}
        <input type="checkbox" checked="checked" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <span class="check-mark"></span>
    </label>
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
