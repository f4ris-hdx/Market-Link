@extends('layouts.farmer')
@section('title', 'Pickup Slots — MarketLink')
@section('content')
<div class="page-hero-sub"><h1 class="h3 fw-bold">Pickup Slots</h1><p class="text-muted">Tell customers when they can collect pre-orders from your stall.</p></div>
<div class="row justify-content-center mt-3"><div class="col-lg-8"><div class="eco-card p-4">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('farmer.slots.update') }}">@csrf<label class="form-label fw-semibold">Available pickup windows</label><textarea class="form-control mb-3" name="slots" rows="6" placeholder="Saturday: 8:00 AM - 1:00 PM&#10;Sunday: 9:00 AM - 2:00 PM">{{ old('slots', $farmer->slots) }}</textarea><button class="btn btn-eco-primary" type="submit"><i class="fa-solid fa-floppy-disk me-1"></i>Save Pickup Slots</button></form>
</div></div></div>
@endsection
