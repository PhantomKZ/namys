<header>
    <nav class="navbar navbar-expand-lg
    {{ request()->routeIs('home') ? 'navbar-main' : 'navbar-light' }}">
        <div class="container-fluid d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ request()->routeIs('home')
            ? asset('images/main/namyslogowhite.png')
            : asset('images/main/namyslogoblack.png') }}"
                     alt="Логотип" width="95" height="70">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ $current == 'catalog.index' ? 'active' : '' }}" href="{{ route('catalog.index') }}">КАТАЛОГ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $current == 'catalog.novelty' ? 'active' : '' }}" href="{{ route('catalog.novelty') }}">НОВИНКИ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $current == 'catalog.limited' ? 'active' : '' }}" href="{{ route('catalog.limited') }}">LIMITED
                            EDITION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $current == 'collection.index' ? 'active' : '' }}" href="{{ route('collection.index') }}">LOOK
                            COLLECTION</a>
                    </li>
                    <li class="nav-item">
                        <div class="language-switcher">
                            <a href="{{ route('language.switch', 'ru') }}" class="{{ app()->getLocale() == 'ru' ? 'active' : '' }}">RU</a>
                            <span class="separator">|</span>
                            <a href="{{ route('language.switch', 'kz') }}" class="{{ app()->getLocale() == 'kz' ? 'active' : '' }}">KZ</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link auth-icon"
                           href="@auth{{route('profile.show')}}@else{{route('login')}}@endauth">
                            <img src="/images/main/auth.png" alt="Авторизация" width="48" height="48">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link cart-icon" href="{{ route('cart.index') }}">
                            <img src="/images/main/basket.png" alt="Корзина" width="48" height="48">
                            @if($cartCount > 0)
                                <span class="cart-count">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
