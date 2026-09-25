@extends('layouts.admin')
@section('title', isset($user) ? 'Edit User — MarketLink' : 'Create User — MarketLink')
@section('content')
<div class="portal-page-heading"><span class="eyebrow"><i class="fa-solid fa-users-gear"></i> User Management</span><h1 class="h3 fw-bold mt-2 mb-1">{{ isset($user) ? 'Edit User' : 'Create User' }}</h1><p class="text-muted mb-0">Manage account details, role, status and password.</p></div>
<div class="row justify-content-center mt-4"><div class="col-lg-8"><div class="eco-card p-4">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">@csrf @if(isset($user)) @method('PUT') @endif
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Name</label><input class="form-control" name="name" value="{{ old('name', $user->name ?? '') }}" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Email</label><input class="form-control" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input class="form-control" name="phone" value="{{ old('phone', $user->phone ?? '') }}"></div>
<div class="col-md-3"><label class="form-label fw-semibold">Role</label><select class="form-select" name="role" required>@foreach(['customer','farmer','admin'] as $role)<option value="{{ $role }}" @selected(old('role', $user->role ?? 'customer') === $role)>{{ ucfirst($role) }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" required>@foreach(['active','inactive'] as $status)<option value="{{ $status }}" @selected(old('status', $user->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label fw-semibold">{{ isset($user) ? 'New password (optional)' : 'Password' }}</label><input class="form-control" type="password" name="password" {{ isset($user) ? '' : 'required' }}></div>
<div class="col-md-6"><label class="form-label fw-semibold">Confirm password</label><input class="form-control" type="password" name="password_confirmation" {{ isset($user) ? '' : 'required' }}></div>
</div>
<div class="mt-4"><button class="btn btn-eco-primary" type="submit">{{ isset($user) ? 'Save Changes' : 'Create User' }}</button><a class="btn btn-eco-outline" href="{{ route('admin.users') }}">Cancel</a></div>
</form>
</div></div></div>
@endsection
