@extends('layouts.portal')

@section('title', 'Your Basket — MarketLink')

@section('content')
    <div class="page-hero-sub reveal in-view">
        <h1 class="h3 fw-bold mb-1">Your basket</h1>
        <p class="text-muted mb-0">Review quantities, then head to checkout to pick your pickup slot.</p>
    </div>

    @if ($lines->isEmpty())
        <div class="text-center py-5">
            <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.5rem;"><i class="fa-solid fa-basket-shopping"></i></div>
            <p class="fw-semibold mb-1">Your basket is empty</p>
            <p class="text-muted small mb-3">Browse the weekly marketplace to add fresh produce.</p>
            <a class="btn btn-eco-primary btn-sm" href="{{ route('products.index') }}"><i class="fa-solid fa-carrot me-1"></i>Start Shopping</a>
        </div>
    @else
        <form method="POST" action="{{ route('cart.update') }}" id="cartForm">
            @csrf
            @foreach ($lines as $line)
                <div class="eco-card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $line['product']->image }}" class="rounded" style="width:64px;height:64px;object-fit:cover;" alt="{{ $line['product']->name }}">
                            <div>
                                <h6 class="fw-bold mb-0 text-forest">{{ $line['product']->name }}</h6>
                                <small class="text-muted">{{ $line['product']->farmer->name }} · ${{ number_format($line['product']->price, 2) }}/{{ $line['product']->unit }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="btn-group">
                                <input type="number" class="form-control form-control-sm" style="max-width:80px;" name="qty[{{ $line['product']->id }}]" value="{{ $line['qty'] }}" min="1" max="{{ $line['product']->stock }}">
                            </div>
                            <span class="fw-bold ms-2" style="min-width:62px;text-align:right;">${{ number_format($line['total'], 2) }}</span>
                            <form method="POST" action="{{ route('cart.remove', $line['product']) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm text-danger ms-1" title="Remove"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="eco-card p-4 mt-4" id="cartSummaryBox">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold" id="cartSubtotal">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fs-5">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-forest" id="cartTotal">${{ number_format($total, 2) }}</span>
                        </div>
                        <small class="text-muted d-block mt-1">Pay in person at pickup.</small>
                    </div>
                    <div class="col-md-6 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                        <button class="btn btn-eco-outline btn-sm" type="submit"><i class="fa-solid fa-arrows-rotate me-1"></i>Update Quantities</button>
                        <a class="btn btn-eco-primary" href="{{ route('checkout') }}"><i class="fa-solid fa-arrow-right me-1"></i>Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection