@extends('layouts.admin')
@section('title', 'Order Management — MarketLink')
@section('content')
<div class="portal-page-heading d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <span class="eyebrow"><i class="fa-solid fa-receipt"></i> Administration</span>
        <h1 class="h3 fw-bold mt-2 mb-1">Order Management</h1>
        <p class="text-muted mb-0">Review complete customer orders and filter by market, status, or farmer.</p>
    </div>
    <span class="badge bg-mint text-forest">{{ number_format($orders->total()) }} orders</span>
</div>

<form method="GET" action="{{ route('admin.orders') }}" class="eco-card p-3 mt-4 mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold" for="orderSearch">Search</label>
            <input id="orderSearch" class="form-control" name="search" value="{{ request('search') }}" placeholder="Order, customer, email">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold" for="orderMarket">Market</label>
            <select id="orderMarket" class="form-select" name="market_id">
                <option value="">All markets</option>
                @foreach($markets as $market)
                    <option value="{{ $market->id }}" @selected((string) request('market_id') === (string) $market->id)>{{ $market->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold" for="orderStatus">Status</label>
            <select id="orderStatus" class="form-select" name="status">
                <option value="">All statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold" for="orderFarmer">Farmer</label>
            <select id="orderFarmer" class="form-select" name="farmer_id">
                <option value="">All farmers</option>
                @foreach($farmers as $farmer)
                    <option value="{{ $farmer->id }}" @selected((string) request('farmer_id') === (string) $farmer->id)>{{ $farmer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-eco-primary flex-fill" type="submit"><i class="fa-solid fa-filter me-1"></i>Filter</button>
            <a class="btn btn-eco-outline" href="{{ route('admin.orders') }}" title="Clear filters"><i class="fa-solid fa-rotate-left"></i></a>
        </div>
    </div>
</form>

<div class="eco-card p-3">
    @if($orders->isEmpty())
        <div class="text-center py-5 text-muted"><div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-receipt"></i></div><p class="mb-0">No orders match these filters.</p></div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Order</th><th>Customer</th><th>Market &amp; pickup</th><th>Items &amp; farmers</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong><div class="small text-muted">Placed {{ optional($order->placed_at)->format('M j, Y') }}</div></td>
                        <td>{{ $order->user?->name ?? $order->pickup_name }}<div class="small text-muted">{{ $order->user?->email }}</div><div class="small text-muted">{{ $order->pickup_phone }}</div></td>
                        <td>{{ $order->market?->name ?? 'Market not set' }}<div class="small text-muted">{{ $order->market?->location }}</div><div class="small">{{ $order->slot }}@if($order->pickup_date) · {{ $order->pickup_date->format('M j, Y') }}@endif</div></td>
                        <td>
                            @foreach($order->items as $item)
                                <div class="small"><strong>{{ $item->qty }}x {{ $item->name }}</strong> · ${{ number_format($item->price, 2) }} / {{ $item->unit }}<div class="text-muted">{{ $item->product?->farmer?->name ?? 'Farmer unavailable' }}</div></div>
                            @endforeach
                        </td>
                        <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                        <td><span class="badge {{ in_array($order->status, ['Picked Up', 'Accepted', 'Ready'], true) ? 'bg-mint text-forest' : (in_array($order->status, ['Cancelled', 'Rejected'], true) ? 'bg-danger text-white' : 'bg-warning text-dark') }}">{{ $order->status }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    @endif
</div>
@endsection