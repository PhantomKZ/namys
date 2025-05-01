<div class="payment-modal" id="paymentModal">
    <div class="payment-form">
        <span class="close-payment" onclick="closePaymentForm()">&times;</span>
        <h2 class="payment-title">Оплата заказа</h2>

        <div class="payment-methods">
            <div class="payment-method" onclick="selectPaymentMethod(this)">
                <img src="{{ asset('images/payment/visaandmastercard.png') }}" alt="Visa">
                <div>Банковская карта</div>
            </div>
            <div class="payment-method" onclick="selectPaymentMethod(this)">
                <img src="{{ asset('images/payment/kaspi.png') }}" alt="Kaspi">
                <div>Kaspi</div>
            </div>
        </div>

        <form id="paymentForm">
            <div class="card-payment">
                <input type="text" class="payment-input" placeholder="Номер карты" required>
                <div class="payment-row">
                    <input type="text" class="payment-input" placeholder="Срок действия" required>
                    <input type="text" class="payment-input" placeholder="CVV" required>
                </div>
                <input type="text" class="payment-input" placeholder="Имя держателя карты" required>
                <button type="submit" class="pay-button card-pay-button">Оплатить</button>
            </div>
            <div class="qr-code">
                <img src="{{ asset('/images/payment/kaspiqr.png') }}" alt="QR-код Kaspi">
                <p>Отсканируйте QR-код для оплаты через Kaspi</p>
            </div>
            <div class="payment-error">Пожалуйста, заполните все поля</div>
        </form>
    </div>
</div>
