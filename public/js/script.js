/* Анимация карточек "Новая коллекция Drop's" */
document.querySelectorAll('.product-card img').forEach((img) => {
    const originalSrc = img.src;
    const hoverSrc = img.getAttribute('data-hover');

    img.addEventListener('mouseenter', () => {
        img.src = hoverSrc;
    });

    img.addEventListener('mouseleave', () => {
        img.src = originalSrc;
    });
});



document.querySelectorAll('.glow-button').forEach((button) => {

    const light = document.createElement('div');
    light.classList.add('light');
    button.appendChild(light);

    button.addEventListener('mouseenter', (e) => {
        light.style.opacity = '1';
    });

    button.addEventListener('mousemove', (e) => {
        const rect = button.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        light.style.left = `${x}px`;
        light.style.top = `${y}px`;
    });

    button.addEventListener('mouseleave', () => {
        light.style.opacity = '0';
    });
});
// Функционал для карусели изображений товара
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('productCarousel');
    if (carousel) {
        const thumbnails = document.querySelectorAll('.thumbnail-button');

        carousel.addEventListener('slide.bs.carousel', function(event) {
            // Удаляем класс active у всех миниатюр
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            // Добавляем класс active текущей миниатюре
            thumbnails[event.to].classList.add('active');
        });

        // Обработчик клика по миниатюрам
        thumbnails.forEach((thumb, index) => {
            thumb.addEventListener('click', () => {
                thumbnails.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            });
        });
    }
});

// Функционал корзины
document.addEventListener('DOMContentLoaded', function() {
    // Находим все элементы управления количеством
    const quantityControls = document.querySelectorAll('.quantity-controls');
    const removeButtons = document.querySelectorAll('.remove-item');
    const basketItems = document.querySelector('.basket-items');
    const emptyBasket = document.querySelector('.empty-basket');
    const basketContent = document.querySelector('.basket-content');

    // Обработка изменения количества товаров
    quantityControls.forEach(control => {
        const minusBtn = control.querySelector('.minus');
        const plusBtn = control.querySelector('.plus');
        const input = control.querySelector('.quantity-input');

        minusBtn.addEventListener('click', () => {
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateTotalPrice();
            }
        });

        plusBtn.addEventListener('click', () => {
            let value = parseInt(input.value);
            input.value = value + 1;
            updateTotalPrice();
        });

        input.addEventListener('change', () => {
            let value = parseInt(input.value);
            if (value < 1) input.value = 1;
            updateTotalPrice();
        });
    });

    // Обработка удаления товаров
    removeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const item = button.closest('.basket-item');
            item.remove();
            updateTotalPrice();
            checkEmptyBasket();
        });
    });

    // Обновление общей стоимости
    function updateTotalPrice() {
        let total = 0;
        const items = document.querySelectorAll('.basket-item');

        items.forEach(item => {
            const price = parseInt(item.querySelector('.price').textContent.replace(/[^\d]/g, ''));
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            total += price * quantity;
        });

        // Обновляем отображение общей стоимости
        document.querySelector('.summary-row:first-child span:last-child').textContent = total.toLocaleString() + '₸';
        document.querySelector('.summary-row.total span:last-child').textContent = total.toLocaleString() + '₸';
        document.querySelector('.summary-row:first-child span:first-child').textContent = `Товары (${items.length})`;
    }

    // Проверка на пустую корзину
    function checkEmptyBasket() {
        const items = document.querySelectorAll('.basket-item');
        if (items.length === 0) {
            basketContent.style.display = 'none';
            emptyBasket.style.display = 'block';
        }
    }

    // Обработка промокода
    const promoForm = document.querySelector('.promo-code');
    const promoInput = promoForm.querySelector('input');
    const promoButton = promoForm.querySelector('button');

    promoButton.addEventListener('click', () => {
        const promoCode = promoInput.value.trim().toUpperCase();
        if (promoCode === 'NAMYS2025') {
            alert('Промокод применен! Скидка 10%');
            applyDiscount(10);
        } else {
            alert('Неверный промокод');
        }
    });

    // Применение скидки
    function applyDiscount(percentage) {
        const total = parseInt(document.querySelector('.summary-row.total span:last-child').textContent.replace(/[^\d]/g, ''));
        const discountAmount = total * (percentage / 100);
        const newTotal = total - discountAmount;

        // Добавляем строку со скидкой
        const summaryDetails = document.querySelector('.summary-details');
        const discountRow = document.createElement('div');
        discountRow.className = 'summary-row';
        discountRow.innerHTML = `
            <span>Скидка ${percentage}%</span>
            <span>-${discountAmount.toLocaleString()}₸</span>
        `;

        // Вставляем перед итоговой строкой
        const totalRow = document.querySelector('.summary-row.total');
        summaryDetails.insertBefore(discountRow, totalRow);

        // Обновляем итоговую сумму
        totalRow.querySelector('span:last-child').textContent = newTotal.toLocaleString() + '₸';

        // Отключаем поле ввода промокода
        promoInput.disabled = true;
        promoButton.disabled = true;
    }

    // Обработка оформления заказа
    const checkoutBtn = document.querySelector('.checkout-btn');
    checkoutBtn.addEventListener('click', () => {
        alert('Спасибо за заказ! Мы свяжемся с вами в ближайшее время.');
    });
});
