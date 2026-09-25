@extends('layouts.guest')

@section('title', 'Create Account')
@section('authHeading', 'Create your account')
@section('authSubtitle', 'Join as a shopper or farmer and get started in minutes.')

@section('content')
<form method="POST" action="{{ route('register.store') }}" class="auth-form" novalidate>
    @csrf

    <div class="mb-3">
        <label for="regName" class="form-label fw-semibold">Full name</label>
        <div class="input-group auth-input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="regName" name="name" value="{{ old('name') }}" placeholder="Jane Miller" autocomplete="name" required autofocus>
        </div>
        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <label for="regEmail" class="form-label fw-semibold">Email address</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="regEmail" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
            </div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-5">
            <label for="regPhone" class="form-label fw-semibold">Phone <span class="text-muted fw-normal">(optional)</span></label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="regPhone" name="phone" value="{{ old('phone') }}" placeholder="03xx-xxxxxxx" autocomplete="tel">
            </div>
            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mt-3 mb-3">
        <label class="form-label fw-semibold d-block">I want to join as</label>
        <div class="row g-2">
            <div class="col-6">
                <input type="radio" class="btn-check" name="role" id="roleCustomer" value="customer" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                <label class="btn role-choice w-100 text-start" for="roleCustomer">
                    <span class="role-choice-icon"><i class="fa-solid fa-basket-shopping"></i></span>
                    <span><strong>Shopper</strong><small>Browse and pre-order</small></span>
                </label>
            </div>
            <div class="col-6">
                <input type="radio" class="btn-check" name="role" id="roleFarmer" value="farmer" {{ old('role') === 'farmer' ? 'checked' : '' }}>
                <label class="btn role-choice w-100 text-start" for="roleFarmer">
                    <span class="role-choice-icon"><i class="fa-solid fa-tractor"></i></span>
                    <span><strong>Farmer</strong><small>Sell your produce</small></span>
                </label>
            </div>
        </div>
        @error('role')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="row g-3 mb-3 farmer-registration-fields d-block" id="farmerRegistrationFields">
        <div class="col-md-6">
            <label for="regStallName" class="form-label fw-semibold">Stall / business name</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fa-solid fa-store"></i></span>
                <input type="text" class="form-control @error('stall_name') is-invalid @enderror" id="regStallName" name="stall_name" value="{{ old('stall_name') }}" placeholder="Green Valley Farm" autocomplete="organization">
            </div>
            @error('stall_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="regMarket" class="form-label fw-semibold">Main Market</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                <select class="form-select @error('market_id') is-invalid @enderror" id="regMarket" name="market_id">
                    <option value="">Select your main market</option>
                    @foreach($markets as $market)
                        <option value="{{ $market->id }}" @selected(old('market_id') == $market->id)>{{ $market->name }} — {{ $market->location }}</option>
                    @endforeach
                </select>
            </div>
            @error('market_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="regPassword" class="form-label fw-semibold">Password</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="regPassword" name="password" placeholder="At least 8 characters" autocomplete="new-password" required>
                <button class="btn auth-password-toggle" type="button" data-password-toggle="regPassword" aria-label="Show password"><i class="fa-solid fa-eye"></i></button>
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="regPasswordConfirm" class="form-label fw-semibold">Confirm password</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text"><i class="fa-solid fa-shield-halved"></i></span>
                <input type="password" class="form-control" id="regPasswordConfirm" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password" required>
                <button class="btn auth-password-toggle" type="button" data-password-toggle="regPasswordConfirm" aria-label="Show password"><i class="fa-solid fa-eye"></i></button>
            </div>
        </div>
    </div>

    <div class="auth-note mb-3">
        <i class="fa-solid fa-circle-info"></i>
        <span>Farmer accounts are placed into an approval queue before their products become live.</span>
    </div>

    <button type="submit" class="btn btn-eco-primary w-100 py-2.5">
        <i class="fa-solid fa-user-plus me-2"></i>Create Account
    </button>
</form>
@endsection

@push('scripts')
<script>
(function(){
    const farmerRadio = document.getElementById('roleFarmer');
    const customerRadio = document.getElementById('roleCustomer');
    const fields = document.getElementById('farmerRegistrationFields');
    const stall = document.getElementById('regStallName');
    const market = document.getElementById('regMarket');

    function syncFarmerFields(){
        const active = !!(farmerRadio && farmerRadio.checked);

        if (fields) fields.classList.toggle('d-none', !active);

        if (stall) stall.required = active;
        if (market) market.required = active;
    }

    farmerRadio?.addEventListener('change', syncFarmerFields);
    customerRadio?.addEventListener('change', syncFarmerFields);

    syncFarmerFields();
})();
</script>
@endpush

@section('authFooter')
<div class="text-center small">
    <span class="text-muted">Already have a MarketLink account?</span>
    <a href="{{ route('login') }}" class="fw-bold text-fresh">Sign in</a>
</div>
@endsection
