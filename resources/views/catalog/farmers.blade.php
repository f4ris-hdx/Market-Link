@extends('layouts.app')

@section('title', 'Farmers — MarketLink')

@section('content')
    <div class="page-hero-sub reveal in-view">
        <h1 class="h3 fw-bold mb-1">Meet the growers</h1>
        <p class="text-muted mb-0">Every farmer on MarketLink is verified by our team.</p>
    </div>

    <form method="GET" action="{{ route('farmers.index') }}" class="row g-2 mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search farmers, specialties, areas…" aria-label="Search farmers">
            </div>
        </div>
        <div class="col-md-4">
            <button class="btn btn-eco-primary w-100" type="submit"><i class="fa-solid fa-magnifying-glass me-1"></i>Search Growers</button>
        </div>
    </form>

    <p class="small text-muted mb-3" id="farmersCountLabel">{{ $farmers->count() }} farmer{{ $farmers->count() === 1 ? '' : 's' }}</p>

    <div class="row g-4" id="farmersDirectoryGrid">
        @forelse ($farmers as $farmer)
            <div class="col-md-4">
                <div class="eco-card h-100 p-4 text-center d-flex flex-column">
                    <img src="{{ $farmer->image ?? 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=500&q=80' }}" class="rounded-circle mb-3 border border-3 border-success mx-auto" style="width:96px;height:96px;object-fit:cover;" alt="{{ $farmer->name }}" loading="lazy">
                    <h5 class="fw-bold mb-1">{{ $farmer->name }}</h5>
                    <p class="small text-muted mb-1"><i class="fa-solid fa-map-pin me-1"></i>{{ $farmer->location }}</p>
                    <p class="small text-dark fw-semibold mb-3">{{ $farmer->specialty }}</p>
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                        <span class="rating text-gold"><i class="fa-solid fa-star"></i> {{ number_format($farmer->rating, 1) }}</span>
                        <span class="badge bg-mint text-forest"><i class="fa-solid fa-circle-check me-1"></i>Verified</span>
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <a class="btn btn-eco-primary btn-sm flex-fill" href="{{ route('products.index') }}">View Produce</a>
                        <button class="btn btn-eco-outline btn-sm" title="Contact farmer" onclick="document.getElementById('toastStack')?.insertAdjacentHTML('beforeend','<div class=\'ml-toast success\'><i class=\'fa-solid fa-circle-check\'></i><span>Message request sent to {{ $farmer->name }}!</span></div>');"><i class="fa-solid fa-envelope"></i></button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.6rem;"><i class="fa-solid fa-tractor"></i></div>
                <p class="text-muted fw-semibold">No farmers matched your search.</p>
            </div>
        @endforelse
    </div>
@endsection