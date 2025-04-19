<div class="search-sort-section">
    <div class="container">
        <div class="search-container">
            @php
                $currentRoute = url()->current();
            @endphp
            @if(request()->filled('search'))
                <a href="{{ $currentRoute }}" class="btn btn-outline-primary">Очистить</a>
            @endif
            <div class="search-box">
                <form method="GET" action="{{ $currentRoute }}" id="searchForm" class="d-flex">
                    <input type="text" name="search" placeholder="Поиск" class="search-input">

                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="searchScope">
                            Каталог
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item" onclick="event.preventDefault(); setRoute('{{ route('catalog.index') }}', 'По всему сайту');">По всему сайту</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setRoute('{{ $currentRoute }}', 'По каталогу')">По каталогу</a>
                            </li>
                        </ul>
                    </div>

                    <button class="search-button" type="submit">НАЙТИ</button>
                </form>
            </div>
            <div class="sort-container">
                <span class="sort-label">Сортировать:</span>
                <form action="{{ $currentRoute }}" method="GET">
                    <input type="hidden" name="search" value="{{ old('search', $searchTerm) }}">
                    <!-- скрытый input для поиска -->
                    <select class="sort-select" name="sort_by" onchange="this.form.submit()">
                        <option value="">Сортировать</option>
                        <option value="price_asc" {{ $sortBy == 'price_asc' ? 'selected' : '' }}>По цене (по
                            возрастанию)
                        </option>
                        <option value="price_desc" {{ $sortBy == 'price_desc' ? 'selected' : '' }}>По цене (по
                            убыванию)
                        </option>
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
                    @if($product->created_at->isCurrentWeek())
                        <span class="new-badge">NEW</span>
                    @endif
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
@section('scripts')
    <script>
        function setRoute(route, label) {
            const form = document.getElementById('searchForm');
            form.setAttribute('action', route);
            document.getElementById('searchScope').textContent = label;
        }
    </script>
@endsection
