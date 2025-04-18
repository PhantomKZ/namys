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
            @foreach($products as $product)
                <a href="{{ route('product.show', $product->id) }}" class="catalog-product-card lazy-content">
                    <div class="product-image-container">
                        <span class="new-badge">NEW</span>
                        <img src="{{ asset($product->mainImage) }}" alt="{{ $product->title }}" class="product-image">
                    </div>
                    <h3 class="product-title">{{ $product->title }}</h3>
                    <p class="product-price">{{ $product->formattedPrice }}</p>
                    <button class="add-to-cart">Добавить в корзину</button>
                </a>
            @endforeach
        </div>

        @if ($products->lastPage() > 1)
            <div class="pagination">
                @if ($products->onFirstPage())
                    <a href="#" class="prev disabled" onclick="return false;">«</a>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="prev">«</a>
                @endif

                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    @if ($i == $products->currentPage())
                        <a href="{{ $products->url($i) }}" class="active">{{ $i }}</a>
                    @else
                        <a href="{{ $products->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="next">»</a>
                @else
                    <a href="#" class="next disabled" onclick="return false;">»</a>
                @endif
            </div>
        @endif

    </div>
    </div>
@endsection
