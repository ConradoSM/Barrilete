@extends('layouts.login')
@section('title', __('Register'))
@section('content')
<p class="dashboard-title"><img src="{{asset('svg/adding-users.svg')}}" />{{ __('Register') }}</p>
<form method="post" action="{{ route('register') }}" id="register">
    @csrf
    <label for="name">{{ __('Name') }}:</label>
    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' error' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))<p class="error" role="alert">{{ $errors->first('name') }}</p>@endif
    <label for="email">{{ __('E-Mail Address') }}:</label>
    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' error' : '' }}" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))<p class="error" role="alert">{{ $errors->first('email') }}</p>@endif
    <label for="password">{{ __('Password') }}:</label>
    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' error' : '' }}" name="password" required>
    @if ($errors->has('password'))<p class="error" role="alert">{{ $errors->first('password') }}</p>@endif
    <label for="password-confirm">{{ __('Confirm Password') }}</label>
    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
    <input type="submit" value="{{ __('Register') }}" class="button primary" />
</form>
<script>
    $('form#register').validate({
        rules: {
            field: {
                required: true,
                email: true,
            },
            password : {
                minlength : 7
            },
            password_confirmation : {
                minlength : 7,
                equalTo : "#password"
            }
        },
        messages: {
            name: {
              required: 'Éste campo es requerido.'
            },
            email: {
                required: 'Éste campo es requerido.',
                email: 'La dirección ingresada no es válida.'
            },
            password: {
                required: 'Éste campo es requerido.',
                minlength: 'Debe tener una longitud de al menos 7 caracteres.'
            },
            password_confirmation: {
                required: 'Éste campo es requerido.',
                minlength: 'Debe tener una longitud de al menos 7 caracteres.',
                equalTo: 'Por favor ingrese el mismo valor otra vez.'
            }
        },
        errorElement: 'p',
        errorPlacement: function (error, element) {
            element.after(error);
        }
    });
</script>
@endsection
