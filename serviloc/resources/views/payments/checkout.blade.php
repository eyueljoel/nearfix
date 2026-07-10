@extends('layouts.app')

@section('title', 'Confirm Payment')

@section('content')
<style>
    .checkout-container {
        max-width: 680px;
        margin: 0 auto;
    }

    .checkout-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    }

    .checkout-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        padding: 32px;
        color: white;
        text-align: center;
    }

    .checkout-header h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .checkout-header p {
        opacity: 0.7;
        font-size: 14px;
    }

    .checkout-body {
        padding: 32px;
    }

    .order-summary {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 28px;
        border: 1px solid #e8ecf1;
    }

    .order-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e8ecf1;
        font-size: 14px;
        color: #555;
    }

    .order-row:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 18px;
        color: #1a1a2e;
        padding-top: 16px;
        margin-top: 6px;
        border-top: 2px solid #e8ecf1;
    }

    .order-row .label { color: #8895aa; font-weight: 500; }

    .payment-methods {
        margin-bottom: 28px;
    }

    .payment-methods h4 {
        font-weight: 700;
        font-size: 15px;
        color: #1a1a2e;
        margin-bottom: 14px;
    }

    .method-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .method-option {
        position: relative;
    }

    .method-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
    }

    .method-label {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border: 2px solid #e8ecf1;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 14px;
        font-weight: 500;
        color: #555;
        background: white;
    }

    .method-label:hover {
        border-color: #e94560;
        background: #fff8f9;
    }

    .method-option input[type="radio"]:checked + .method-label {
        border-color: #e94560;
        background: #fff0f3;
        color: #e94560;
        font-weight: 600;
    }

    .method-icon { font-size: 24px; }

    .security-note {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f0fff4;
        border: 1px solid #c6f6d5;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 13px;
        color: #276749;
        margin-bottom: 24px;
    }

    .btn-pay {
        width: 100%;
        padding: 16px;
        background: #e94560;
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-pay:hover {
        background: #c73652;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(233, 69, 96, 0.35);
    }

    .btn-cancel {
        display: block;
        text-align: center;
        margin-top: 14px;
        color: #8895aa;
        font-size: 14px;
        text-decoration: none;
    }

    .btn-cancel:hover { color: #e94560; }

    .provider-info {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .provider-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }
</style>

<div class="checkout-container">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('customer.offers') }}" style="color: #8895aa; text-decoration: none; font-size: 14px;">
            ← Back to Offers
        </a>
    </div>

    <div class="checkout-card">
        <div class="checkout-header">
            <div style="font-size: 48px; margin-bottom: 12px;">💳</div>
            <h2>Confirm Payment</h2>
            <p>Secure payment to lock in your provider</p>
        </div>

        <div class="checkout-body">

            {{-- Provider info --}}
            <div class="provider-info">
                <div class="provider-avatar">
                    {{ strtoupper(substr($offer->provider->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight: 600; color: #1a1a2e;">{{ $offer->provider->name }}</div>
                    <div style="font-size: 13px; color: #8895aa;">Service Provider</div>
                </div>
            </div>

            {{-- Order summary --}}
            <div class="order-summary">
                <div class="order-row">
                    <span class="label">Service</span>
                    <span>{{ $serviceRequest->title }}</span>
                </div>
                <div class="order-row">
                    <span class="label">Provider</span>
                    <span>{{ $offer->provider->name }}</span>
                </div>
                <div class="order-row">
                    <span class="label">Offer Price</span>
                    <span>ETB {{ number_format($offer->price, 2) }}</span>
                </div>
                <div class="order-row">
                    <span class="label">Platform Fee</span>
                    <span style="color: #2e7d32;">Free</span>
                </div>
                <div class="order-row">
                    <span class="label">Total to Pay</span>
                    <span style="color: #e94560;">ETB {{ number_format($offer->price, 2) }}</span>
                </div>
            </div>

            {{-- Payment method --}}
            <form action="{{ route('payments.pay', $offer) }}" method="POST">
                @csrf

                <div class="payment-methods">
                    <h4>Select Payment Method</h4>
                    <div class="method-options">
                        <div class="method-option">
                            <input type="radio" name="payment_method" id="m_simulated" value="simulated" checked>
                            <label class="method-label" for="m_simulated">
                                <span class="method-icon">💵</span>
                                <div>
                                    <div>Cash on Delivery</div>
                                    <div style="font-size: 11px; opacity: 0.7;">Pay when done</div>
                                </div>
                            </label>
                        </div>
                        <div class="method-option">
                            <input type="radio" name="payment_method" id="m_telebirr" value="telebirr">
                            <label class="method-label" for="m_telebirr">
                                <span class="method-icon">📱</span>
                                <div>
                                    <div>TeleBirr</div>
                                    <div style="font-size: 11px; opacity: 0.7;">Mobile wallet</div>
                                </div>
                            </label>
                        </div>
                        <div class="method-option">
                            <input type="radio" name="payment_method" id="m_cbe" value="cbe_birr">
                            <label class="method-label" for="m_cbe">
                                <span class="method-icon">🏦</span>
                                <div>
                                    <div>CBE Birr</div>
                                    <div style="font-size: 11px; opacity: 0.7;">Bank transfer</div>
                                </div>
                            </label>
                        </div>
                        <div class="method-option">
                            <input type="radio" name="payment_method" id="m_bank" value="bank_transfer">
                            <label class="method-label" for="m_bank">
                                <span class="method-icon">💳</span>
                                <div>
                                    <div>Bank Transfer</div>
                                    <div style="font-size: 11px; opacity: 0.7;">Direct transfer</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="security-note">
                    🔒 Your payment is held securely. It's only released to the provider after you confirm the service is complete.
                </div>

                <button type="submit" class="btn-pay">
                    💳 Pay ETB {{ number_format($offer->price, 2) }}
                </button>
            </form>

            <a href="{{ route('customer.offers') }}" class="btn-cancel">Cancel and go back</a>
        </div>
    </div>
</div>
@endsection
