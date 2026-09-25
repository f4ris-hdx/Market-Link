@extends('layouts.admin')
@section('title', 'Product Management — MarketLink')
@section('content')
<div class="portal-page-heading d-flex flex-wrap justify-content-between align-items-center gap-3"><div><span class="eyebrow"><i class="fa-solid fa-boxes-stacked"></i> Administration</span><h1 class="h3 fw-bold mt-2 mb-1">Product Management</h1><p class="text-muted mb-0">Create, view, edit and remove marketplace products.</p></div><div class="d-flex gap-2 flex-wrap"><form class="d-flex gap-2" method="GET"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search products"><button class="btn btn-eco-outline" type="submit">Search</button></form><a class="btn btn-eco-primary" href="{{ route('admin.products.create') }}"><i class="fa-solid fa-plus me-1"></i>Create</a></div></div>
<div class="eco-card p-3 mt-4">
@if($products->isEmpty())
<div class="text-center py-5 text-muted"><div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-boxes-stacked"></i></div><p class="mb-0">No products found.</p></div>
@else
<div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Product</th><th>Farmer</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead><tbody>
@foreach($products as $product)
<tr><td><strong>{{ $product->name }}</strong><div class="small text-muted">{{ \Illuminate\Support\Str::limit($product->description ?? '', 80) }}</div></td><td>{{ $product->farmer?->name ?? 'Unassigned' }}</td><td>{{ $product->category?->name ?? 'Uncategorized' }}</td><td>${{ number_format($product->price, 2) }} / {{ $product->unit }}</td><td>{{ $product->stock }}</td><td><div class="d-flex gap-1"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.products.edit', $product) }}"><i class="fa-solid fa-pen"></i></a><form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>
@endforeach
</tbody></table></div>
<div class="mt-3">{{ $products->links() }}</div>
@endif
</div>
@endsection
