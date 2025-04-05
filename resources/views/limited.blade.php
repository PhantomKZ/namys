@extends('layouts.app')
@section('content')
    <img src="{{ asset('images/limited.png') }}" alt="Limited Edition Banner" class="banner">
    <div class="container-fluid">
        <h1 class="le-title">LIMITED EDITION</h1>

        <div class="search-sort-section">
            <div class="container">
                <div class="search-container">
                    <div class="search-box">
                        <input type="text" placeholder="Поиск" class="search-input">
                        <div class="dropdown">
                            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                Каталог
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">По всему сайту</a></li>
                                <li><a class="dropdown-item" href="#">По каталогу</a></li>
                            </ul>
                        </div>
                        <button class="search-button">НАЙТИ</button>
                    </div>
                    <div class="sort-container">
                        <span class="sort-label">Сортировать:</span>
                        <select class="sort-select">
                            <option value="">Все товары</option>
                            <option value="hoodies">Худи</option>
                            <option value="longsleeves">Лонгсливы</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="le-products-grid">
            <a href="./product/product1.html" class="le-product-card">
                <div class="le-product-image">
                    <img src="./image/limited edition/item1.jpeg" alt="Футболка AQ NAMYS">
                </div>
                <h3 class="le-product-title">Футболка AQ NAMYS</h3>
                <p class="le-product-price">7990₸</p>
                <button class="le-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product2.html" class="le-product-card">
                <div class="le-product-image">
                    <img src="./image/limited edition/item2.jpeg" alt="Свитшот QARA NAMYS">
                </div>
                <h3 class="le-product-title">Свитшот QARA NAMYS</h3>
                <p class="le-product-price">10 990₸</p>
                <button class="le-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product3.html" class="le-product-card">
                <div class="le-product-image">
                    <img src="./image/limited edition/item3.jpeg" alt="Шопер AQ Namys">
                </div>
                <h3 class="le-product-title">Шопер AQ Namys</h3>
                <p class="le-product-price">3350₸</p>
                <button class="le-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product4.html" class="le-product-card">
                <div class="le-product-image">
                    <img src="./image/limited edition/item4.jpeg" alt="Кепка AQ Namys">
                </div>
                <h3 class="le-product-title">Кепка AQ Namys</h3>
                <p class="le-product-price">2350₸</p>
                <button class="le-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product17.html" class="le-product-card">
                <div class="le-product-image">
                    <img src="./image/limited edition/item5.jpg" alt="Свитшот OVER Duisenbi кремовая">
                </div>
                <h3 class="le-product-title">Свитшот OVER Duisenbi кремовая</h3>
                <p class="le-product-price">12 000₸</p>
                <button class="le-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product24.html" class="le-product-card">
                <div class="le-product-image">
                    <img src="./image/limited edition/item6.jpg" alt="Брюки WIDE БЕЖЕВЫЕ">
                </div>
                <h3 class="le-product-title">Брюки WIDE БЕЖЕВЫЕ</h3>
                <p class="le-product-price">15 000₸</p>
                <button class="le-add-to-cart">Добавить в корзину</button>
            </a>
        </div>
    </div>
@endsection
