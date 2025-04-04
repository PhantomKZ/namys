@extends('layouts.app')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-sidebar">
                <img src="{{ asset('images/main/namyslogowhite.png') }}" alt="NAMYS">
                <h2>Добро пожаловать!</h2>
                <p>Войдите в свой аккаунт или создайте новый, чтобы получить доступ к эксклюзивным предложениям и отслеживанию заказов.</p>
            </div>
            <div class="auth-main">
                <div class="auth-switch">
                    <button class="active" onclick="switchForm('login')">Вход</button>
                    <button onclick="switchForm('register')">Регистрация</button>
                </div>

                <div class="forms-container">

                    <form class="auth-form active" id="login-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label>Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus
                                   class="@error('password') is-invalid @enderror" placeholder="Введите ваш email" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Пароль</label>
                            <input id="password" type="password" name="password"
                                   class="@error('password') is-invalid @enderror" placeholder="Введите ваш пароль" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <button type="submit" class="auth-button"> Войти </button>

                        <div class="social-auth">
                            <p>Или войдите через</p>
                            <div class="social-buttons">
                                <a href="#" class="social-button">
                                    <img src="{{ asset('images/auth/google.png') }}" alt="Google">
                                </a>
                                <a href="#" class="social-button">
                                    <img src="{{ asset('images/auth/facebook.png') }}" alt="Facebook">
                                </a>
                                <a href="#" class="social-button">
                                    <img src="{{ asset('images/auth/vk.png') }}" alt="VK">
                                </a>
                            </div>
                        </div>
                    </form>

                    <form class="auth-form" id="register-form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input id="name" type="text" name="name" class="@error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Введите ваше имя" required autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Введите ваш email" required autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input id="password" type="password" name="password" class="@error('password') is-invalid @enderror" placeholder="Придумайте пароль" required autocomplete="new-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">Подтверждение пароля</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Повторите пароль">
                        </div>
                        <button type="submit" class="auth-button">Зарегистрироваться</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
