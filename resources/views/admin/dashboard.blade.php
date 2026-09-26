@extends('layouts.admin')
@section('title', 'Admin Dashboard — MarketLink')
@section('content')
<div class="page-hero mb-4"><div class="container"><span class="hero-badge"><i class="fa-solid fa-shield-halved"></i> Platform Administration</span><h1 class="hero-title h2 mt-3">MarketLink Admin Portal</h1><p class="hero-lead">Manage accounts, farmer approvals, products, markets and marketplace content from one workspace.</p></div></div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2"><div class="stat-card h-100"><div class="stat-icon blue"><i class="fa-solid fa-users"></i></div><div><small>Total Users</small><h3 class="mb-0">{{ number_format($metrics['users']) }}</h3></div></div></div>
    <div class="col-6 col-lg-2"><div class="stat-card h-100"><div class="stat-icon green"><i class="fa-solid fa-tractor"></i></div><div><small>Farmers</small><h3 class="mb-0">{{ number_format($metrics['farmers']) }}</h3></div></div></div>
    <div class="col-6 col-lg-2"><div class="stat-card h-100"><div class="stat-icon gold"><i class="fa-solid fa-store"></i></div><div><small>Markets</small><h3 class="mb-0">{{ number_format($metrics['markets']) }}</h3></div></div></div>
    <div class="col-6 col-lg-2"><div class="stat-card h-100"><div class="stat-icon red"><i class="fa-solid fa-boxes-stacked"></i></div><div><small>Products</small><h3 class="mb-0">{{ number_format($metrics['products']) }}</h3></div></div></div>
    <div class="col-6 col-lg-2"><div class="stat-card h-100"><div class="stat-icon blue"><i class="fa-solid fa-receipt"></i></div><div><small>Orders</small><h3 class="mb-0">{{ number_format($metrics['orders']) }}</h3></div></div></div>
    <div class="col-6 col-lg-2"><div class="stat-card h-100"><div class="stat-icon gold"><i class="fa-solid fa-sack-dollar"></i></div><div><small>Order Value</small><h3 class="mb-0">${{ number_format($metrics['revenue'], 2) }}</h3></div></div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><a class="eco-card p-4 h-100 d-block text-decoration-none" href="{{ route('admin.users') }}"><div class="icon-chip mb-3"><i class="fa-solid fa-users-gear"></i></div><h5 class="fw-bold text-forest">Manage Users</h5><p class="small text-muted mb-0">Full account CRUD, roles and access status.</p></a></div>
    <div class="col-md-3"><a class="eco-card p-4 h-100 d-block text-decoration-none" href="{{ route('admin.farmers') }}"><div class="icon-chip mb-3"><i class="fa-solid fa-user-check"></i></div><h5 class="fw-bold text-forest">Manage Farmers</h5><p class="small text-muted mb-0">Create, edit, verify, suspend and remove profiles.</p></a></div>
    <div class="col-md-3"><a class="eco-card p-4 h-100 d-block text-decoration-none" href="{{ route('admin.products') }}"><div class="icon-chip mb-3"><i class="fa-solid fa-boxes-stacked"></i></div><h5 class="fw-bold text-forest">Manage Products</h5><p class="small text-muted mb-0">Full product CRUD and listing moderation.</p></a></div>
    <div class="col-md-3"><a class="eco-card p-4 h-100 d-block text-decoration-none" href="{{ route('admin.markets') }}"><div class="icon-chip mb-3"><i class="fa-solid fa-map-location-dot"></i></div><h5 class="fw-bold text-forest">Manage Markets</h5><p class="small text-muted mb-0">Create, edit and remove market hubs.</p></a></div>
</div>

<div class="row g-4">
    <div class="col-lg-7" id="announcements">
        <div class="eco-card p-4">
            <h4 class="fw-bold mb-3"><i class="fa-solid fa-bullhorn text-fresh me-2"></i>Announcements</h4>
            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf
                <div class="row g-2"><div class="col-md-5"><input class="form-control" name="title" placeholder="Announcement title" required></div><div class="col-md-7"><input class="form-control" name="message" placeholder="Message for the marketplace" required></div></div>
                <button class="btn btn-eco-primary btn-sm mt-3" type="submit"><i class="fa-solid fa-paper-plane me-1"></i>Publish</button>
            </form>
            <hr>
            @if($announcements->isEmpty())
                <p class="small text-muted mb-0">No announcements yet.</p>
            @else
                @foreach($announcements as $announcement)
                    <div class="border-start border-4 border-success bg-light rounded-3 p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-start gap-2"><strong>{{ $announcement->title }}</strong><form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}">@csrf @method('DELETE')<button class="btn btn-sm text-danger" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button></form></div>
                        <p class="small text-muted mb-0">{{ $announcement->message }}</p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="col-lg-5">
        <div class="eco-card p-4">
            <h4 class="fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left text-fresh me-2"></i>Recent Orders</h4>
            @if($recentOrders->isEmpty())
                <p class="small text-muted mb-0">No orders yet.</p>
            @else
                @foreach($recentOrders as $order)
                    <div class="d-flex justify-content-between border-bottom py-2 small gap-3"><div><strong>{{ $order->order_number }}</strong><div class="text-muted">{{ $order->user?->name ?? 'Customer' }} · {{ optional($order->placed_at)->format('M j') }}</div></div><div class="text-end"><strong class="text-forest">${{ number_format($order->total, 2) }}</strong><br><span class="badge bg-mint text-forest">{{ $order->status }}</span></div></div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="eco-card p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div><h4 class="fw-bold mb-1"><i class="fa-solid fa-star text-warning me-2"></i>Ratings &amp; Reviews</h4><p class="small text-muted mb-0">{{ number_format($metrics['reviews']) }} published reviews · {{ number_format($metrics['averageRating'], 1) }}/5 average rating</p></div>
        <span class="badge bg-mint text-forest">Customer feedback</span>
    </div>
    @if($recentReviews->isEmpty())
        <p class="small text-muted mb-0">No customer ratings or reviews yet.</p>
    @else
        <div class="row g-3">
            @foreach($recentReviews as $review)
                <div class="col-lg-6">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="d-flex justify-content-between gap-2"><strong>{{ $review->user?->name ?? 'Customer' }}</strong><span class="rating">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span></div>
                        <div class="small text-muted">{{ $review->product?->name ?? 'Farmer review: '.$review->farmer?->name }} · {{ $review->created_at->format('M j, Y g:i A') }}</div>
                        @if($review->review)<p class="small mb-0 mt-2">{{ $review->review }}</p>@endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
