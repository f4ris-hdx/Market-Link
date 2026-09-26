<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Farmer Portal — MarketLink')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body data-role="farmer" class="role-portal-body role-portal-farmer">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-market sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('farmer.dashboard') }}">
                <span class="brand-logo"><i class="fa-solid fa-basket-shopping"></i></span>
                <span class="fs-4 text-white">Market<span class="text-warning">Link</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#farmerNav" aria-controls="farmerNav" aria-expanded="false" aria-label="Toggle farmer navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="farmerNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}" href="{{ route('farmer.dashboard') }}"><i class="fa-solid fa-gauge-high me-1"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('farmer.products*') ? 'active' : '' }}" href="{{ route('farmer.products') }}"><i class="fa-solid fa-boxes-stacked me-1"></i>My Products</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('farmer.orders') ? 'active' : '' }}" href="{{ route('farmer.orders') }}"><i class="fa-solid fa-list-check me-1"></i>Orders</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('farmer.slots') ? 'active' : '' }}" href="{{ route('farmer.slots') }}"><i class="fa-solid fa-clock me-1"></i>Pickup Slots</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="fa-solid fa-store me-1"></i>Marketplace</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2 nav-actions">
                    <div class="dropdown">
                        <button class="btn btn-eco-primary btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-tractor"></i>{{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small text-muted"><i class="fa-solid fa-seedling me-1"></i>Farmer account</span></li>
                            <li><a class="dropdown-item" href="{{ route('farmer.dashboard') }}"><i class="fa-solid fa-gauge-high me-2"></i>Farmer Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('farmer.products') }}"><i class="fa-solid fa-box me-2"></i>My Products</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="portal-context-bar">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div><span class="role-badge"><i class="fa-solid fa-tractor me-1"></i>Farmer Workspace</span><span class="small text-white-50 ms-2">Manage inventory, orders and pickup availability</span></div>
            <span class="small text-white-50">Status: <strong class="text-white">{{ ucfirst(auth()->user()->farmer?->status ?? 'pending') }}</strong></span>
        </div>
    </div>

    <main class="py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="footer-market">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5"><div class="fs-4 fw-bold text-white">Market<span class="text-warning">Link</span></div><p class="small mt-2 mb-0">Farmer workspace for fresh local-market inventory and pickup orders.</p></div>
                <div class="col-lg-7 text-lg-end"><a href="{{ route('farmer.dashboard') }}">Dashboard</a><a href="{{ route('farmer.products') }}">Products</a><a href="{{ route('farmer.orders') }}">Orders</a><a href="{{ route('home') }}">Marketplace</a></div>
            </div>
        </div>
    </footer>

    <div class="toast-stack" id="toastStack" aria-live="polite" aria-atomic="true">
        @if(session('status'))<div class="ml-toast success"><i class="fa-solid fa-circle-check"></i><span>{{ session('status') }}</span></div>@endif
        @if(session('error'))<div class="ml-toast error"><i class="fa-solid fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>@endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>window.ML_BACKEND=@json(['authenticated'=>true,'role'=>'farmer','csrf'=>csrf_token()]); window.ML_URLS={home:@json(route('home')),products:@json(route('products.index')),markets:@json(route('markets.index')),farmers:@json(route('farmers.index')),dashboard:@json(route('farmer.dashboard')),login:@json(route('login')),register:@json(route('register')),farmerDashboard:@json(route('farmer.dashboard')),adminDashboard:@json(route('admin.dashboard'))}; if (typeof initGlobalUI==='function') initGlobalUI();</script>
    @stack('scripts')
</body>
</html>
