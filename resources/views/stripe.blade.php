@extends('maindesign')

@section('title', 'Secure Checkout — Giftos')

@section('content')
<section class="page-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Secure checkout</span>
                <h1>Complete your order</h1>
                <p>Enter your delivery and card information. Your order is created only after payment succeeds.</p>
            </div>
        </div>

        <form action="{{ route('checkout.payment') }}"
              method="POST"
              class="require-validation"
              data-cc-on-file="false"
              data-stripe-publishable-key="{{ config('services.stripe.key') }}"
              id="payment-form">
            @csrf

            <div class="checkout-layout">
                <div class="checkout-forms">
                    <section class="form-card">
                        <div class="form-card-heading">
                            <span class="form-step">1</span>
                            <div>
                                <h2>Delivery information</h2>
                                <p>Where should we send your order?</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group full required">
                                <label for="receiver_name">Receiver name</label>
                                <input class="form-control" id="receiver_name" name="receiver_name" type="text" value="{{ old('receiver_name', Auth::user()->name ?? '') }}" placeholder="Full name" required>
                            </div>
                            <div class="form-group required">
                                <label for="receiver_phone">Phone number</label>
                                <input class="form-control" id="receiver_phone" name="receiver_phone" type="tel" value="{{ old('receiver_phone') }}" placeholder="e.g. +62 812 3456 7890" required>
                            </div>
                            <div class="form-group full required">
                                <label for="receiver_address">Complete address</label>
                                <textarea class="form-control" id="receiver_address" name="receiver_address" placeholder="Street, building, city, postal code" required>{{ old('receiver_address') }}</textarea>
                            </div>
                        </div>
                    </section>

                    <section class="form-card">
                        <div class="form-card-heading">
                            <span class="form-step">2</span>
                            <div>
                                <h2>Payment details</h2>
                                <p>Use a valid Stripe test or payment card.</p>
                            </div>
                            <span class="secure-label">
                                <svg viewBox="0 0 24 24"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                                Secure
                            </span>
                        </div>

                        <div class="card-fields">
                            <div class="form-group card-number-group required">
                                <label for="card_name">Name on card</label>
                                <input class="form-control" id="card_name" type="text" autocomplete="cc-name" placeholder="Name shown on card" required>
                            </div>
                            <div class="form-group card-number-group card required">
                                <label for="card_number">Card number</label>
                                <input class="form-control card-number" id="card_number" type="text" inputmode="numeric" autocomplete="cc-number" placeholder="1234 1234 1234 1234" required>
                            </div>
                            <div class="form-group expiration required">
                                <label for="card_month">Expiry month</label>
                                <input class="form-control card-expiry-month" id="card_month" type="text" inputmode="numeric" autocomplete="cc-exp-month" placeholder="MM" maxlength="2" required>
                            </div>
                            <div class="form-group expiration required">
                                <label for="card_year">Expiry year</label>
                                <input class="form-control card-expiry-year" id="card_year" type="text" inputmode="numeric" autocomplete="cc-exp-year" placeholder="YYYY" maxlength="4" required>
                            </div>
                            <div class="form-group cvc required">
                                <label for="card_cvc">CVC</label>
                                <input class="form-control card-cvc" id="card_cvc" type="text" inputmode="numeric" autocomplete="cc-csc" placeholder="123" maxlength="4" required>
                            </div>
                        </div>

                        <div class="payment-error error" role="alert">Please check your card information and try again.</div>
                        <button class="button button-dark button-wide payment-button" type="submit" id="pay-button">
                            Pay ${{ number_format($total, 2, '.', ',') }} securely
                        </button>
                    </section>
                </div>

                <aside class="summary-card">
                    <h2>Your order</h2>
                    @foreach($cart as $cartProduct)
                        @if($cartProduct->product)
                            @php
                                $quantity = (int) ($cartProduct->quantity ?? 1);
                                $lineTotal = $cartProduct->product->product_prices * $quantity;
                            @endphp
                            <div class="checkout-item">
                                <div class="checkout-item-image">
                                    <img src="/products/{{ $cartProduct->product->product_image }}" alt="{{ $cartProduct->product->product_title }}">
                                </div>
                                <div>
                                    <h4>{{ $cartProduct->product->product_title }}</h4>
                                    <span>Quantity {{ $quantity }}</span>
                                </div>
                                <span class="checkout-item-price">${{ number_format($lineTotal, 2, '.', ',') }}</span>
                            </div>
                        @endif
                    @endforeach
                    <div class="summary-row summary-total"><span>Total</span><strong>${{ number_format($total, 2, '.', ',') }}</strong></div>
                    <div class="summary-note">
                        <svg viewBox="0 0 24 24"><path d="M12 3 5 6v5c0 4.6 2.9 8.1 7 10 4.1-1.9 7-5.4 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></svg>
                        <span>Stock is reduced and the order appears in My Orders only after Stripe confirms payment.</span>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v2/"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('payment-form');
    if (!form || typeof Stripe === 'undefined') return;

    const errorBox = form.querySelector('.payment-error');
    const payButton = document.getElementById('pay-button');
    let submitting = false;

    form.addEventListener('submit', function (event) {
        if (submitting) return;
        event.preventDefault();

        errorBox.classList.remove('visible');
        errorBox.textContent = '';
        payButton.disabled = true;
        payButton.textContent = 'Processing payment...';

        Stripe.setPublishableKey(form.dataset.stripePublishableKey);
        Stripe.createToken({
            number: form.querySelector('.card-number').value,
            cvc: form.querySelector('.card-cvc').value,
            exp_month: form.querySelector('.card-expiry-month').value,
            exp_year: form.querySelector('.card-expiry-year').value
        }, function (status, response) {
            if (response.error) {
                errorBox.textContent = response.error.message;
                errorBox.classList.add('visible');
                payButton.disabled = false;
                payButton.textContent = 'Pay ${{ number_format($total, 2, '.', ',') }} securely';
                return;
            }

            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = 'stripeToken';
            tokenInput.value = response.id;
            form.appendChild(tokenInput);
            submitting = true;
            form.submit();
        });
    });
});
</script>
@endpush
