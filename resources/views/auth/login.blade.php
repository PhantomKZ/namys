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
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="@error('email', 'login') is-invalid @enderror" placeholder="Введите ваш email" required>
                            @error('email', 'login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Пароль</label>
                            <input id="password" type="password" name="password"
                                   class="@error('password', 'login') is-invalid @enderror" placeholder="Введите ваш пароль" required autocomplete="current-password">
                            @error('password', 'login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="auth-button"> Войти </button>
                    </form>

                    <!-- Регистрация форма -->
                    <form class="auth-form" id="register-form" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input id="name" type="text" name="name" class="@error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Введите ваше имя" required autocomplete="name">
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
