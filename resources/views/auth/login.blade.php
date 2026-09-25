@extends('layouts.guest')

@section('title', 'Sign In')
@section('authHeading', 'Welcome back')
@section('authSubtitle', 'Sign in to manage your MarketLink account and orders.')

@section('content')
<form method="POST" action="{{ route('login.store') }}" class="auth-form" novalidate>
    @csrf

    <div class="auth-role-banner mb-4">
        <div class="icon-chip"><i class="fa-solid fa-user"></i></div>
        <div>
            <strong>MarketLink account</strong>
            <div class="small text-muted">Access your dashboard and account features</div>
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
    <span class="text-muted">New to MarketLink?</span>
    <a href="{{ route('register') }}" class="fw-bold text-fresh">Create an account</a>
</div>
@endsection
