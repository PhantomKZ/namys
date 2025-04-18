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
