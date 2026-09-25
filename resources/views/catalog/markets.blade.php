@extends('layouts.app')

@section('title', 'Markets — MarketLink')

@section('content')
    <div class="page-hero-sub reveal in-view">
        <h1 class="h3 fw-bold mb-1">Find your market</h1>
        <p class="text-muted mb-0">Pre-order online, then pick up at a market near you.</p>
    </div>

    <form method="GET" action="{{ route('markets.index') }}" class="row g-2 mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search markets, locations, days…" aria-label="Search markets">
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select" name="sort" aria-label="Sort">
                <option value="name" {{ request('sort', 'name') === 'name' ? 'selected' : '' }}>Name A–Z</option>
                <option value="farmers" {{ request('sort') === 'farmers' ? 'selected' : '' }}>Most vendors</option>
                <option value="near" {{ request('sort') === 'near' ? 'selected' : '' }}>Closest to me</option>
            </select>
        </div>
    </form>

    <p class="small text-muted mb-3" id="marketsCountLabel">{{ $markets->count() }} market{{ $markets->count() === 1 ? '' : 's' }}</p>

    <div class="row g-4" id="fullMarketsGrid">
        @forelse ($markets as $market)
            <div class="col-md-4">
                <div class="eco-card p-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="icon-chip"><i class="fa-solid fa-store"></i></div>
                        <span class="badge bg-mint text-forest">{{ $market->farmers->count() }} Vendors</span>
                    </div>
                    <h5 class="fw-bold mb-1 mt-2">{{ $market->name }}</h5>
                    <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1 text-fresh"></i>{{ $market->location }} · {{ number_format($market->distance, 1) }} miles</p>
                    <p class="fw-semibold small text-dark mb-4"><i class="fa-regular fa-calendar me-1 text-warning"></i>{{ $market->days }}</p>
                    <a class="btn btn-eco-outline w-100 mt-auto" href="{{ route('farmers.index') }}"><i class="fa-solid fa-tractor me-1"></i>Farmers at this Market</a>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.6rem;"><i class="fa-solid fa-store"></i></div>
                <p class="text-muted fw-semibold">No markets matched your search.</p>
            </div>
        @endforelse
    </div>
@endsection