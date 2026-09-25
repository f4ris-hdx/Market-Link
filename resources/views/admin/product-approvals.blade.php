@extends('layouts.portal')
@section('title', 'Product Approvals — MarketLink')
@section('content')
<div class="page-hero-sub d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div><h1 class="h3 fw-bold mb-1">Product Approvals</h1><p class="text-muted mb-0">Review farmer product submissions before they become visible to customers.</p></div>
    <a class="btn btn-eco-outline" href="{{ route('admin.products') }}"><i class="fa-solid fa-boxes-stacked me-1"></i>All Products</a>
</div>

<div class="eco-card p-3 mt-4">
    @forelse($products as $product)
        <div class="border-bottom py-4">
            <div class="row g-3 align-items-start">
                <div class="col-lg-5">
                    <div class="d-flex gap-3">
                        @if($product->image)<img src="{{ $product->image }}" style="width:84px;height:84px;object-fit:cover" class="rounded-3" alt="{{ $product->name }}">@endif
                        <div><h5 class="fw-bold mb-1">{{ $product->name }}</h5><div class="small text-muted mb-1">Farmer: {{ $product->farmer?->name ?? 'Unassigned' }}</div><div class="small text-muted">Category: {{ $product->category?->name ?? 'Other' }} · ${{ number_format($product->price,2) }} / {{ $product->unit }} · Stock {{ $product->stock }}</div></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="small text-uppercase fw-bold text-muted mb-2">Requested markets</div>
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($product->markets as $market)<span class="badge rounded-pill bg-light text-dark border">{{ $market->name }}</span>@empty<span class="small text-muted">No market selected.</span>@endforelse
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('admin.products.approve', $product) }}">@csrf<button class="btn btn-eco-primary w-100"><i class="fa-solid fa-circle-check me-1"></i>Approve Product</button></form>
                        <form method="POST" action="{{ route('admin.products.reject', $product) }}">@csrf<div class="input-group"><input class="form-control" name="reason" maxlength="500" placeholder="Reason (optional)"><button class="btn btn-outline-danger" type="submit"><i class="fa-solid fa-xmark me-1"></i>Reject</button></div></form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5"><div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-circle-check"></i></div><h5 class="fw-bold">No pending products</h5><p class="text-muted mb-0">New farmer products will appear here for review.</p></div>
    @endforelse
    <div class="pt-3">{{ $products->links() }}</div>
</div>
@endsection
