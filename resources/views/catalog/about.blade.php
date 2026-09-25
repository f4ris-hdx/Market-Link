@extends('layouts.app')

@section('title', 'About — MarketLink')

@section('content')
    <div class="page-hero-sub reveal in-view text-center mx-auto" style="max-width:720px;">
        <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.5rem;"><i class="fa-solid fa-wheat-awn"></i></div>
        <h1 class="h3 fw-bold mb-2">Farm fresh, straight from the grower</h1>
        <p class="text-muted mb-0">MarketLink connects you directly with verified local farmers — you pre-order fresh produce, dairy, and bakery items, then collect them yourself at the weekend market. Zero food miles, fair prices, real taste.</p>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="eco-card p-4 h-100 text-center">
                <div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-handshake"></i></div>
                <h5 class="fw-bold mb-2">Fair for farmers</h5>
                <p class="text-muted small mb-0">No middlemen taking a cut. Farmers keep 100% of every sale and know exactly what to harvest each week.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="eco-card p-4 h-100 text-center">
                <div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-leaf"></i></div>
                <h5 class="fw-bold mb-2">Fresh for you</h5>
                <p class="text-muted small mb-0">Pre-orders are picked at peak ripeness and handed to you at the stall — nothing shipped, nothing stored for weeks.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="eco-card p-4 h-100 text-center">
                <div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-trash-can"></i></div>
                <h5 class="fw-bold mb-2">Zero waste</h5>
                <p class="text-muted small mb-0">Every item is pre-sold before it's harvested. Less spoilage, less waste, and more variety on local shelves.</p>
            </div>
        </div>
    </div>

    <div class="eco-card p-4 mt-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h5 class="fw-bold mb-1">Ready to build your basket?</h5>
            <p class="text-muted small mb-0">Browse this week's harvest and place a pre-order today.</p>
        </div>
        <a class="btn btn-eco-primary" href="{{ route('products.index') }}"><i class="fa-solid fa-carrot me-1"></i>Shop the Marketplace</a>
    </div>
@endsection