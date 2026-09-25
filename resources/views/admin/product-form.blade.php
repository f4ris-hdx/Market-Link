@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Product — MarketLink' : 'Create Product — MarketLink')
@section('content')
<div class="portal-page-heading"><span class="eyebrow"><i class="fa-solid fa-boxes-stacked"></i> Product Management</span><h1 class="h3 fw-bold mt-2 mb-1">{{ isset($product) ? 'Edit Product' : 'Create Product' }}</h1><p class="text-muted mb-0">Maintain accurate listing information, pricing and available stock.</p></div>
<div class="row justify-content-center mt-4"><div class="col-lg-9"><div class="eco-card p-4">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}">@csrf @if(isset($product)) @method('PUT') @endif
<div class="row g-3">
<div class="col-md-6"><label class="form-label fw-semibold">Verified farmer</label><select class="form-select" name="farmer_id" required><option value="">Choose farmer</option>@foreach($farmers as $farmer)<option value="{{ $farmer->id }}" @selected(old('farmer_id', $product->farmer_id ?? '') == $farmer->id)>{{ $farmer->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Product name</label><input class="form-control" name="name" value="{{ old('name', $product->name ?? '') }}" required></div>
<div class="col-12"><label class="form-label fw-semibold">Description</label><textarea class="form-control" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea></div>
<div class="col-md-6"><label class="form-label fw-semibold">Category</label><select class="form-select" name="category_id" required>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
<div class="col-md-3"><label class="form-label fw-semibold">Unit</label><input class="form-control" name="unit" value="{{ old('unit', $product->unit ?? '') }}" placeholder="kg, bunch, dozen" required></div>
<div class="col-md-3"><label class="form-label fw-semibold">Price</label><input class="form-control" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price ?? '') }}" required></div>
<div class="col-md-4"><label class="form-label fw-semibold">Stock</label><input class="form-control" type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required></div>
<div class="col-md-4"><label class="form-label fw-semibold">Rating</label><input class="form-control" type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $product->rating ?? 0) }}"></div>
<div class="col-md-4"><label class="form-label fw-semibold">Image URL</label><input class="form-control" type="url" name="image" value="{{ old('image', $product->image ?? '') }}"></div>
</div>
<div class="mt-4"><button class="btn btn-eco-primary" type="submit">{{ isset($product) ? 'Save Changes' : 'Create Product' }}</button><a class="btn btn-eco-outline" href="{{ route('admin.products') }}">Cancel</a></div>
</form>
</div></div></div>
@endsection
