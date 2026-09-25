<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MarketLink') — Farm Fresh, Direct</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="guest-body">
    <div class="guest-shell">
        <div class="guest-hero">
            <a class="guest-brand" href="{{ route('home') }}" aria-label="MarketLink home">
                <span class="brand-logo"><i class="fa-solid fa-basket-shopping"></i></span>
                <span class="fs-4 fw-bold text-white">Market<span class="text-warning">Link</span></span>
            </a>

            <div class="guest-hero-content d-none d-lg-block">
                <span class="eyebrow dark mb-3"><i class="fa-solid fa-leaf"></i> Local &amp; fresh</span>
                <h1 class="display-5 fw-bold text-white mb-3">Farm fresh.<br>Direct to your market pickup.</h1>
                <p class="text-white-50 mb-4">Discover local growers, check weekly stock, pre-order what you need, and collect it at the market.</p>
                <div class="d-flex flex-wrap gap-2 text-white-50 small">
                    <span class="guest-feature"><i class="fa-solid fa-circle-check me-1 text-warning"></i> Local farmers</span>
                    <span class="guest-feature"><i class="fa-solid fa-circle-check me-1 text-warning"></i> Pre-order pickup</span>
                    <span class="guest-feature"><i class="fa-solid fa-circle-check me-1 text-warning"></i> Fresh stock</span>
                </div>
            </div>
        </div>

        <main class="guest-main">
            <div class="guest-card">
                <div class="guest-card-top">
                    <span class="brand-logo guest-mobile-brand"><i class="fa-solid fa-basket-shopping"></i></span>
                    <div>
                        <div class="small text-uppercase fw-bold text-muted guest-kicker">MarketLink account</div>
                        <h2 class="fw-bold mb-1">@yield('authHeading', 'Welcome to MarketLink')</h2>
                        <p class="text-muted small mb-0">@yield('authSubtitle', 'Farm fresh, direct from local growers.') </p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger guest-alert" role="alert">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa-solid fa-circle-exclamation mt-1"></i>
                            <div>
                                <div class="fw-bold mb-1">Please check the highlighted details.</div>
                                <ul class="mb-0 ps-3 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')

                <div class="guest-divider"><span>MarketLink</span></div>
                <div class="guest-card-bottom">@yield('authFooter')</div>
            </div>

            <div class="guest-back-link">
                <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left me-1"></i> Back to MarketLink</a>
            </div>
        </main>
    </div>

    <div class="toast-stack" id="toastStack" aria-live="polite" aria-atomic="true">
        @if (session('status'))
            <div class="ml-toast success"><i class="fa-solid fa-circle-check"></i><span>{{ session('status') }}</span></div>
        @endif
        @if (session('error'))
            <div class="ml-toast error"><i class="fa-solid fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const input = document.getElementById(button.dataset.passwordToggle);
                    if (!input) return;
                    const visible = input.type === 'text';
                    input.type = visible ? 'password' : 'text';
                    button.innerHTML = visible
                        ? '<i class="fa-solid fa-eye"></i>'
                        : '<i class="fa-solid fa-eye-slash"></i>';
                    button.setAttribute('aria-label', visible ? 'Show password' : 'Hide password');
                });
            });

            const year = document.getElementById('currentYear');
            if (year) year.textContent = new Date().getFullYear();
        });
    </script>
    @stack('scripts')
</body>
</html>
