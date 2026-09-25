@extends('layouts.guest')

@php($loginRole = $loginRole ?? null)

@section('title', $loginRole === 'admin' ? 'Admin Sign In' : ($loginRole === 'farmer' ? 'Farmer Sign In' : 'Sign In'))
@section('authHeading', $loginRole === 'admin' ? 'Admin sign in' : ($loginRole === 'farmer' ? 'Farmer sign in' : 'Welcome back'))
@section('authSubtitle', $loginRole === 'admin' ? 'Access the MarketLink administration console.' : ($loginRole === 'farmer' ? 'Access your MarketLink farmer workspace.' : 'Sign in to manage your MarketLink account and orders.'))

@section('content')
<form method="POST" action="{{ $loginRole === 'admin' ? route('admin.login.store') : ($loginRole === 'farmer' ? route('farmer.login.store') : route('login.store')) }}" class="auth-form" novalidate>
    @csrf
    @if($loginRole)<input type="hidden" name="login_role" value="{{ $loginRole }}">@endif

    <div class="auth-role-banner mb-4">
        <div class="icon-chip"><i class="fa-solid {{ $loginRole === 'admin' ? 'fa-user-shield' : ($loginRole === 'farmer' ? 'fa-tractor' : 'fa-user') }}"></i></div>
        <div>
            <strong>{{ $loginRole === 'admin' ? 'Administrator Portal' : ($loginRole === 'farmer' ? 'Farmer Portal' : 'MarketLink Customer Portal') }}</strong>
            <div class="small text-muted">{{ $loginRole === 'admin' ? 'Management, moderation and reporting' : ($loginRole === 'farmer' ? 'Inventory, orders and pickup management' : 'Shopping, favorites and pre-orders') }}</div>
        </div>
    </div>

    <div class="mb-3">
        <label for="loginEmail" class="form-label fw-semibold">Email address</label>
        <div class="input-group auth-input-group">
            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="loginEmail" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required autofocus>
        </div>
        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label for="loginPassword" class="form-label fw-semibold">Password</label>
        <div class="input-group auth-input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="loginPassword" name="password" placeholder="Enter your password" autocomplete="current-password" required>
            <button class="btn auth-password-toggle" type="button" data-password-toggle="loginPassword" aria-label="Show password"><i class="fa-solid fa-eye"></i></button>
        </div>
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check"><input class="form-check-input" type="checkbox" name="remember" id="rememberMe" value="1" {{ old('remember') ? 'checked' : '' }}><label class="form-check-label small" for="rememberMe">Remember me</label></div>
        <span class="small text-muted">Secure sign in</span>
    </div>

    <button type="submit" class="btn btn-eco-primary w-100 py-2"><i class="fa-solid fa-right-to-bracket me-2"></i>Sign In</button>
</form>
@endsection

@section('authFooter')
<div class="text-center small">
    @if($loginRole === 'admin')
        <span class="text-muted">Not an administrator?</span> <a href="{{ route('login') }}" class="fw-bold text-fresh">Use standard sign in</a>
    @elseif($loginRole === 'farmer')
        <span class="text-muted">Need a farmer account?</span> <a href="{{ route('register') }}" class="fw-bold text-fresh">Create one</a>
    @else
        <span class="text-muted">New to MarketLink?</span> <a href="{{ route('register') }}" class="fw-bold text-fresh">Create an account</a>
    @endif
</div>
<div class="text-center small mt-2">
    @if(!$loginRole)<a href="{{ route('farmer.login') }}" class="me-3 text-fresh">Farmer sign in</a><a href="{{ route('admin.login') }}" class="text-fresh">Admin sign in</a>@endif
</div>
@endsection
