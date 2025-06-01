@extends('layouts.app')
@section('content')
    <div class="product-page">
        <div class="container-fluid">
            <div class="breadcrumb-nav">
                <a href="{{ route('collection.index') }}">{{ __('messages.look_collection') }}</a> / <span>{{ $collection->name }}</span>
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
                            @foreach($collection->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset($image->path) }}" alt="{{ $collection->name }}" class="main-image">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">{{ __('messages.previous') }}</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">{{ __('messages.next') }}</span>
                        </button>
                    </div>
                    <div class="product-thumbnails">
                        @foreach($collection->images as $index => $image)
                            <button type="button" class="thumbnail-button active" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}">
                                <img src="{{ asset($image->path) }}" alt="{{ __('messages.thumbnail') }} {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="product-info">
                    <h1 class="product-title">{{ __('messages.kit') }} "{{ $collection->name }}"</h1>
                    <div class="product-price">{{ $collection->formattedPrice }}₸</div>

                    <div class="product-description">
                        <p>{{ $collection->description }}</p>
                        <ul class="features-list">
                            @foreach($products as $item)
                                <li>- {{ $item->type }} {{ $item->name }} ({{ $item->color }}) - {{ $item->formattedPrice }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <form action="{{ route('cart.addAll') }}" method="POST" id="add-all-form">
                        @csrf
                        @foreach($products as $product)
                            <div class="mb-3">
                                <label for="size_{{ $product->id }}">{{ __('messages.size_for') }} {{ $product->type }} {{ $product->name }}:</label>
                                <select name="sizes[{{ $product->id }}]" id="size_{{ $product->id }}" class="form-control" required>
                                    <option value="">{{ __('messages.choose_size') }}</option>
                                    @foreach($product->sizes as $size)
                                        @if($size->pivot->quantity > 0)
                                            <option value="{{ $size->id }}">{{ $size->name }} ({{ $size->pivot->quantity }} {{ __('messages.in_stock') }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                        @endforeach
                        @if ($errors->has('sizes'))
                            <div class="alert alert-danger">{{ $errors->first('messages.choose_size_for_all') }}</div>
                        @endif
                        <div class="size-help" style="margin-bottom: 20px;">
                            <a href="#"
                               class="info-trigger"
                               data-imgs='["{{ asset('images/look/size_chart1.png') }}",
                                           "{{ asset('images/look/size_chart2.png') }}"]'>
                                {{ __('messages.size_help') }}
                            </a>

                            <a href="#"
                               class="info-trigger"
                               data-img="{{ asset('images/look/delivery_info.png') }}">
                                {{ __('messages.about_delivery') }}
                            </a>
                        </div>
                        <button type="submit" class="add-to-cart-btn">{{ __('messages.buy_whole_look') }}</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="recommendations">
        <div class="container">
            <h2 class="section-title">{{ __('messages.separate_items_from_kit') }}</h2>
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
        document.addEventListener('DOMContentLoaded', () => {

            const galleryImages = Array.from(
                document.querySelectorAll('.product-gallery .carousel-inner img')
            ).map(img => img.src);

            const overlay       = document.getElementById('imageOverlay');
            const overlayImg    = document.getElementById('overlayImg');
            const thumbsWrapper = document.getElementById('overlayThumbs');
            const btnPrev       = overlay.querySelector('.prev-btn');
            const btnNext       = overlay.querySelector('.next-btn');
            const btnClose      = overlay.querySelector('.close-btn');
            let currentIndex    = 0;
            let isZoomed        = false;

            galleryImages.forEach((src, idx) => {
                const t = document.createElement('img');
                t.src = src;
                t.dataset.index = idx;
                thumbsWrapper.appendChild(t);
            });

            function showSlide(i){
                currentIndex = (i + galleryImages.length) % galleryImages.length;
                overlayImg.src = galleryImages[currentIndex];
                overlayImg.classList.remove('zoomed');
                overlayImg.style.transformOrigin = 'center center';
                isZoomed = false;
                thumbsWrapper.querySelectorAll('img').forEach(img =>
                    img.classList.toggle('active', Number(img.dataset.index) === currentIndex)
                );
            }

            function openOverlay(idx){
                overlay.classList.add('open');
                showSlide(idx);
                document.body.style.overflow = 'hidden';
            }

            function closeOverlay(){
                overlay.classList.remove('open');
                document.body.style.overflow = '';
                overlayImg.classList.remove('zoomed');
                overlayImg.style.transformOrigin = 'center center';
                isZoomed = false;
            }

            overlayImg.addEventListener('click', () => {
                if(isZoomed){
                    overlayImg.classList.remove('zoomed');
                    overlayImg.style.transformOrigin = 'center center';
                    isZoomed = false;
                } else {
                    overlayImg.classList.add('zoomed');
                    isZoomed = true;
                }
            });
            overlayImg.addEventListener('mousemove', e => {
                if(!isZoomed) return;
                const r = overlayImg.getBoundingClientRect();
                const x = ((e.clientX - r.left)/r.width)*100;
                const y = ((e.clientY - r.top)/r.height)*100;
                overlayImg.style.transformOrigin = `${x}% ${y}%`;
            });

            btnPrev.onclick  = () => showSlide(currentIndex - 1);
            btnNext.onclick  = () => showSlide(currentIndex + 1);
            btnClose.onclick = closeOverlay;
            overlay.addEventListener('click', e => { if(e.target===overlay) closeOverlay(); });
            document.addEventListener('keydown', e => {
                if(!overlay.classList.contains('open')) return;
                if(e.key==='Escape') closeOverlay();
                if(e.key==='ArrowLeft')  showSlide(currentIndex - 1);
                if(e.key==='ArrowRight') showSlide(currentIndex + 1);
            });
            thumbsWrapper.addEventListener('click', e => {
                if(e.target.tagName==='IMG') showSlide(Number(e.target.dataset.index));
            });

            document.querySelectorAll('.product-gallery .carousel-inner img')
                .forEach((img, idx) => {
                    img.style.cursor = 'zoom-in';
                    img.addEventListener('click', () => openOverlay(idx));
                });

            const infoOverlay = document.getElementById('infoOverlay');
            const infoImg     = document.getElementById('infoImg');
            const btnInfoPrev = infoOverlay.querySelector('.prev-info');
            const btnInfoNext = infoOverlay.querySelector('.next-info');
            const infoClose   = infoOverlay.querySelector('.close-btn');
            let infoImages = [], infoIdx = 0;

            function showInfo(i){
                if(!infoImages.length) return;
                infoIdx = (i + infoImages.length) % infoImages.length;
                infoImg.src = infoImages[infoIdx];
                // прячем стрелки, если одна картинка
                const vis = infoImages.length>1 ? 'flex' : 'none';
                btnInfoPrev.style.display = btnInfoNext.style.display = vis;
            }

            function openInfo(arr){
                infoImages = arr;
                infoOverlay.classList.add('open');
                document.body.style.overflow = 'hidden';
                showInfo(0);
            }
            function closeInfo(){
                infoOverlay.classList.remove('open');
                document.body.style.overflow = '';
            }

            btnInfoPrev.onclick = () => showInfo(infoIdx - 1);
            btnInfoNext.onclick = () => showInfo(infoIdx + 1);
            infoClose.onclick   = closeInfo;
            infoOverlay.addEventListener('click', e => { if(e.target===infoOverlay) closeInfo(); });
            document.addEventListener('keydown', e => {
                if(!infoOverlay.classList.contains('open')) return;
                if(e.key==='Escape') closeInfo();
                if(e.key==='ArrowLeft') showInfo(infoIdx - 1);
                if(e.key==='ArrowRight') showInfo(infoIdx + 1);
            });

            document.querySelectorAll('.info-trigger').forEach(link =>{
                link.addEventListener('click', e =>{
                    e.preventDefault();
                    if(link.dataset.imgs){
                        openInfo(JSON.parse(link.dataset.imgs));
                    } else if(link.dataset.img){
                        openInfo([ link.dataset.img ]);
                    }
                });
            });

        });
    </script>
@endsection
