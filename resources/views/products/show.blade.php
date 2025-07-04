@extends('layouts.app')
@section('content')
    <div class="product-page">
        <div class="container-fluid">
            <div class="breadcrumb-nav">
                <a href="{{ route('catalog.index') }}">{{ __('messages.catalog') }}</a>
                /
                <a href="{{ route('catalog.index', ['type_id' => $product->type_id]) }}">
                    {{ $product->type }}
                </a>
            </div>

            <div id="imageOverlay" class="image-overlay">
                <button class="close-btn" aria-label="{{ __('messages.close') }}">&times;</button>

                <button class="nav-btn prev-btn" aria-label="{{ __('messages.previous') }}">&lsaquo;</button>
                <img id="overlayImg" class="overlay-img" src="" alt="">
                <button class="nav-btn next-btn" aria-label="{{ __('messages.next') }}">&rsaquo;</button>

                <div id="overlayThumbs" class="overlay-thumbs"></div>
            </div>


            <div id="infoOverlay" class="image-overlay">
                <button class="close-btn" aria-label="{{ __('messages.close') }}">&times;</button>
                <button class="nav-btn prev-info" aria-label="{{ __('messages.previous') }}">&lsaquo;</button>
                <img id="infoImg" class="overlay-img" src="" alt="">
                <button class="nav-btn next-info" aria-label="{{ __('messages.next') }}">&rsaquo;</button>
            </div>


            <div class="product-details">
                <div class="product-gallery">
                    <div id="productCarousel" class="carousel slide" data-bs-interval="false">
                        <div class="carousel-inner">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset($image->path) }}" alt="{{ $product->name }}" class="main-image" style="height: 600px" >
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">{{ __('messages.previous') }}</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">{{ __('messages.next') }}</span>
                        </button>
                    </div>
                    <div class="product-thumbnails">
                        @foreach($product->images as $index => $image)
                            <button type="button"
                                    class="thumbnail-button {{ $index === 0 ? 'active' : '' }}"
                                    data-bs-target="#productCarousel"
                                    data-bs-slide-to="{{ $index }}">
                                <img src="{{ asset($image->path) }}" alt="{{ __('messages.thumbnail') }} {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="product-info">
                    <h1 class="product-title">
                        {{ $product->title }}
                    </h1>
                    <div class="product-price">{{ $product->formattedPrice }}</div>

                    @if(isset($variants) && $variants->count() > 1)
                        <div class="color-picker mb-3 d-flex align-items-center">
                            <span class="me-2 fw-bold">{{ __('messages.color') }}:</span>
                            @foreach($variants as $item)
                                @php
                                    $thumb = optional($item->images->first())->path
                                             ?? '/images/noimage.jpg';
                                    $colorName = optional($item->color)->name ?? 'цвет';
                                @endphp

                                <a href="{{ route('product.show', $item->id) }}"
                                   class="color-swatch {{ $item->id == $product->id ? 'active' : '' }}"
                                   style="background-image:url('{{ asset($thumb) }}')"
                                   title="{{ $colorName }}">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="product-description">
                        <p>{{ $product->description }}</p>
                        <ul class="features-list">
                            <li><strong>{{ __('messages.brand') }}:</strong> {{ $product->brand }}</li>
                            <li>
                                <strong>{{ __('messages.material') }}:</strong>
                                {{ $product->materials->pluck('name')->join(', ') }}
                            </li>

                            <li><strong>{{ __('messages.color') }}:</strong> {{ $product->color }}</li>
                        </ul>
                    </div>

                    @php
                        $isInactive = $product->sizes->sum('available_quantity') <= 0;
                    @endphp
                    @if($isInactive)
                        <div class="alert alert-danger" style="font-size: 1.2rem; margin: 30px 0;">{{ __('messages.sold_out') }}</div>
                    @else
                        <div class="size-selector">
                            <select name="size_id" id="size_id" class="form-control" required>
                                <option value="">{{ __('messages.choose_size') }}</option>
                                @foreach($product->sizes as $size)
                                    @if(($size->available_quantity ?? 0) > 0)
                                        @php
                                            $isSelected = old('size_id') == $size->id;
                                            $inCart = $cartItemsBySize instanceof \Illuminate\Support\Collection
                                                ? $cartItemsBySize->has($size->id)
                                                : array_key_exists($size->id, $cartItemsBySize);
                                        @endphp
                                        <option value="{{ $size->id }}"
                                                data-in-cart="{{ $inCart ? '1' : '0' }}"
                                            {{ $isSelected ? 'selected' : '' }}>
                                            {{ $size->name }} ({{ $size->available_quantity }} {{ __('messages.in_stock') }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="size-help">
                        <a href="#"
                           class="info-trigger"
                           data-imgs='["{{ asset('images/catalog/size_chart1.png') }}",
                                       "{{ asset('images/catalog/size_chart2.png') }}"]'>
                            {{ __('messages.size_help') }}
                        </a>

                        <a href="#"
                           class="info-trigger"
                           data-img="{{ asset('images/catalog/delivery_info.png') }}">
                            {{ __('messages.about_delivery') }}
                        </a>
                    </div>



                    <form action="{{ route('cart.add') }}" method="POST" class="w-100" id="add-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size_id" id="add-size-id">
                        @error('size_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn add-to-cart-btn btn-block">{{ __('messages.add_to_cart') }}</button>
                    </form>

                    <form action="{{ route('cart.remove') }}" method="POST" class="w-100 d-none" id="remove-form">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="size_id" id="remove-size-id">
                        <button type="submit" class="btn add-to-cart-btn btn-block">{{ __('messages.remove_from_cart') }}</button>
                    </form>
                    @auth
                        @if(auth()->user()->favorites->contains($product->id))
                            <form action="{{ route('product.removeFromFavorites', $product->id) }}" method="POST">
                                @csrf
                                <button class="d-flex ms-auto mt-2 btn add-to-favorites-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="white"
                                              d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    <span class="text-nowrap"> {{ __('messages.remove_from_favorites') }} </span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('product.addToFavorites', $product->id) }}" method="POST">
                                @csrf
                                <button class="d-flex ms-auto mt-2 btn add-to-favorites-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                        <path fill="white"
                                              d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                    <span class="text-nowrap"> {{ __('messages.add_to_favorites') }} </span>
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="recommendations">
        <div class="container">
            <h2 class="section-title">{{ __('messages.might_like') }}</h2>
            <div class="recommendations-container">
                <div class="recommendations-grid">
                    @foreach($recommendations as $item)
                        <a href="{{ route('product.show', $item->id) }}" class="catalog-product-card">
                            <div class="product-image-container">
                                <img src="{{ $item->mainImage ? asset($item->mainImage) : '/images/default_main_image.jpg' }}" alt="{{ $item->name }}" class="product-image">
                            </div>
                            <h3 class="product-title">{{ $item->title }}</h3>
                            <p class="product-price">{{ $item->formattedPrice }}</p>
                            <button class="add-to-cart">{{ __('messages.add_to_cart') }}</button>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const sizeSelect = document.getElementById('size_id');
        const addForm = document.getElementById('add-form');
        const removeForm = document.getElementById('remove-form');
        const addSizeInput = document.getElementById('add-size-id');
        const removeSizeInput = document.getElementById('remove-size-id');

        sizeSelect.addEventListener('change', function () {
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            const sizeId = selectedOption.value;
            const inCart = selectedOption.dataset.inCart === '1';

            addSizeInput.value = sizeId;
            removeSizeInput.value = sizeId;

            if (sizeId === "") {
                addForm.classList.remove('d-none');
                removeForm.classList.add('d-none');
                return;
            }

            if (inCart) {
                addForm.classList.add('d-none');
                removeForm.classList.remove('d-none');
            } else {
                addForm.classList.remove('d-none');
                removeForm.classList.add('d-none');
            }

        });
        document.addEventListener('DOMContentLoaded', function () {
            if (sizeSelect.value !== "") {
                const event = new Event('change');
                sizeSelect.dispatchEvent(event);
            }
        });

        /* Просмотр изображении в полном размере */
        document.addEventListener('DOMContentLoaded', () => {

            const galleryImages = Array.from(
                document.querySelectorAll('.product-gallery .main-image')
            ).map(img => img.src);

            const overlay       = document.getElementById('imageOverlay');
            const overlayImg    = document.getElementById('overlayImg');
            const thumbsWrapper = document.getElementById('overlayThumbs');
            const btnPrev       = overlay.querySelector('.prev-btn');
            const btnNext       = overlay.querySelector('.next-btn');
            const btnClose      = overlay.querySelector('.close-btn');
            let currentIndex    = 0;

            let isZoomed = false;

            overlayImg.addEventListener('click', () => {
                if (isZoomed) resetZoom();
                else applyZoom();
            });

            overlayImg.addEventListener('mousemove', e => {
                if (!isZoomed) return;

                const rect = overlayImg.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width)  * 100;
                const y = ((e.clientY - rect.top)  / rect.height) * 100;
                overlayImg.style.transformOrigin = `${x}% ${y}%`;
            });

            function applyZoom(){
                isZoomed = true;
                overlayImg.classList.add('zoomed');
            }

            function resetZoom(){
                isZoomed = false;
                overlayImg.classList.remove('zoomed');
                overlayImg.style.transformOrigin = 'center center';
            }

            function showSlide(i){
                resetZoom();
            }
            function closeOverlay(){
                resetZoom();
            }

            galleryImages.forEach((src, idx) => {
                const t = document.createElement('img');
                t.src = src;
                t.dataset.index = idx;
                thumbsWrapper.appendChild(t);
            });

            function showSlide(i) {
                currentIndex = (i + galleryImages.length) % galleryImages.length;
                overlayImg.src = galleryImages[currentIndex];


                thumbsWrapper.querySelectorAll('img').forEach(img =>
                    img.classList.toggle('active', Number(img.dataset.index) === currentIndex)
                );
            }

            function openOverlay(startIdx) {
                overlay.classList.add('open');
                showSlide(startIdx);
                document.body.style.overflow = 'hidden';
            }

            function closeOverlay() {
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            }

            overlay.addEventListener('click', e => {
                if (e.target === overlay) closeOverlay();
            });

            btnPrev.onclick  = () => showSlide(currentIndex - 1);
            btnNext.onclick  = () => showSlide(currentIndex + 1);
            btnClose.onclick = closeOverlay;

            thumbsWrapper.addEventListener('click', e => {
                if (e.target.tagName === 'IMG') showSlide(Number(e.target.dataset.index));
            });

            document.addEventListener('keydown', e => {
                if (!overlay.classList.contains('open')) return;

                if (e.key === 'Escape')       closeOverlay();
                if (e.key === 'ArrowLeft')    showSlide(currentIndex - 1);
                if (e.key === 'ArrowRight')   showSlide(currentIndex + 1);
            });

            document.querySelectorAll('.product-gallery .carousel-inner .main-image')
                .forEach((img, idx) => {
                    img.style.cursor = 'zoom-in';
                    img.addEventListener('click', () => openOverlay(idx));
                });



            /* справочная информация */
            const infoOverlay = document.getElementById('infoOverlay');
            const infoImg     = document.getElementById('infoImg');
            const btnInfoPrev = infoOverlay.querySelector('.prev-info');
            const btnInfoNext = infoOverlay.querySelector('.next-info');
            const infoClose   = infoOverlay.querySelector('.close-btn');

            let infoImages = [];
            let infoIdx    = 0;

            function showInfo(i){
                if(!infoImages.length) return;
                infoIdx = (i + infoImages.length) % infoImages.length;
                infoImg.src = infoImages[infoIdx];

                const showArrows = infoImages.length > 1;
                btnInfoPrev.style.display = btnInfoNext.style.display =
                    showArrows ? 'flex' : 'none';
            }

            function openInfoOverlay(arr){
                infoImages = arr;
                infoOverlay.classList.add('open');
                document.body.style.overflow = 'hidden';
                showInfo(0);
            }

            function closeInfoOverlay(){
                infoOverlay.classList.remove('open');
                document.body.style.overflow = '';
            }

            btnInfoPrev.onclick = () => showInfo(infoIdx - 1);
            btnInfoNext.onclick = () => showInfo(infoIdx + 1);
            infoClose.onclick   = closeInfoOverlay;
            infoOverlay.addEventListener('click',e=>{
                if(e.target===infoOverlay) closeInfoOverlay();
            });
            document.addEventListener('keydown',e=>{
                if(!infoOverlay.classList.contains('open')) return;
                if(e.key==='Escape') closeInfoOverlay();
                if(e.key==='ArrowLeft')  showInfo(infoIdx-1);
                if(e.key==='ArrowRight') showInfo(infoIdx+1);
            });

            document.querySelectorAll('.info-trigger').forEach(link=>{
                link.addEventListener('click',e=>{
                    e.preventDefault();

                    if(link.dataset.imgs){
                        openInfoOverlay(JSON.parse(link.dataset.imgs));
                    }else if(link.dataset.img){
                        openInfoOverlay([link.dataset.img]);
                    }
                });
            });
        });
    </script>
@endsection
