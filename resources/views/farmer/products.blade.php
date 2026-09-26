@extends('layouts.farmer')
@section('title', 'My Products — MarketLink')
@section('content')
<div class="page-hero-sub d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div><h1 class="h3 fw-bold">My Products</h1><p class="text-muted mb-0">Create, edit, remove and update stock for your own listings.</p></div>
    @if($farmer->status === 'verified')<a class="btn btn-eco-primary text-black" href="{{ route('farmer.products.create') }}"><i class="fa-solid fa-plus me-1 "></i>Add Product</a>@endif
</div>
@if($farmer->status !== 'verified')<div class="alert alert-warning mt-4"><i class="fa-solid fa-hourglass-half me-2"></i>Your farmer profile is awaiting administrator approval. Product publishing is disabled until verification.</div>@endif
<div class="eco-card p-3 mt-4">
    @if($products->isEmpty())
        <div class="text-center py-5 text-muted"><div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-seedling"></i></div><p class="mb-0">You do not have any products yet.</p></div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td><div class="d-flex align-items-center gap-2">@if($product->image)<img src="{{ $product->image }}" class="rounded" style="width:48px;height:48px;object-fit:cover" alt="">@else<div class="icon-chip"><i class="fa-solid fa-leaf"></i></div>@endif<div><strong>{{ $product->name }}</strong><div class="small text-muted">{{ \Illuminate\Support\Str::limit($product->description ?? '', 70) }}</div></div></div></td>
                        <td>{{ $product->category?->name ?? 'Uncategorized' }}</td>
                        <td>${{ number_format($product->price, 2) }} / {{ $product->unit }}</td>
                        <td>{{ $product->stock }}</td>
                        <td><span class="badge {{ $product->status === 'approved' && $product->stock > 0 ? 'bg-mint text-forest' : 'bg-secondary text-white' }}">{{ $product->status === 'approved' ? ($product->stock > 0 ? 'Visible' : 'Sold Out') : 'Hidden' }}</span></td>
                        <td><div class="d-flex gap-1"><a class="btn btn-sm btn-outline-secondary" href="{{ route('farmer.products.edit', $product) }}" title="Edit"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('farmer.products.visibility', $product) }}">@csrf<button class="btn btn-sm {{ $product->status === 'approved' ? 'btn-outline-warning' : 'btn-outline-success' }}" type="submit" title="{{ $product->status === 'approved' ? 'Hide from customers' : 'Show to customers' }}"><i class="fa-solid {{ $product->status === 'approved' ? 'fa-eye-slash' : 'fa-eye' }}"></i></button></form><form method="POST" action="{{ route('farmer.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button></form></div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
