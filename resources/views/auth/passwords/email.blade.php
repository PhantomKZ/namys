@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-sidebar">
                <img src="{{ asset('images/main/namyslogowhite.png') }}" alt="NAMYS">
                <h2>Восстановление пароля</h2>
                <p>Введите ваш email, и мы отправим вам ссылку для сброса пароля.</p>
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
                            <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Введите ваш email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="auth-button">Отправить ссылку для сброса пароля</button>
                        <div class="back-to-auth-text">
                            <a href="{{ route('login') }}">Назад к авторизации</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
