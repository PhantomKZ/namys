<div class="search-sort-section">
    <div class="container">
        <div class="search-container">
            @php
                $currentRoute = url()->current();
            @endphp
            @if(request()->filled('search'))
                <a href="{{ $currentRoute }}" class="btn btn-outline-primary">{{ __('messages.clear') }}</a>
            @endif
            <div class="search-box">
                <form method="GET" action="{{ $currentRoute }}" id="searchForm" class="d-flex">
                    <input type="text" name="search" placeholder="{{ __('messages.search') }}" class="search-input">

                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="searchScope">
                            {{ __('messages.catalog') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item" onclick="event.preventDefault(); setRoute('{{ route('catalog.index') }}', '{{ __('messages.across_site') }}');">{{ __('messages.across_site') }}</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setRoute('{{ $currentRoute }}', '{{ __('messages.by_catalog') }}')">{{ __('messages.by_catalog') }}</a>
                            </li>
                        </ul>
                    </div>

                    <button class="search-button" type="submit">{{ __('messages.find') }}</button>
                </form>
            </div>
            <div class="sort-container">
                <span class="sort-label">{{ __('messages.sort_by') }}:</span>
                <form action="{{ $currentRoute }}" method="GET">
                    <input type="hidden" name="search" value="{{ old('search', $searchTerm) }}">
                    <!-- скрытый input для поиска -->
                    <select class="sort-select" name="sort_by" onchange="this.form.submit()">
                        <option value="">{{ __('messages.sort') }}</option>
                        <option value="price_asc" {{ $sortBy == 'price_asc' ? 'selected' : '' }}>{{ __('messages.price_asc') }}
                        </option>
                        <option value="price_desc" {{ $sortBy == 'price_desc' ? 'selected' : '' }}>{{ __('messages.price_desc') }}
                        </option>
                        <option value="name_asc" {{ $sortBy == 'name_asc' ? 'selected' : '' }}>{{ __('messages.name_asc') }}</option>
                        <option value="name_desc" {{ $sortBy == 'name_desc' ? 'selected' : '' }}>{{ __('messages.name_desc') }}</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="catalog">
    @if(!empty($products) && $products->count())
        <div class="products-grid">
            @foreach($products as $product)
                @php
                    $isInactive = $product->sizes->sum('pivot.quantity') <= 0;
                @endphp
                <a href="{{ route('product.show', $product->id) }}" class="catalog-product-card{{ $isInactive ? ' inactive' : '' }}">
                    <div class="product-image-container">
                        @if ($product->created_at->diffInDays(now()) < 7)
                            <span class="new-badge">{{ __('messages.new') }}</span>
                        @endif
                        <img src="{{ asset($product->mainImage) }}" alt="{{ $product->title }}" class="product-image">
                    </div>
                    <h3 class="product-title">{{ $product->title }}</h3>
                    <p class="product-price">{{ $product->formattedPrice }}</p>
                    <button class="add-to-cart" {{ $isInactive ? 'disabled style=\'opacity:0.5;cursor:not-allowed;\'' : '' }}>{{ __('messages.add_to_cart') }}</button>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <p class="mb-3">{{ __('messages.no_products_found') }}</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary">{{ __('messages.go_to_catalog') }}</a>
        </div>
    @endif

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
