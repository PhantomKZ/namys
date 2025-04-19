@extends('layouts.app')
@section('content')
    <div class="product-page">
        <div class="container-fluid">
            <div class="breadcrumb-nav">
                <a href="{{ route('collection.index') }}">Look Collection</a> / <span>Street Look</span>
            </div>

            <div class="product-details">
                <div class="product-gallery">
                    <div id="productCarousel" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            @foreach($collection->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset($image->path) }}" alt="{{ $collection->name }}" class="main-image">
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
                        @foreach($collection->images as $index => $image)
                            <button type="button" class="thumbnail-button active" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}">
                                <img src="{{ asset($image->path) }}" alt="Миниатюра {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="product-info">
                    <h1 class="product-title">Комплект "{{ $collection->name }}"</h1>
                    <div class="product-price">{{ $collection->formattedPrice }}₸</div>

                    <div class="product-description">
                        <p>{{ $collection->description }}</p>
                        <ul class="features-list">
                            @foreach($products as $item)
                                <li>- {{ $item->type }} {{ $item->name }} ({{ $item->color }}) - {{ $item->formattedPrice }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="size-selector">
                        <select id="size_id_select" class="form-control" required>
                            <option value="">Выберите размер</option>
                            @foreach($collection->availableSizes() as $size)
                                @if($size->quantity > 0)
                                    <option value="{{ $size->id }}">
                                        {{ $size->name }} ({{ $size->quantity }} в наличии)
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="size-help">
                        <a href="#" class="size-guide">ПОМОЩЬ С РАЗМЕРОМ</a>
                        <a href="#" class="delivery-info">О ДОСТАВКЕ</a>
                    </div>

                    <form action="{{ route('cart.addAll') }}" method="POST" id="add-all-form">
                        @csrf
                        <input type="hidden" name="size_id" id="size_id_input">
                        @error('size_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @foreach($products as $product)
                            <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                        @endforeach
                        <button type="submit" class="add-to-cart-btn">Купить весь лук</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="recommendations">
        <div class="container">
            <h2 class="section-title">Отдельные товары из комплекта</h2>
            <div class="recommendations-container">
                <div class="recommendations-grid">
                    @foreach($products as $product)
                        <a href="{{ route('product.show', $product->id) }}" class="catalog-product-card">
                            <div class="product-image-container">
                                <img src="{{ asset($product->mainImage) }}" alt="{{ $product->title }}"
                                     class="product-image">
                            </div>
                            <h3 class="product-title">{{ $product->title }}</h3>
                            <p class="product-price">{{ $product->formattedPrice }}₸</p>
                            <button class="add-to-cart">Добавить в корзину</button>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('size_id_select');
            const input = document.getElementById('size_id_input');

            select.addEventListener('change', function () {
                input.value = this.value;
            });

            // Если select уже выбран (например, при возврате формы), то установить значение:
            if (select.value) {
                input.value = select.value;
            }
        });
    </script>
@endsection
