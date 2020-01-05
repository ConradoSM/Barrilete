@extends('layouts.barrilete')
@section('title', __('Reset Password'))
@section('content')
<div class="dashboard-container">
    <div class="dashboard-login">
        <p><img src="{{asset('svg/lock.svg')}}" />{{ __('Reset Password') }}</p>
         @if (session('status'))<p class="alert feedback-success" role="alert"><img src="{{ asset('svg/ajax-success.svg') }}" alt="Error"/>{{ session('status') }}</p>@endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            @if ($errors->has('email'))
            <p class="alert feedback-error" role="alert"><img src="{{ asset('svg/ajax-error.svg') }}" alt="Error"/>{{ $errors->first('email') }}</p>
            @endif
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' error' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required>
            <input type="submit" value="{{ __('Send Password Reset Link') }}" class="button primary" />
        </form>
    </div>
</div>
@endsection
