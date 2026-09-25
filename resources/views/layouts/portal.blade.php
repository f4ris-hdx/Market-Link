<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MarketLink')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>
<body data-role="customer" class="role-portal-body role-portal-customer">
<nav class="navbar navbar-expand-lg navbar-dark navbar-market sticky-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}"><span class="brand-logo"><i class="fa-solid fa-basket-shopping"></i></span><span class="fs-4 text-white">Market<span class="text-warning">Link</span></span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#portalNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="portalNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('markets.index') ? 'active' : '' }}" href="{{ route('markets.index') }}">Markets</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('farmers.index') ? 'active' : '' }}" href="{{ route('farmers.index') }}">Farmers</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('how-it-works') ? 'active' : '' }}" href="{{ route('how-it-works') }}">How It Works</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2 nav-actions">
                <a class="nav-icon-btn position-relative" href="{{ route('cart.index') }}" title="Shopping cart"><i class="fa-solid fa-cart-shopping"></i>@php($cartCount = collect(session('cart', []))->sum())@if($cartCount>0)<span class="cart-badge">{{ $cartCount }}</span>@endif</a>
                <a class="btn btn-eco-primary btn-sm px-3 my-portal-btn" href="{{ route('dashboard') }}"><i class="fa-solid fa-user-circle me-1"></i>My Portal</a>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle rounded-pill btn-sm px-3" data-bs-toggle="dropdown" type="button"><i class="fa-solid fa-user-gear me-1"></i>{{ auth()->user()->name }}</button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><span class="dropdown-item-text small text-muted">Customer account</span></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge-high me-2"></i>My Portal</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item text-danger" type="submit"><i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out</button></form></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<main class="py-5"><div class="container">@yield('content')</div></main>
<footer class="footer-market"><div class="container"><div class="row g-4"><div class="col-lg-5"><div class="fs-4 fw-bold text-white">Market<span class="text-warning">Link</span></div><p class="small mt-2 mb-0">Farm-fresh products, pre-orders and local market pickup — directly from farmers.</p></div><div class="col-lg-7 text-lg-end"><a href="{{ route('products.index') }}">Products</a><a href="{{ route('markets.index') }}">Markets</a><a href="{{ route('farmers.index') }}">Farmers</a><a href="{{ route('about') }}">About</a></div></div></div></footer>
<div class="toast-stack" id="toastStack">@if(session('status'))<div class="ml-toast success"><i class="fa-solid fa-circle-check"></i><span>{{ session('status') }}</span></div>@endif @if(session('error'))<div class="ml-toast error"><i class="fa-solid fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>@endif</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script><script src="{{ asset('js/app.js') }}"></script>
<script>window.ML_BACKEND=@json(['authenticated'=>auth()->check(),'role'=>auth()->user()?->role,'csrf'=>csrf_token()]);window.ML_URLS={home:@json(route('home')),products:@json(route('products.index')),markets:@json(route('markets.index')),farmers:@json(route('farmers.index')),dashboard:@json(route('dashboard')),login:@json(route('login')),register:@json(route('register')),farmerDashboard:@json(route('farmer.dashboard')),adminDashboard:@json(route('admin.dashboard'))};initGlobalUI();</script>
@stack('scripts')
</body></html>
