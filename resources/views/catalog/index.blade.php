@extends('layouts.app')
@section('content')
    <div class="container-fluid">
    <div class="search-sort-section">
        <div class="container">
            <div class="search-container">
                @if(request()->has('search') && request()->get('search') !== '')
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary">Очистить</a>
                @endif
                <div class="search-box">
                    <form action="{{ route('catalog.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" placeholder="Поиск" class="search-input" value="{{ old('search', $searchTerm) }}">
                        <button class="search-button" type="submit">НАЙТИ</button>
                    </form>
                </div>

                    <div class="sort-container">
                        <span class="sort-label">Сортировать:</span>
                        <form action="{{ route('catalog.index') }}" method="GET">
                            <input type="hidden" name="search" value="{{ old('search', $searchTerm) }}"> <!-- скрытый input для поиска -->
                            <select class="sort-select" name="sort_by" onchange="this.form.submit()">
                                <option value="">Сортировать</option>
                                <option value="price_asc" {{ $sortBy == 'price_asc' ? 'selected' : '' }}>По цене (по возрастанию)</option>
                                <option value="price_desc" {{ $sortBy == 'price_desc' ? 'selected' : '' }}>По цене (по убыванию)</option>
                                <option value="name_asc" {{ $sortBy == 'name_asc' ? 'selected' : '' }}>По имени (А-Я)</option>
                                <option value="name_desc" {{ $sortBy == 'name_desc' ? 'selected' : '' }}>По имени (Я-А)</option>
                            </select>
                        </form>
                    </div>
            </div>
        </div>
    </div>

    <div class="catalog">
        <div class="products-grid">
            @foreach($products as $product)
                <a href="{{ route('product.show', $product->id) }}" class="catalog-product-card">
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
@section('scripts')

@endsection
