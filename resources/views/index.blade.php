@extends('layouts.app')
@section('content')
    <div class="hero-section">
        <div class="hero-text">
            <h1>NEW DROP</h1>
            <p>C O L L E C T I O N 2025</p>
            <button class="glow-button">Перейти</button>
        </div>
    </div>
    <div class="container-fluid">
        <section class="categories mt-4">
            <h2>Категории одежды</h2>
            <p>Минимализм, уникальность, качество и исключительно натуральные материалы</p>
            <div class="category-grid">
                @foreach($categories as $category)
                    <a>
                        <div class="category-card">
                            <div class="category-image">
                                <img src="{{ asset('storage/' . $category->thumbnail) }}" alt="{{ $category->name }} | Namys">
                                <div class="category-overlay">
                                    <h3>{{ $category->name }}</h3>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Новая коллекция Drop's -->
        <section class="new-drop">
            <h2>NEW! NEW! NEW!</h2>
            <h3>DROP'S</h3>
            <div class="product-grid">
                <a href="product/product1.html" class="product-card">
                    <div class="image-container">
                        <img src="/images/main/item1.jpeg" alt="Продукт 1" class="main-image">
                        <img src="/images/main/item1-hover.jpeg" alt="Продукт 1" class="hover-image">
                    </div>
                    <p>Футболка AQ Namys</p>
                    <p class="price">7990₸</p>
                    <button>Добавить в корзину</button>
                </a>
                <a href="product/product2.html" class="product-card">
                    <div class="image-container">
                        <img src="/images/main/item2.jpeg" alt="Продукт 2" class="main-image">
                        <img src="/images/main/item2-hover.jpeg" alt="Продукт 2" class="hover-image">
                    </div>
                    <p>Свитшот QARA Namys</p>
                    <p class="price">10 990₸</p>
                    <button>Добавить в корзину</button>
                </a>
                <a href="product/product3.html" class="product-card">
                    <div class="image-container">
                        <img src="/images/main/item3.jpeg" alt="Продукт 3" class="main-image">
                        <img src="/images/main/item3-hover.jpeg" alt="Продукт 3" class="hover-image">
                    </div>
                    <p>Сумка шопер AQ Namys</p>
                    <p class="price">3350₸</p>
                    <button>Добавить в корзину</button>
                </a>
                <a href="product/product4.html" class="product-card">
                    <div class="image-container">
                        <img src="/images/main/item4.jpeg" alt="Продукт 4" class="main-image">
                        <img src="/images/main/item4-hover.jpeg" alt="Продукт 4" class="hover-image">
                    </div>
                    <p>Кепка AQ Namys</p>
                    <p class="price">2350₸</p>
                    <button>Добавить в корзину</button>
                </a>
                <a href="product/product5.html" class="product-card">
                    <div class="image-container">
                        <img src="/images/main/item5.jpg" alt="Продукт 5" class="main-image">
                        <img src="/images/main/item5-hover.jpg" alt="Продукт 5" class="hover-image">
                    </div>
                    <p>Футболка KIIKII с принтом</p>
                    <p class="price">6890₸</p>
                    <button>Добавить в корзину</button>
                </a>
                <a href="product/product6.html" class="product-card">
                    <div class="image-container">
                        <img src="/images/main/item6.jpg" alt="Продукт 6" class="main-image">
                        <img src="/images/main/item6-hover.jpg" alt="Продукт 6" class="hover-image">
                    </div>
                    <p>Худи Cyber Art</p>
                    <p class="price">9990₸</p>
                    <button>Добавить в корзину</button>
                </a>
                <a href="product/product7.html" class="product-card">
                    <div class="image-container">
                        <img src="/images/main/item7.jpg" alt="Продукт 7" class="main-image">
                        <img src="/images/main/item7-hover.jpg" alt="Продукт 7" class="hover-image">
                    </div>
                    <p>Футболка KIIKII Amigo SKULL</p>
                    <p class="price">6290₸</p>
                    <button>Добавить в корзину</button>
                </a>
                <a href="product/product8.html" class="product-card">
                    <div class="image-container">
                        <img src="/images/main/item8.jpg" alt="Продукт 8" class="main-image">
                        <img src="/images/main/item8-hover.jpg" alt="Продукт 8" class="hover-image">
                    </div>
                    <p>Свитшот SAMURAI 2 0 7 7</p>
                    <p class="price">8950₸</p>
                    <button>Добавить в корзину</button>
                </a>
            </div>
        </section>

        <!-- О нас -->
        <section class="about-us">
            <div class="about-content row">
                <div class="col-md-6">
                    <div class="about-text">
                        <h2>О нас</h2>
                        <p>Мы любим городскую моду и обожаем создавать одежду. Находимся в Павлодаре. Наш бренд NAMYS сфокусирован на тренд, надежность и качество. Доступна доставка по городу.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-image">
                        <img src="/images/main/aboutme.gif" alt="О нас">
                    </div>
                </div>
            </div>
        </section>

        <!-- Обратная связь -->
        <section class="feedback">
            <h2>УЗНАВАЙТЕ О НОВИНКАХ ПЕРВЫМИ!</h2>
            <h3>При первой покупке выдается промокод на 20%!</h3>
            <p>Один раз в месяц мы будем присылать вам информацию о наших последних коллекциях, скидках и акциях. Обещаем быть полезными!</p>
            <form class="feedback-form" action="{{ route('subscribe') }}" method="POST" id="subscribe-form">
                @csrf
                <input type="email" name="email" id="email" placeholder="Ваш E-mail" required>
                <button type="submit">Подписаться</button>
            </form>
        </section>
        @if(session('success'))
            <div id="success-message" class="popup-message">
                <span>{{ session('success') }}</span>
                <button onclick="closePopup()" class="close-btn">&times;</button>
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        $('#subscribe-form').submit(function (e) {
            e.preventDefault(); // Останавливаем стандартную отправку формы

            var email = $('#email').val(); // Получаем email из поля ввода

            $.ajax({
                url: '{{ route('subscribe') }}',
                type: 'POST',
                data: {
                    email: email,
                    _token: $('input[name="_token"]').val(), // CSRF токен
                },
                success: function (response) {
                    if (response.success) {
                        // Показать всплывающее сообщение
                        $('#message-text').text(response.success);
                        $('#success-message').show();

                        // Очистить поле ввода после отправки
                        $('#email').val('');
                    }
                },
                error: function () {
                    alert('Произошла ошибка при подписке. Попробуйте позже.');
                }
            });
        });

        function closePopup() {
            $('#success-message').hide();
        }

        setTimeout(function() {
            var message = document.getElementById('success-message');
            if (message) {
                message.style.display = 'none';
            }
        }, 5000);
    </script>
@endsection

