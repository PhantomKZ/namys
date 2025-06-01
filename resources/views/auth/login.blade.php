@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-sidebar">
                <img src="{{ asset('images/main/namyslogowhite.png') }}" alt="NAMYS">
                <h2>{{ __('messages.welcome') }}</h2>
                <p>{{ __('messages.auth_description') }}</p>
            </div>
            <div class="auth-main">
                <div class="auth-switch">
                    <button class="active" onclick="switchForm('login')">{{ __('messages.login') }}</button>
                    <button onclick="switchForm('register')">{{ __('messages.register') }}</button>
                </div>

                <div class="forms-container">
                    <form class="auth-form active" id="login-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label>Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="@error('email', 'login') is-invalid @enderror" placeholder="{{ __('messages.enter_email') }}" required>
                            @error('email', 'login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>{{ __('messages.password') }}</label>
                            <input id="password" type="password" name="password"
                                   class="@error('password', 'login') is-invalid @enderror" placeholder="{{ __('messages.enter_password') }}" required autocomplete="current-password">
                            @error('password', 'login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="auth-button">{{ __('messages.login') }}</button>
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">{{ __('messages.forgot_password') }}</a>
                        </div>
                    </form>

                    <form class="auth-form" id="register-form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('messages.name') }}</label>
                            <input id="name" type="text" name="name" class="@error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('messages.enter_name') }}" required autocomplete="name">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('messages.enter_email') }}" required autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('messages.password') }}</label>
                            <input id="password" type="password" name="password" class="@error('password') is-invalid @enderror" placeholder="{{ __('messages.create_password') }}" required autocomplete="new-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">{{ __('messages.confirm_password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('messages.repeat_password') }}">
                        </div>
                        <button type="submit" class="auth-button">{{ __('messages.register') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function switchForm(formType) {
            const buttons = document.querySelectorAll('.auth-switch button');
            buttons.forEach(button => button.classList.remove('active'));
            event.target.classList.add('active');

            const formsContainer = document.querySelector('.forms-container');
            const forms = document.querySelectorAll('.auth-form');

            if (formType === 'login') {
                formsContainer.classList.remove('show-register');
                forms[0].classList.add('active');
                forms[1].classList.remove('active');
            } else {
                formsContainer.classList.add('show-register');
                forms[0].classList.remove('active');
                forms[1].classList.add('active');
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->getBag('login')->any())
            switchForm('login');
            @elseif ($errors->any())
            switchForm('register');
            @endif
        });
    </script>
@endsection
