@extends('layouts.app')
@section('content')
    <img src="{{ asset('images/novelty.jpg') }}" alt="Новинки Banner" class="banner">
    <div class="container-fluid">
        <h1 class="nov-title">НОВИНКИ</h1>

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
                            <option value="tshirts">Футболки</option>
                            <option value="hoodies">Худи</option>
                            <option value="sweatshirts">Свитшоты</option>
                            <option value="pants">Брюки</option>
                            <option value="caps">Кепки</option>
                            <option value="shoes">Обувь</option>
                            <option value="backpacks">Рюкзаки</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="nov-products-grid">
            <a href="./product/product1.html" class="nov-product-card">
                <div class="nov-product-image">
                    <span class="new-badge">NEW</span>
                    <img src="./image/novelty/item1.jpeg" alt="Футболка AQ NAMYS">
                </div>
                <h3 class="nov-product-title">Футболка AQ NAMYS</h3>
                <p class="nov-product-price">7990₸</p>
                <button class="nov-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product2.html" class="nov-product-card">
                <div class="nov-product-image">
                    <span class="new-badge">NEW</span>
                    <img src="./image/novelty/item2.jpeg" alt="Свитшот QARA NAMYS">
                </div>
                <h3 class="nov-product-title">Свитшот QARA NAMYS</h3>
                <p class="nov-product-price">10 990₸</p>
                <button class="nov-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product3.html" class="nov-product-card">
                <div class="nov-product-image">
                    <span class="new-badge">NEW</span>
                    <img src="./image/novelty/item3.jpeg" alt="Шопер AQ Namys">
                </div>
                <h3 class="nov-product-title">Шопер AQ Namys</h3>
                <p class="nov-product-price">3350₸</p>
                <button class="nov-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product4.html" class="nov-product-card">
                <div class="nov-product-image">
                    <span class="new-badge">NEW</span>
                    <img src="./image/novelty/item4.jpeg" alt="Кепка AQ Namys">
                </div>
                <h3 class="nov-product-title">Кепка AQ Namys</h3>
                <p class="nov-product-price">2350₸</p>
                <button class="nov-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product5.html" class="nov-product-card">
                <div class="nov-product-image">
                    <span class="new-badge">NEW</span>
                    <img src="./image/novelty/item5.jpg" alt="Футболка KIIKII с принтом">
                </div>
                <h3 class="nov-product-title">Футболка KIIKII с принтом </h3>
                <p class="nov-product-price">6980₸</p>
                <button class="nov-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product6.html" class="nov-product-card">
                <div class="nov-product-image">
                    <span class="new-badge">NEW</span>
                    <img src="./image/novelty/item6.jpg" alt="Худи Cyber Art">
                </div>
                <h3 class="nov-product-title">Худи Cyber Art</h3>
                <p class="nov-product-price">9990₸</p>
                <button class="nov-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product7.html" class="nov-product-card">
                <div class="nov-product-image">
                    <span class="new-badge">NEW</span>
                    <img src="./image/novelty/item7.jpg" alt="Футболка KIIKII Amigo Skull">
                </div>
                <h3 class="nov-product-title">Футболка KIIKII Amigo Skull</h3>
                <p class="nov-product-price">2350₸</p>
                <button class="nov-add-to-cart">Добавить в корзину</button>
            </a>
            <a href="./product/product8.html" class="nov-product-card">
                <div class="nov-product-image">
                    <span class="new-badge">NEW</span>
                    <img src="./image/novelty/item8.jpg" alt="Свитшот Samurai 2 0 7 7">
                </div>
                <h3 class="nov-product-title">Свитшот Samurai 2 0 7 7</h3>
                <p class="nov-product-price">8950₸</p>
                <button class="nov-add-to-cart">Добавить в корзину</button>
            </a>
        </div>
    </div>
@endsection
