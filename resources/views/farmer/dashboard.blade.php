@extends('layouts.farmer')
@section('title', 'Farmer Dashboard — MarketLink')
@section('content')
<div class="page-hero mb-4">
    <div class="container">
        <span class="hero-badge"><i class="fa-solid fa-tractor"></i> Farmer Workspace</span>
        <h1 class="hero-title h2 mt-3">{{ $farmer->name }}</h1>
        <p class="hero-lead">Manage your products, pickup slots, customer pre-orders and farm profile from one place.</p>
        <span class="badge {{ $farmer->status === 'verified' ? 'bg-mint text-forest' : ($farmer->status === 'suspended' ? 'bg-danger text-white' : 'bg-warning text-dark') }}">{{ ucfirst($farmer->status) }}</span>
    </div>
</div>

@if(session('status'))
    <div class="alert alert-success"><i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-warning"><i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <strong>Please check the highlighted information.</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="stat-card h-100"><div class="stat-icon green"><i class="fa-solid fa-box-open"></i></div><div><small>Total Orders</small><h3 class="mb-0">{{ $ordersCount }}</h3></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card h-100"><div class="stat-icon gold"><i class="fa-solid fa-sack-dollar"></i></div><div><small>Revenue</small><h3 class="mb-0">${{ number_format($revenue, 2) }}</h3></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card h-100"><div class="stat-icon blue"><i class="fa-solid fa-seedling"></i></div><div><small>My Products</small><h3 class="mb-0">{{ $productCount }}</h3></div></div></div>
    <div class="col-6 col-lg-3"><div class="stat-card h-100"><div class="stat-icon red"><i class="fa-solid fa-clock"></i></div><div><small>Pending</small><h3 class="mb-0">{{ $orders->where('status', 'Placed')->count() }}</h3></div></div></div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="eco-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div><h4 class="fw-bold mb-1"><i class="fa-solid fa-list-check text-fresh me-2"></i>Recent customer orders</h4><p class="small text-muted mb-0">Orders containing products from your farm.</p></div>
                <a class="btn btn-eco-outline btn-sm" href="{{ route('farmer.orders') }}">View all</a>
            </div>
            @if($orders->isEmpty())
                <div class="text-center py-5 text-muted"><div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-receipt"></i></div><p class="mb-0">No customer orders yet.</p></div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle small">
                        <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Status</th></tr></thead>
                        <tbody>
                        @foreach($orders->take(8) as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->user?->name ?? 'Customer' }}</td>
                                <td>
                                    @php($matchingItems = $order->items->filter(fn($item) => $item->product && $item->product->farmer_id === $farmer->id))
                                    @if($matchingItems->isEmpty())
                                        <span class="text-muted">No matching items</span>
                                    @else
                                        @foreach($matchingItems as $item)
                                            {{ $item->qty }}x {{ $item->name }}@if(!$loop->last), @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td><span class="badge {{ $order->status === 'Picked Up' ? 'bg-mint text-forest' : ($order->status === 'Rejected' ? 'bg-danger text-white' : 'bg-warning text-dark') }}">{{ $order->status }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <div class="col-xl-4">
        <div class="eco-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3"><h4 class="fw-bold mb-0"><i class="fa-solid fa-store text-fresh me-2"></i>Farm profile</h4><span class="role-badge">{{ $farmer->is_demo ? 'Demo' : 'Account' }}</span></div>
            <p class="text-muted small">Update how customers see your farm and pickup location.</p>
            <form method="POST" action="{{ route('farmer.profile.update') }}">
                @csrf
                <div class="mb-2"><label class="form-label small fw-semibold">Stall / business name</label><input class="form-control" name="name" value="{{ old('name', $farmer->name) }}" required></div>
                <div class="mb-2"><label class="form-label small fw-semibold">Owner / contact person</label><input class="form-control" name="owner_name" value="{{ old('owner_name', $farmer->owner_name) }}" required></div>
                <div class="mb-2"><label class="form-label small fw-semibold">Location / address</label><input class="form-control" name="location" value="{{ old('location', $farmer->location) }}" required></div>
                <div class="mb-3"><label class="form-label small fw-semibold">Specialty</label><input class="form-control" name="specialty" value="{{ old('specialty', $farmer->specialty) }}"></div>
                <button class="btn btn-eco-primary btn-sm" type="submit"><i class="fa-solid fa-floppy-disk me-1"></i>Save Profile</button>
            </form>
        </div>
    </div>
</div>

<div class="eco-card p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div><h4 class="fw-bold mb-1"><i class="fa-solid fa-star text-warning me-2"></i>Customer ratings &amp; reviews</h4><p class="small text-muted mb-0">{{ $reviewCount }} published rating{{ $reviewCount === 1 ? '' : 's' }} · {{ number_format($averageRating, 1) }}/5 average</p></div>
        <span class="badge bg-mint text-forest">Your feedback</span>
    </div>
    @if($reviews->isEmpty())
        <p class="small text-muted mb-0">Customers have not reviewed your farmer profile or products yet.</p>
    @else
        <div class="row g-3">
            @foreach($reviews as $review)
                <div class="col-lg-6">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="d-flex justify-content-between gap-2"><strong>{{ $review->user?->name ?? 'Customer' }}</strong><span class="rating">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span></div>
                        <div class="small text-muted">{{ $review->product?->name ?? 'Your farmer profile' }} · {{ $review->created_at->format('M j, Y g:i A') }}</div>
                        @if($review->review)<p class="small mb-0 mt-2">{{ $review->review }}</p>@endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
