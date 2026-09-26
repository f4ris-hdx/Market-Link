@extends('layouts.admin')
@section('title', 'Farmer Management — MarketLink')
@section('content')
<div class="portal-page-heading d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div><span class="eyebrow"><i class="fa-solid fa-tractor"></i> Administration</span><h1 class="h3 fw-bold mt-2 mb-1">Farmer Management</h1><p class="text-muted mb-0">Create, view, edit, verify, suspend and remove farmer profiles.</p></div>
    <a class="btn btn-eco-primary" href="{{ route('admin.farmers.create') }}"><i class="fa-solid fa-user-plus me-1"></i>Create Farmer</a>
</div>
<form method="GET" class="eco-card p-3 mt-4"><div class="row g-2 align-items-end"><div class="col-md-5"><label class="form-label small fw-semibold">Profile source</label><select name="type" class="form-select"><option value="">All profiles</option><option value="registered" @selected(($type ?? '') === 'registered')>Registered accounts</option><option value="sample" @selected(($type ?? '') === 'sample')>Sample/demo data</option></select></div><div class="col-md-4"><label class="form-label small fw-semibold">Status</label><select name="status" class="form-select"><option value="">All statuses</option><option value="pending" @selected(request('status') === 'pending')>Pending</option><option value="verified" @selected(request('status') === 'verified')>Verified</option><option value="suspended" @selected(request('status') === 'suspended')>Suspended</option></select></div><div class="col-md-3"><button class="btn btn-eco-outline w-100" type="submit"><i class="fa-solid fa-filter me-1"></i>Apply Filters</button></div></div></form>
<div class="eco-card p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div><h4 class="fw-bold mb-1"><i class="fa-solid fa-map-location-dot text-fresh me-2"></i>Market change requests</h4><p class="small text-muted mb-0">Review farmer requests before changing their assigned market.</p></div>
        <span class="badge bg-warning text-dark">{{ $marketChangeRequests->count() }} pending</span>
    </div>
    @if($marketChangeRequests->isEmpty())
        <p class="small text-muted mb-0">There are no pending market change requests.</p>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Farmer</th><th>Current market</th><th>Requested market</th><th>Requested by</th><th></th></tr></thead>
                <tbody>
                @foreach($marketChangeRequests as $marketRequest)
                    <tr>
                        <td><strong>{{ $marketRequest->farmer?->name ?? 'Farmer unavailable' }}</strong><div class="small text-muted">{{ $marketRequest->farmer?->owner_name }}</div></td>
                        <td>{{ $marketRequest->currentMarket?->name ?? 'Not assigned' }}</td>
                        <td>{{ $marketRequest->requestedMarket?->name ?? 'Market unavailable' }}<div class="small text-muted">{{ $marketRequest->requestedMarket?->location }}</div></td>
                        <td>{{ $marketRequest->requester?->name ?? 'Farmer' }}<div class="small text-muted">{{ $marketRequest->created_at->format('M j, Y g:i A') }}</div></td>
                        <td><div class="d-flex gap-2"><form method="POST" action="{{ route('admin.farmer-market-change-requests.approve', $marketRequest) }}">@csrf<button class="btn btn-sm btn-eco-primary" type="submit"><i class="fa-solid fa-check me-1"></i>Approve</button></form><form method="POST" action="{{ route('admin.farmer-market-change-requests.reject', $marketRequest) }}">@csrf<button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa-solid fa-xmark me-1"></i>Reject</button></form></div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
<div class="eco-card p-3 mt-3">
@if($farmers->isEmpty())
    <div class="text-center py-5 text-muted"><div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-tractor"></i></div><p class="mb-0">No farmer profiles found.</p></div>
@else
    <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Farmer</th><th>Owner</th><th>Market</th><th>Products</th><th>Status</th><th>Source</th><th>Actions</th></tr></thead><tbody>
    @foreach($farmers as $farmer)
        <tr>
            <td><strong>{{ $farmer->name }}</strong><div class="small text-muted">{{ $farmer->location }}</div></td>
            <td>{{ $farmer->owner_name ?? '—' }}</td>
            <td>{{ $farmer->market?->name ?? 'Unassigned' }}</td>
            <td>{{ $farmer->products_count }}</td>
            <td><span class="badge {{ $farmer->status === 'verified' ? 'bg-mint text-forest' : ($farmer->status === 'suspended' ? 'bg-danger text-white' : 'bg-warning text-dark') }}">{{ ucfirst($farmer->status) }}</span></td>
            <td><span class="badge {{ $farmer->is_demo ? 'bg-light text-secondary' : 'bg-mint text-forest' }}">{{ $farmer->is_demo ? 'Sample data' : 'Registered' }}</span></td>
            <td><div class="d-flex flex-wrap gap-1"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.farmers.edit', $farmer) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>@if($farmer->status !== 'verified')<form method="POST" action="{{ route('admin.farmers.verify', $farmer) }}">@csrf<button class="btn btn-sm btn-outline-success" type="submit" title="Verify"><i class="fa-solid fa-check"></i></button></form>@endif @if($farmer->status !== 'suspended')<form method="POST" action="{{ route('admin.farmers.suspend', $farmer) }}">@csrf<button class="btn btn-sm btn-outline-danger" type="submit" title="Suspend"><i class="fa-solid fa-ban"></i></button></form>@endif<form method="POST" action="{{ route('admin.farmers.delete', $farmer) }}" onsubmit="return confirm('Delete this farmer profile and its products?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" title="Delete"><i class="fa-solid fa-trash"></i></button></form></div></td>
        </tr>
    @endforeach
    </tbody></table></div>
@endif
</div>
@endsection
