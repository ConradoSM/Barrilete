@extends('layouts.login')
@section('title', __('Reset Password'))
@section('content')
<p class="dashboard-title"><img src="{{asset('svg/lock.svg')}}" />{{ __('Reset Password') }}</p>
 @if (session('status'))<p class="alert feedback-success" role="alert">{{ session('status') }}</p>@endif
<form method="post" action="{{ route('password.update') }}" id="reset">
    @csrf
    @if ($errors->has('email'))
    <p class="alert feedback-error" role="alert">{{ $errors->first('email') }}</p>
    @endif
    <input placeholder="{{ __('E-Mail Address') }}" id="email" type="email" class="form-control{{ $errors->has('email') ? ' error' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>
    @if ($errors->has('password'))
    <p class="alert feedback-error" role="alert"><img src="{{ asset('svg/ajax-error.svg') }}" alt="Error"/>{{ $errors->first('password') }}</p>
    @endif
    <input placeholder="{{ __('Password') }}" id="password" type="password" class="form-control{{ $errors->has('password') ? ' error' : '' }}" name="password" required>
    <input placeholder="{{ __('Confirm Password') }}" id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
    <input type="submit" value="{{ __('Reset Password') }}" class="button primary" />
    <input type="hidden" name="token" value="{{ $token }}">
</form>
<script>
    $('form#reset').validate({
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
