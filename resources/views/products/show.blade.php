@extends('layouts.app')
@section('content')
    <div class="product-page">
        <div class="container-fluid">
            <div class="breadcrumb-nav">
                <a href="{{ route('catalog') }}">КАТАЛОГ</a> / <a href="#">Футболки</a>
            </div>

            <div class="product-details">
                <div class="product-gallery">
                    <div id="productCarousel" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset($image->path) }}" alt="Изображение {{ $index + 1 }}" class="main-image">
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
                                <img src="{{ asset($image->path) }}" alt="Миниатюра {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="product-info">
                    <h1 class="product-title">
                        {{ $product->type }} "{{ $product->name}}"
                    </h1>
                    <div class="product-price">{{ $product->formattedPrice }}₸</div>

                    <div class="product-description">
                        <p>{{ $product->description }}</p>
                        <ul class="features-list">
                            <li><strong>Бренд:</strong> {{ $product->brand->name }}</li>
                            <li><strong>Материал:</strong> {{ $product->material->name }}</li>
                            <li><strong>Цвет:</strong> {{ $product->color }}</li>
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

                    <div class="button-container">
                        <button class="add-to-cart-btn">Добавить в корзину</button>
                        @if(auth()->user()->favorites->contains($product->id))
                            <form action="{{ route('product.removeFromFavorites', $product->id) }}" method="POST">
                                @csrf
                                <button class="d-flex btn add-to-favorites-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="white" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    <span class="text-nowrap"> Удалить из избранного </span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('product.addToFavorites', $product->id) }}" method="POST">
                                @csrf
                                <button class="d-flex btn add-to-favorites-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="white" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    <span class="text-nowrap"> Добавить в избранное </span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="recommendations">
        <div class="container">
            <h2 class="section-title">Могут понравиться</h2>
            <div class="recommendations-container">
                <div class="recommendations-grid">
                    @foreach($recommendations as $item)
                        <a href="{{ route('product.show', $item->id) }}" class="catalog-product-card">
                            <div class="product-image-container">
                                <img src="{{ $item->mainImage ? asset($item->mainImage) : '/images/default_main_image.jpg' }}" alt="{{ $item->name }}" class="product-image">
                            </div>
                            <h3 class="product-title">{{ $item->name }}</h3>
                            <p class="product-price">{{ $item->formattedPrice }}₸</p>
                            <button class="add-to-cart">Добавить в корзину</button>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
