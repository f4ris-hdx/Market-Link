@extends('layouts.farmer')
@section('title', isset($product) ? 'Edit Product — MarketLink' : 'Add Product — MarketLink')
@section('content')
<div class="page-hero-sub text-black"><h1 class="h3 fw-bold">{{ isset($product) ? 'Edit Product' : 'Add Product' }}</h1><p class="text-muted">Keep your marketplace listing accurate and current.</p></div>
<div class="row justify-content-center mt-3">
    <div class="col-lg-9">
        <div class="eco-card p-4">
            @if($errors->any())<div class="alert alert-danger"><strong>Please fix these fields:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ isset($product) ? route('farmer.products.update', $product) : route('farmer.products.store') }}">
                @csrf
                @if(isset($product)) @method('PUT') @endif
                <div class="mb-3"><label class="form-label fw-semibold">Product name</label><input class="form-control" name="name" value="{{ old('name', $product->name ?? '') }}" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Description</label><textarea class="form-control" name="description" rows="4">{{ old('description', $product->description ?? '') }}</textarea></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold" for="productMarket">Market</label><select class="form-select @error('market_id') is-invalid @enderror" id="productMarket" name="market_id" required><option value="">Choose market</option>@foreach($markets as $market)<option value="{{ $market->id }}" @selected(old('market_id', $selectedMarketId ?? '') == $market->id)>{{ $market->name }} — {{ $market->location }}</option>@endforeach</select>@error('market_id')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Category</label><select class="form-select" name="category_id" required>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Unit</label><input class="form-control" name="unit" value="{{ old('unit', $product->unit ?? '') }}" placeholder="kg, bunch, dozen" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Price</label><input class="form-control" type="number" step="0.01" min="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Stock quantity</label><input class="form-control" type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required></div>
                </div>
                <div class="mt-3 mb-4"><label class="form-label fw-semibold">Image URL</label><input class="form-control" type="url" name="image" value="{{ old('image', $product->image ?? '') }}"></div>
                <button class="btn btn-eco-primary" type="submit"><i class="fa-solid fa-floppy-disk me-1"></i>{{ isset($product) ? 'Save Changes' : 'Publish Product' }}</button>
                <a class="btn btn-eco-outline" href="{{ route('farmer.products') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
