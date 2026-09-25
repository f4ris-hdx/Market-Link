@extends('layouts.admin')
@section('title', isset($farmer) ? 'Edit Farmer — MarketLink' : 'Create Farmer — MarketLink')
@section('content')
<div class="portal-page-heading"><span class="eyebrow"><i class="fa-solid fa-tractor"></i> Farmer Management</span><h1 class="h3 fw-bold mt-2 mb-1">{{ isset($farmer) ? 'Edit Farmer' : 'Create Farmer' }}</h1><p class="text-muted mb-0">Manage farmer profile, market assignment, availability and account linkage.</p></div>
<div class="row justify-content-center mt-4"><div class="col-lg-9"><div class="eco-card p-4">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ isset($farmer) ? route('admin.farmers.update', $farmer) : route('admin.farmers.store') }}">
@csrf
@if(isset($farmer)) @method('PUT') @endif
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Linked farmer user</label><select class="form-select" name="user_id"><option value="">No linked user</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(old('user_id', $farmer->user_id ?? '') == $user->id)>{{ $user->name }} — {{ $user->email }}</option>@endforeach</select><div class="form-text">A linked user can sign in through the farmer portal.</div></div>
<div class="col-md-6"><label class="form-label fw-semibold">Farmer / stall name</label><input class="form-control" name="name" value="{{ old('name', $farmer->name ?? '') }}" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Owner / contact person</label><input class="form-control" name="owner_name" value="{{ old('owner_name', $farmer->owner_name ?? '') }}"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Location / address</label><input class="form-control" name="location" value="{{ old('location', $farmer->location ?? '') }}" required></div>
<div class="col-md-6"><label class="form-label fw-semibold">Specialty</label><input class="form-control" name="specialty" value="{{ old('specialty', $farmer->specialty ?? '') }}"></div>
<div class="col-md-3"><label class="form-label fw-semibold">Rating</label><input class="form-control" type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $farmer->rating ?? 5) }}"></div>
<div class="col-md-3"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" required>@foreach(['pending','verified','suspended'] as $status)<option value="{{ $status }}" @selected(old('status', $farmer->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Market</label><select class="form-select" name="market_id"><option value="">Unassigned</option>@foreach($markets as $market)<option value="{{ $market->id }}" @selected(old('market_id', $farmer->market_id ?? '') == $market->id)>{{ $market->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Image URL</label><input class="form-control" type="url" name="image" value="{{ old('image', $farmer->image ?? '') }}"></div>
<div class="col-12"><label class="form-label fw-semibold">Pickup slots</label><textarea class="form-control" name="slots" rows="3">{{ old('slots', $farmer->slots ?? '') }}</textarea></div>
</div>
<div class="mt-4"><button class="btn btn-eco-primary" type="submit">{{ isset($farmer) ? 'Save Changes' : 'Create Farmer' }}</button><a class="btn btn-eco-outline" href="{{ route('admin.farmers') }}">Cancel</a></div>
</form>
</div></div></div>
@endsection
