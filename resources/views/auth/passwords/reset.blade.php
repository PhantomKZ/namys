@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-sidebar">
                <img src="{{ asset('images/main/namyslogowhite.png') }}" alt="NAMYS">
                <h2>{{ __('messages.reset_password') }}</h2>
                <p>{{ __('messages.enter_new_password') }}</p>
            </div>
            <div class="auth-main">
                <div class="auth-form active reset-password-form">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('messages.enter_email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('messages.new_password') }}</label>
                            <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('messages.enter_new_password_placeholder') }}">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">{{ __('messages.confirm_password') }}</label>
                            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('messages.repeat_new_password') }}">
                        </div>

                        <button type="submit" class="auth-button">{{ __('messages.reset_password_button') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
