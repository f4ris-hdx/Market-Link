@extends('layouts.farmer')
@section('title', 'Farmer Orders — MarketLink')
@section('content')
<div class="page-hero-sub"><h1 class="h3 fw-bold">Incoming Pre-Orders</h1><p class="text-muted">Accept, reject, mark ready and complete pickup orders for your products.</p></div>
<div class="eco-card p-3 mt-4">
    @if($orders->isEmpty())
        <div class="text-center py-5 text-muted"><div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-receipt"></i></div><p class="mb-0">No orders containing your products.</p></div>
    @else
        <div class="table-responsive"><table class="table align-middle small"><thead><tr><th>Order</th><th>Customer</th><th>Pickup</th><th>Items</th><th>Total</th><th>Status</th><th>Action</th></tr></thead><tbody>
        @foreach($orders as $order)
            <tr>
                <td><strong>{{ $order->order_number }}</strong></td>
                <td>{{ $order->user?->name ?? 'Customer' }}</td>
                <td>{{ $order->slot }}<br><span class="text-muted">{{ optional($order->pickup_date)->format('M j, Y') }}</span></td>
                <td>@foreach($order->items as $item)@if($item->product && $item->product->farmer_id === $farmer->id){{ $item->qty }}x {{ $item->name }}@if(!$loop->last), @endif @endif @endforeach</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td><span class="badge bg-light text-dark">{{ $order->status }}</span></td>
                <td>
                    @if(in_array($order->status, ['Placed','Accepted','Ready'], true))
                        <form method="POST" action="{{ route('farmer.orders.update', $order) }}" class="d-flex gap-1">
                            @csrf
                            <select class="form-select form-select-sm" name="status"><option value="Accepted" @selected($order->status === 'Accepted')>Accepted</option><option value="Ready" @selected($order->status === 'Ready')>Ready</option><option value="Picked Up">Picked Up</option><option value="Rejected">Rejected</option></select>
                            <button class="btn btn-sm btn-eco-primary" type="submit">Update</button>
                        </form>
                    @else<span class="text-muted">Closed</span>@endif
                </td>
            </tr>
        @endforeach
        </tbody></table></div>
        <div class="mt-3">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
