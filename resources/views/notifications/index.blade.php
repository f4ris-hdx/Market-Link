@extends('layouts.portal')
@section('title', 'Notifications — MarketLink')
@section('content')
<div class="page-hero-sub d-flex justify-content-between align-items-center gap-3 flex-wrap">
    <div>
        <h1 class="h3 fw-bold mb-1">Notifications</h1>
        <p class="text-muted mb-0">Updates about your MarketLink account and product approvals.</p>
    </div>
    <a class="btn btn-eco-outline" href="{{ route('farmer.dashboard') }}"><i class="fa-solid fa-arrow-left me-1"></i>Back to Workspace</a>
</div>
<div class="eco-card p-3 mt-4">
    @forelse($notifications as $notification)
        @php($data = $notification->data)
        <div class="d-flex align-items-start gap-3 border-bottom py-3 {{ $notification->read_at ? 'opacity-75' : '' }}">
            <div class="icon-chip flex-shrink-0"><i class="fa-solid {{ ($data['status'] ?? '') === 'approved' ? 'fa-circle-check' : 'fa-circle-exclamation' }}"></i></div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <strong>{{ $data['message'] ?? 'You have a new MarketLink update.' }}</strong>
                        @if(!empty($data['product_name']))<div class="small text-muted mt-1">Product: {{ $data['product_name'] }}</div>@endif
                    </div>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notification->read_at)
                    <form class="mt-2" method="POST" action="{{ route('notifications.read', $notification) }}">@csrf<button class="btn btn-sm btn-eco-outline">Mark as read</button></form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">No notifications yet.</div>
    @endforelse
    <div class="pt-3">{{ $notifications->links() }}</div>
</div>
@endsection
