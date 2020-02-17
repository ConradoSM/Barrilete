@extends('layouts.login')
@section('title', __('Reset Password'))
@section('content')
<p class="dashboard-title"><img src="{{asset('svg/lock.svg')}}" />{{ __('Reset Password') }}</p>
 @if (session('status'))<p class="alert feedback-success" role="alert">{{ session('status') }}</p>@endif
<form method="post" action="{{ route('password.email') }}" id="email">
    @csrf
    @if ($errors->has('email'))
    <p class="alert feedback-error" role="alert">{{ $errors->first('email') }}</p>
    @endif
    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' error' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required>
    <input type="submit" value="{{ __('Send Password Reset Link') }}" class="button primary" />
</form>
<script>
    $('form#email').validate({
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
            }
        },
        errorElement: 'p',
        errorPlacement: function (error, element) {
            element.after(error);
        }
    });
</script>
@endsection
