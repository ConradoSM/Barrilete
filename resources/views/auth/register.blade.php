@extends('layouts.barrilete')
@section('title', __('Register'))
@section('content')
<div class="dashboard-container">
    <div class="dashboard-login">
        <p><img src="{{asset('svg/adding-users.svg')}}" />{{ __('Register') }}</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" required autofocus>
            @if ($errors->has('name'))<p class="invalid-feedback" role="alert"><img src="{{ asset('svg/ajax-error.svg') }}" alt="Error"/>{{ $errors->first('name') }}</p>@endif
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required>
            @if ($errors->has('email'))<p class="invalid-feedback" role="alert"><img src="{{ asset('svg/ajax-error.svg') }}" alt="Error"/>{{ $errors->first('email') }}</p>@endif
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
            @if ($errors->has('password'))<p class="invalid-feedback" role="alert"><img src="{{ asset('svg/ajax-error.svg') }}" alt="Error"/>{{ $errors->first('password') }}</p>@endif
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
            <input type="submit" value="{{ __('Register') }}" class="primary" />
        </form>
    </div>
</div>
@endsection
