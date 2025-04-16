@extends('layouts.app')
@section('content')
    <div class="product-page">
        <div class="container">
            <div class="breadcrumb-nav">
                <a href="{{ route('catalog') }}">КАТАЛОГ</a> / <a href="#">Футболки</a>
            </div>

            <div class="product-details">
                <div class="product-gallery">
                    <div id="productCarousel" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Изображение {{ $index + 1 }}" class="main-image">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Предыдущий</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Следующий</span>
                        </button>
                    </div>
                    <div class="product-thumbnails">
                        @foreach($product->images as $index => $image)
                            <button type="button"
                                    class="thumbnail-button {{ $index === 0 ? 'active' : '' }}"
                                    data-bs-target="#productCarousel"
                                    data-bs-slide-to="{{ $index }}">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Миниатюра {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="product-info">
                    <h1 class="product-title">
                        {{ $product->type->name }} "{{ $product->name}}"
                    </h1>
                    <div class="product-price">{{ $product->price }}₸</div>

                    <div class="product-description">
                        <p>{{ $product->description }}</p>
                        <ul class="features-list">
                            <li><strong>Бренд:</strong> {{ $product->brand->name }}</li>
                            <li><strong>Материал:</strong> {{ $product->material->name }}</li>
                            <li><strong>Цвет:</strong> {{ $product->color->name }}</li>
                        </ul>
                    </div>

                    <div class="size-selector">
                        <button class="size-dropdown" data-bs-toggle="dropdown">
                            выберите размер <span class="arrow">▼</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($product->sizes as $size)
                                <li>
                                    <a class="dropdown-item" href="#">{{ $size->name }} ({{$size->pivot->quantity}} КАТАЛОГ)</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="size-help">
                        <a href="#" class="size-guide">ПОМОЩЬ С РАЗМЕРОМ</a>
                        <a href="#" class="delivery-info">О ДОСТАВКЕ</a>
                    </div>

                    <button class="add-to-cart-btn">Добавить в корзину</button>
                </div>
            </div>
        </div>
    </div>

    <div class="recommendations">
        <div class="container">
            <h2 class="section-title">Могут понравиться</h2>
            <div class="recommendations-container">
                <div class="recommendations-grid">
                    <a href="../product/product2.html" class="catalog-product-card">
                        <div class="product-image-container">
                            <img src="../image/catalog/namyssweatshirt.jpeg" alt="Свитшот QARA NAMYS" class="product-image">
                        </div>
                        <h3 class="product-title">Свитшот QARA NAMYS</h3>
                        <p class="product-price">10 990₸</p>
                        <button class="add-to-cart">Добавить в корзину</button>
                    </a>
                    <a href="../product/product3.html" class="catalog-product-card">
                        <div class="product-image-container">
                            <img src="../image/catalog/namysshopper.jpeg" alt="Сумка шопер AQ Namys" class="product-image">
                        </div>
                        <h3 class="product-title">Сумка шопер AQ Namys</h3>
                        <p class="product-price">3350 тг</p>
                        <button class="add-to-cart">Добавить в корзину</button>
                    </a>
                    <a href="../product/product4.html" class="catalog-product-card">
                        <div class="product-image-container">
                            <img src="../image/catalog/aqkepka.jpg" alt="Кепка AQ Namys" class="product-image">
                        </div>
                        <h3 class="product-title">Кепка AQ Namys</h3>
                        <p class="product-price">2350 тг</p>
                        <button class="add-to-cart">Добавить в корзину</button>
                    </a>
                    <a href="../product/product5.html" class="catalog-product-card">
                        <div class="product-image-container">
                            <img src="../image/catalog/item5.jpg" alt="Футболка KIIKII с принтом" class="product-image">
                        </div>
                        <h3 class="product-title">Футболка KIIKII с принтом</h3>
                        <p class="product-price">6890 тг</p>
                        <button class="add-to-cart">Добавить в корзину</button>
                    </a>
                    <a href="../product/product6.html" class="catalog-product-card">
                        <div class="product-image-container">
                            <img src="../image/catalog/item6.jpg" alt="Худи Cyber Art" class="product-image">
                        </div>
                        <h3 class="product-title">Худи Cyber Art</h3>
                        <p class="product-price">9990 тг</p>
                        <button class="add-to-cart">Добавить в корзину</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
