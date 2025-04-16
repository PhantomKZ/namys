@extends('layouts.app')
@section('content')
    <div class="container-fluid">
    <div class="search-sort-section">
        <div class="container">
            <div class="search-container">
                <div class="search-box">
                    <input type="text" placeholder="Поиск" class="search-input">
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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

    <div class="catalog">
        <div class="products-grid">
            <a href="product/product1.html" class="catalog-product-card">
                <div class="product-image-container">
                    <span class="new-badge">NEW</span>
                    <img src="{{ asset('images/catalog/aqnamys.jpeg') }}" alt="Товар 1" class="product-image">
                </div>
                <h3 class="product-title">Футболка AQ Namys</h3>
                <p class="product-price">7990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product2.html" class="catalog-product-card">
                <div class="product-image-container">
                    <span class="new-badge">NEW</span>
                    <img src="{{ asset('images/catalog/namyssweatshirt.jpeg') }}" alt="Товар 2" class="product-image">
                </div>
                <h3 class="product-title">Свитшот QARA Namys</h3>
                <p class="product-price">10 990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product3.html" class="catalog-product-card">
                <div class="product-image-container">
                    <span class="new-badge">NEW</span>
                    <img src="{{ asset('images/catalog/namysshopper.jpeg') }}" alt="Товар 3" class="product-image">
                </div>
                <h3 class="product-title">Сумка шопер AQ Namys</h3>
                <p class="product-price">3350₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product4.html" class="catalog-product-card">
                <div class="product-image-container">
                    <span class="new-badge">NEW</span>
                    <img src="{{ asset('images/catalog/aqkepka.jpg') }}" alt="Товар 4" class="product-image">
                </div>
                <h3 class="product-title">Кепка AQ Namys</h3>
                <p class="product-price">2350₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product5.html" class="catalog-product-card">
                <div class="product-image-container">
                    <span class="new-badge">NEW</span>
                    <img src="{{ asset('images/catalog/item5.jpg') }}" alt="Товар 5" class="product-image">
                </div>
                <h3 class="product-title">Футболка KIIKII с принтом</h3>
                <p class="product-price">6890₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product6.html" class="catalog-product-card">
                <div class="product-image-container">
                    <span class="new-badge">NEW</span>
                    <img src="{{ asset('images/catalog/item6.jpg') }}" alt="Товар 6" class="product-image">
                </div>
                <h3 class="product-title">Худи Cyber Art</h3>
                <p class="product-price">9990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product7.html" class="catalog-product-card">
                <div class="product-image-container">
                    <span class="new-badge">NEW</span>
                    <img src="{{ asset('images/catalog/item7.jpg') }}" alt="Товар 7" class="product-image">
                </div>
                <h3 class="product-title">Футболка KIIKII Amigo SKULL</h3>
                <p class="product-price">6290₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product8.html" class="catalog-product-card">
                <div class="product-image-container">
                    <span class="new-badge">NEW</span>
                    <img src="{{ asset('images/catalog/item8.jpg') }}" alt="Товар 8" class="product-image">
                </div>
                <h3 class="product-title">Свитшот SAMURAI 2 0 7 7</h3>
                <p class="product-price">8950₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product9.html" class="catalog-product-card">
                <div class="product-image-container">
                    <img src="{{ asset('images/catalog/item9.jpg') }}" alt="Товар 9" class="product-image">
                </div>
                <h3 class="product-title">Рубашка Cyber Art</h3>
                <p class="product-price">7990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product10.html" class="catalog-product-card">
                <div class="product-image-container">
                    <img src="{{ asset('images/catalog/item10.jpg') }}" alt="Товар 10" class="product-image">
                </div>
                <h3 class="product-title">Рюкзак "Кот-программист и бинарный код"</h3>
                <p class="product-price">8990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product11.html" class="catalog-product-card">
                <div class="product-image-container">
                    <img src="{{ asset('images/catalog/item11.jpg') }}" alt="Товар 11" class="product-image">
                </div>
                <h3 class="product-title">Худи "Chainsaw Man - Kitsune no akuma"</h3>
                <p class="product-price">10650₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product12.html" class="catalog-product-card">
                <div class="product-image-container">
                    <img src="{{ asset('images/catalog/item12.jpg') }}" alt="Товар 12" class="product-image">
                </div>
                <h3 class="product-title">Брюки Blackness</h3>
                <p class="product-price">5490₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product13.html" class="catalog-product-card">
                <div class="product-image-container">
                    <img src="{{ asset('images/catalog/item13.jpg') }}" alt="Товар 13" class="product-image">
                </div>
                <h3 class="product-title">Свитшот Kitagawa's Eyes</h3>
                <p class="product-price">9990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product14.html" class="catalog-product-card">
                <div class="product-image-container">
                    <img src="{{ asset('images/catalog/item14.jpg') }}" alt="Товар 14" class="product-image">
                </div>
                <h3 class="product-title">Брюки "Chainsaw Man - Хвостик Почита"</h3>
                <p class="product-price">10990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product15.html" class="catalog-product-card">
                <div class="product-image-container">
                    <img src="{{ asset('images/catalog/item15.jpg') }}" alt="Товар 15" class="product-image">
                </div>
                <h3 class="product-title">Кепка Chil Guy</h3>
                <p class="product-price">4990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
            <a href="product/product16.html" class="catalog-product-card">
                <div class="product-image-container">
                    <img src="{{ asset('images/catalog/item16.jpg') }}" alt="Товар 16" class="product-image">
                </div>
                <h3 class="product-title">Женское худи One Heart</h3>
                <p class="product-price">12990₸</p>
                <button class="add-to-cart">Добавить в корзину</button>
            </a>
        </div>

        <div class="pagination">
            <a href="catalog1.html" class="prev">«</a>
            <a href="catalog.html" class="active">1</a>
            <a href="catalog1.html">2</a>
            <a href="catalog1.html" class="next">»</a>
        </div>
    </div>
    </div>
@endsection
