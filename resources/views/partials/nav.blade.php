@php($currentUser = auth()->user())
<nav class="navbar navbar-expand-lg navbar-market navbar-fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ $currentUser?->isFarmer() ? route('farmer.dashboard') : ($currentUser?->isAdmin() ? route('admin.dashboard') : route('home')) }}">
            <span class="brand-icon"><i class="fa-solid fa-wheat-awn"></i></span>
            <span class="brand-name text-success">Market<span class="text-warning">Link</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNavbar" aria-controls="appNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="appNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 nav-links">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('markets.index') ? 'active' : '' }}" href="{{ route('markets.index') }}">Markets</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('farmers.index') ? 'active' : '' }}" href="{{ route('farmers.index') }}">Farmers</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('how-it-works') ? 'active' : '' }}" href="{{ route('how-it-works') }}">How It Works</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2 flex-wrap nav-actions">
                @if($currentUser?->isCustomer())
                    <a class="nav-icon-btn position-relative" href="{{ route('cart.index') }}" title="Shopping cart"><i class="fa-solid fa-basket-shopping"></i>@php($cartCount = collect(session('cart', []))->sum())@if($cartCount > 0)<span class="cart-badge">{{ $cartCount }}</span>@endif</a>
                    <button type="button" class="nav-icon-btn" onclick="openNotificationsModal()" title="Notifications"><i class="fa-regular fa-bell"></i></button>
                @elseif($currentUser?->isFarmer())
                    <a class="btn btn-eco-primary btn-sm px-3 my-portal-btn" href="{{ route('farmer.dashboard') }}"><i class="fa-solid fa-tractor me-1"></i>Farmer Portal</a>
                @elseif($currentUser?->isAdmin())
                    <a class="btn btn-eco-primary btn-sm px-3 my-portal-btn" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-shield me-1"></i>Admin Portal</a>
                @else
                    <a class="btn btn-eco-outline btn-sm" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket me-1"></i>Sign In</a>
                    <a class="btn btn-eco-primary btn-sm" href="{{ route('register') }}"><i class="fa-solid fa-user-plus me-1"></i>Register</a>
                @endif

                @if($currentUser)
                    <div class="dropdown">
                        <button class="btn btn-eco-primary btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user"></i>{{ $currentUser->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small text-muted">{{ ucfirst($currentUser->role) }} account</span></li>
                            <li><a class="dropdown-item" href="{{ $currentUser->isFarmer() ? route('farmer.dashboard') : ($currentUser->isAdmin() ? route('admin.dashboard') : route('dashboard')) }}"><i class="fa-solid fa-gauge-high me-2"></i>{{ $currentUser->isFarmer() ? 'Farmer Dashboard' : ($currentUser->isAdmin() ? 'Admin Dashboard' : 'Dashboard') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
