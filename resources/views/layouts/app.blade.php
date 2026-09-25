<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MarketLink') — Farm Fresh, Direct</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @include('partials.nav')

    <main class="app-main">
        <div class="container pt-5">
            @yield('content')
        </div>
    </main>

    @include('partials.footer')

    <div class="toast-stack" id="toastStack" aria-live="polite" aria-atomic="true">
        @if (session('status'))
            <div class="ml-toast success"><i class="fa-solid fa-circle-check"></i><span>{{ session('status') }}</span></div>
        @endif
        @if (session('error'))
            <div class="ml-toast error"><i class="fa-solid fa-circle-exclamation"></i><span>{{ session('error') }}</span></div>
        @endif
    </div>

    <button class="back-to-top" id="backToTopBtn" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Back to top"><i class="fa-solid fa-arrow-up"></i></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>