@extends('layouts.barrilete')
@section('title', __('Register'))
@section('content')
<div class="dashboard-container">
    <div class="dashboard-login">
        <p><img src="{{asset('svg/adding-users.svg')}}" />{{ __('Register') }}</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' error' : '' }}" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" required autofocus>
            @if ($errors->has('name'))<p class="error" role="alert">{{ $errors->first('name') }}</p>@endif
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' error' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required>
            @if ($errors->has('email'))<p class="error" role="alert">{{ $errors->first('email') }}</p>@endif
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' error' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
            @if ($errors->has('password'))<p class="error" role="alert">{{ $errors->first('password') }}</p>@endif
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
            <input type="submit" value="{{ __('Register') }}" class="button primary" />
        </form>
    </div>
</div>
@endsection
