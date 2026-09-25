@extends('layouts.app')

@section('title', 'Products — MarketLink')

@php($favoritesIds = auth()->user()->favorites()->pluck('product_id'))

@section('content')
    <div class="page-hero-sub reveal in-view">
        <h1 class="h3 fw-bold mb-1">Marketplace</h1>
        <p class="text-muted mb-0">Fresh harvest from verified local growers — {{ $products->total() }} products available.</p>
    </div>

    <form method="GET" action="{{ route('products.index') }}" class="row g-2 mb-4">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search products, farmers…" aria-label="Search products">
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select" name="category" aria-label="Category">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>{{ $category->name }} ({{ $category->products_count }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" name="sort" aria-label="Sort">
                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Newest first</option>
                <option value="price-asc" {{ request('sort') === 'price-asc' ? 'selected' : '' }}>Price: low to high</option>
                <option value="price-desc" {{ request('sort') === 'price-desc' ? 'selected' : '' }}>Price: high to low</option>
                <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Top rated</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-eco-primary w-100" type="submit"><i class="fa-solid fa-filter"></i></button>
        </div>
    </form>

    <div class="d-flex flex-wrap gap-2 mb-3" id="filterChips">
        <a href="{{ route('products.index', array_merge(request()->except('category'), ['category' => ''])) }}" class="chip {{ ! request('category') ? 'active' : '' }}">All</a>
        @foreach ($categories as $category)
            <a href="{{ route('products.index', array_merge(request()->except('category'), ['category' => $category->slug])) }}" class="chip {{ request('category') === $category->slug ? 'active' : '' }}">{{ $category->name }}</a>
        @endforeach
    </div>

    <p class="small text-muted mb-3" id="productsCountLabel">Showing {{ $products->total() }} fresh product{{ $products->total() === 1 ? '' : 's' }}</p>

    <div class="row g-4" id="mainProductsGrid">
        @forelse ($products as $product)
            @include('partials.product-card', ['product' => $product, 'favoritesIds' => $favoritesIds])
        @empty
            <div class="col-12 text-center py-5">
                <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.6rem;"><i class="fa-solid fa-lemon"></i></div>
                <p class="text-muted fw-semibold">No fresh items matched your search criteria.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection