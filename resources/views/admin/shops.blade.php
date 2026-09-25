@extends('layouts.portal')
@section('title', 'Shop & Stall Management — MarketLink')
@section('content')
<div class="page-hero-sub d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div><h1 class="h3 fw-bold mb-1">Shop & Stall Management</h1><p class="text-muted mb-0">Create the approved shop/stall records that farmers can select during registration and profile updates.</p></div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-5">
        <div class="eco-card p-4">
            <h5 class="fw-bold mb-3">Add Shop / Stall</h5>
            <form method="POST" action="{{ route('admin.shops.store') }}">
                @csrf
                <div class="mb-3"><label class="form-label fw-semibold">Market</label><select class="form-select" name="market_id" required><option value="">Select market</option>@foreach($markets as $market)<option value="{{ $market->id }}">{{ $market->name }}</option>@endforeach</select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Shop / stall name</label><input class="form-control" name="name" placeholder="Green Valley Farm" required></div>
                <div class="mb-3"><label class="form-label fw-semibold">Stall location</label><input class="form-control" name="location" placeholder="Stall A1, Downtown Plaza"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Status</label><select class="form-select" name="status" required><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                <button class="btn btn-eco-primary"><i class="fa-solid fa-plus me-1"></i>Create Shop</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="eco-card p-3">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Shop / Stall</th><th>Market</th><th>Location</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @forelse($shops as $shop)
                        <tr>
                            <td><strong>{{ $shop->name }}</strong></td>
                            <td>{{ $shop->market?->name }}</td>
                            <td>{{ $shop->location ?: $shop->market?->location }}</td>
                            <td><span class="badge {{ $shop->status === 'active' ? 'bg-mint text-forest' : 'bg-secondary text-white' }}">{{ ucfirst($shop->status) }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editShop{{ $shop->id }}"><i class="fa-solid fa-pen"></i></button>
                                <form class="d-inline" method="POST" action="{{ route('admin.shops.destroy', $shop) }}" onsubmit="return confirm('Delete this shop/stall?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form>
                                <div class="modal fade" id="editShop{{ $shop->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="{{ route('admin.shops.update', $shop) }}">@csrf @method('PUT')<div class="modal-header"><h5 class="modal-title">Edit {{ $shop->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="mb-3"><label class="form-label">Market</label><select class="form-select" name="market_id" required>@foreach($markets as $market)<option value="{{ $market->id }}" @selected($shop->market_id===$market->id)>{{ $market->name }}</option>@endforeach</select></div><div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" value="{{ $shop->name }}" required></div><div class="mb-3"><label class="form-label">Location</label><input class="form-control" name="location" value="{{ $shop->location }}"></div><div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status"><option value="active" @selected($shop->status==='active')>Active</option><option value="inactive" @selected($shop->status==='inactive')>Inactive</option></select></div></div><div class="modal-footer"><button class="btn btn-eco-primary">Save Changes</button></div></form></div></div></div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No shops/stalls created yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pt-2">{{ $shops->links() }}</div>
        </div>
    </div>
</div>
@endsection
