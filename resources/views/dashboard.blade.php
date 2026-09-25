@extends('layouts.app')

@section('title', 'Dashboard — MarketLink')

@section('content')
    <div class="page-hero-sub d-flex flex-wrap align-items-center justify-content-between gap-3 reveal in-view">
        <div>
            <h1 class="h3 fw-bold mb-1 text-black">Hey {{ auth()->user()->name }}! 👋</h1>
            <p class="text-muted mb-0">Order history, favorites, and your profile — all in one place.</p>
        </div>
        <span class="badge bg-mint text-forest px-3 py-2"><i class="fa-solid fa-tag me-1"></i>{{ ucfirst(auth()->user()->role) }} account</span>
    </div>

    @php
        $activeOrders = $orders->where('status', '!=', 'Picked Up')->count();
        $lifetime = $orders->sum('total');
    @endphp
    <div class="row g-3 mb-4" id="customerStatCards">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa-solid fa-box-open"></i></div>
                <div><small>Active Pre-Orders</small><h3 class="mb-0">{{ $activeOrders }}</h3></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div><small>Total Orders</small><h3 class="mb-0">{{ $orders->count() }}</h3></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon red"><i class="fa-solid fa-heart"></i></div>
                <div><small>Favorites Saved</small><h3 class="mb-0">{{ $favorites->count() }}</h3></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fa-solid fa-sack-dollar"></i></div>
                <div><small>Lifetime Spend</small><h3 class="mb-0">${{ number_format($lifetime, 2) }}</h3></div>
            </div>
        </div>
    </div>

    <ul class="nav nav-pills ms-tabs mb-4" id="custTabs">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tabOrders" type="button">Order History</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabFavorites" type="button">Favorites</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tabProfile" type="button">My Profile</button></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tabOrders">
            @if ($orders->isEmpty())
                <div class="text-center py-5">
                    <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.5rem;"><i class="fa-solid fa-box-open"></i></div>
                    <p class="fw-semibold mb-1">No pre-orders placed yet</p>
                    <p class="text-muted small mb-3">Head to the marketplace and build your first basket.</p>
                    <a class="btn btn-eco-primary btn-sm" href="{{ route('products.index') }}"><i class="fa-solid fa-carrot me-1"></i>Start Shopping</a>
                </div>
            @else
                @foreach ($orders as $order)
                    <div class="eco-card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <div>
                                <span class="fw-bold text-forest">{{ $order->order_number }}</span>
                                <small class="text-muted ms-2"><i class="fa-regular fa-calendar me-1"></i>{{ $order->placed_at->format('M j, Y') }}</small>
                            </div>
                            <span class="status-pill badge {{ $order->status === 'Picked Up' ? 'bg-mint text-forest' : 'bg-warning text-dark' }}">{{ $order->status }}</span>
                        </div>
                        <p class="small mb-1"><strong><i class="fa-solid fa-location-dot me-1 text-fresh"></i>Pickup:</strong> {{ $order->market->name ?? 'Market TBD' }} · {{ $order->slot }}@if ($order->pickup_date) ({{ $order->pickup_date->format('M j, Y') }})@endif</p>
                        <p class="small text-muted mb-2"><strong>Items:</strong>
                            @foreach ($order->items as $item)
                                {{ $item->qty }}x {{ $item->name }}@if (!$loop->last), @endif
                            @endforeach
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top flex-wrap gap-2">
                            <span class="fw-bold text-forest">Total: ${{ number_format($order->total, 2) }} <small class="text-muted fw-normal">(pay at pickup)</small></span>
                            <span class="small text-muted"><i class="fa-solid fa-user me-1"></i>{{ $order->pickup_name }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="tab-pane fade" id="tabFavorites">
            @if ($favorites->isEmpty())
                <div class="text-center py-5">
                    <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.5rem;"><i class="fa-solid fa-heart"></i></div>
                    <p class="fw-semibold mb-1">No favorites yet</p>
                    <p class="text-muted small mb-3">Tap the heart on any product to save it here.</p>
                    <a class="btn btn-eco-primary btn-sm" href="{{ route('products.index') }}"><i class="fa-solid fa-carrot me-1"></i>Browse Products</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($favorites as $favorite)
                        @include('partials.product-card', ['product' => $favorite->product, 'favoritesIds' => $favorites->pluck('product_id')])
                    @endforeach
                </div>
            @endif
        </div>

        <div class="tab-pane fade" id="tabProfile">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="eco-card p-4 h-100">
                        <h5 class="fw-bold mb-3"><i class="fa-solid fa-id-card me-2 text-fresh"></i>Account details</h5>
                        <form method="POST" action="{{ route('dashboard.profile') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Full name</label>
                                <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Email</label>
                                <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                                <div class="form-text">Email is your sign-in and cannot be changed here.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Phone <span class="text-muted fw-normal">(optional)</span></label>
                                <input type="text" class="form-control" name="phone" value="{{ auth()->user()->phone }}">
                            </div>
                            <button class="btn btn-eco-primary btn-sm"><i class="fa-solid fa-floppy-disk me-1"></i>Save Details</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="eco-card p-4 h-100">
                        <h5 class="fw-bold mb-3"><i class="fa-solid fa-shield-halved me-2 text-fresh"></i>Membership</h5>
                        <p class="small text-muted mb-3">Your MarketLink role controls which tools you can access.</p>
                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3 mb-2">
                            <div><strong>Role</strong><div class="small text-muted">{{ ucfirst(auth()->user()->role) }}</div></div>
                            <span class="badge bg-mint text-forest">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border rounded-3 p-3">
                            <div><strong>Member since</strong><div class="small text-muted">{{ auth()->user()->created_at->format('M j, Y') }}</div></div>
                            <i class="fa-solid fa-wheat-awn text-fresh fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="mt-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h4 fw-bold mb-0">Fresh this week</h2>
            <a href="{{ route('products.index') }}" class="btn btn-eco-outline btn-sm">View all <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4" id="featuredProductsGrid">
            @foreach ($products->take(8) as $product)
                @include('partials.product-card', ['product' => $product, 'favoritesIds' => $favorites->pluck('product_id')])
            @endforeach
        </div>
    </section>
@endsection