@extends('layouts.barrilete')
@section('title', __('Reset Password'))
@section('content')
<div class="dashboard-container">
    <div class="dashboard-login">
        <p><img src="{{asset('svg/lock.svg')}}" />{{ __('Reset Password') }}</p>
         @if (session('status'))<p class="alert feedback-success" role="alert">{{ session('status') }}</p>@endif
        <form method="POST" action="{{ route('password.update') }}">
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
    </div>
</div>
@endsection
