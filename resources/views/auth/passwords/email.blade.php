@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-sidebar">
                <img src="{{ asset('images/main/namyslogowhite.png') }}" alt="NAMYS">
                <h2>{{ __('messages.reset_password') }}</h2>
                <p>{{ __('messages.reset_password_description') }}</p>
            </div>
            <div class="auth-main">
                <div class="auth-form active reset-password-form">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('messages.enter_email') }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="auth-button">{{ __('messages.send_reset_link') }}</button>
                        <div class="back-to-auth-text">
                            <a href="{{ route('login') }}">{{ __('messages.back_to_login') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
