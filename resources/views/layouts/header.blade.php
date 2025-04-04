<header>
    <nav class="navbar navbar-expand-lg
    {{ request()->routeIs('home') ? 'navbar-main' : 'navbar-light' }}">
    <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ request()->routeIs('home')
            ? asset('images/main/namyslogowhite.png')
            : asset('images/main/namyslogoblack.png') }}"
                     alt="Логотип" width="95" height="70">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="catalog.html">В НАЛИЧИИ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="novelty.html">НОВИНКИ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="limited.html">LIMITED EDITION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">LOOK COLLECTION</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link auth-icon" href="{{ route('login') }}">
                            <img src="/images/main/auth.png" alt="Авторизация" width="48" height="48">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link cart-icon" href="basket.html">
                            <img src="/images/main/basket.png" alt="Корзина" width="48" height="48">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
