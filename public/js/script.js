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


/* Анимация кнопки "Перейти" */
document.querySelectorAll('.glow-button').forEach((button) => {
    // Создаем элемент света и добавляем его в кнопку
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
