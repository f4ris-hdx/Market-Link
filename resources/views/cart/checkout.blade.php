@extends('layouts.portal')

@section('title', 'Checkout — MarketLink')

@section('content')
    <div class="page-hero-sub reveal in-view">
        <h1 class="h3 fw-bold mb-1">Checkout &amp; pickup</h1>
        <p class="text-muted mb-0">Choose your market slot — your order is confirmed once placed.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="eco-card p-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-hand-holding-heart me-2 text-fresh"></i>Pickup details</h5>
                <form method="POST" action="{{ route('checkout.place') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Name at pickup *</label>
                            <input type="text" class="form-control" name="pickup_name" value="{{ auth()->user()->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Phone <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control" name="pickup_phone" value="{{ auth()->user()->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Market</label>
                            <select class="form-select" name="market_id">
                                <option value="" selected>Any market (best fit)</option>
                                @foreach ($markets as $market)
                                    <option value="{{ $market->id }}">{{ $market->name }} — {{ $market->location }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Pickup slot *</label>
                            <select class="form-select" name="slot" required>
                                @foreach (explode(',', 'Sat 8:00-10:00 AM, Sat 10:30-12:30 PM, Sun 9:00-11:00 AM, Sun 11:30 AM-1:30 PM') as $slot)
                                    <option value="{{ trim($slot) }}">{{ trim($slot) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Pickup date <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="date" class="form-control" name="pickup_date">
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <a href="{{ route('cart.index') }}" class="btn btn-eco-outline btn-sm"><i class="fa-solid fa-arrow-left me-1"></i>Back to Basket</a>
                                <button type="submit" class="btn btn-eco-primary"><i class="fa-solid fa-bag-check me-1"></i>Place Pre-Order</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="eco-card p-4" id="checkoutSummaryContent">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-receipt me-2 text-fresh"></i>Order summary</h5>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">{{ $lines->count() }} item(s) in basket</span>
                    <span class="fw-bold text-forest">${{ number_format($total, 2) }}</span>
                </div>
                <div class="small text-muted">
                    @foreach ($lines as $line)
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $line['qty'] }}x {{ $line['product']->name }}</span>
                            <span>${{ number_format($line['total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <hr>
                <div class="d-flex justify-content-between fs-5">
                    <span class="fw-bold">Total</span>
                    <span class="fw-bold text-forest">${{ number_format($total, 2) }}</span>
                </div>
                <p class="small text-muted mt-2 mb-0"><i class="fa-solid fa-circle-info me-1"></i>Pay in person at the stall. Order history is saved to your account.</p>
            </div>
        </div>
    </div>
@endsection