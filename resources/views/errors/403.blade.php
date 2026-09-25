@extends('layouts.app')

@section('title', 'Access Restricted — MarketLink')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-lg-7">
        <div class="eco-card p-5 text-center">
            <div class="icon-chip mx-auto mb-3" style="width:70px;height:70px;font-size:1.5rem;"><i class="fa-solid fa-shield-halved"></i></div>
            <span class="eyebrow"><i class="fa-solid fa-circle-info"></i> Access Restricted</span>
            <h1 class="h3 fw-bold mt-2 mb-2">This area is not available yet</h1>
            <p class="text-muted mb-4">{{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}</p>
            @auth
                @if(auth()->user()->isFarmer())
                    <a class="btn btn-eco-primary" href="{{ route('farmer.dashboard') }}"><i class="fa-solid fa-tractor me-1"></i>Back to Farmer Portal</a>
                @elseif(auth()->user()->isAdmin())
                    <a class="btn btn-eco-primary" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-shield-halved me-1"></i>Back to Admin Portal</a>
                @else
                    <a class="btn btn-eco-primary" href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge-high me-1"></i>Back to Dashboard</a>
                @endif
            @else
                <a class="btn btn-eco-primary" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket me-1"></i>Sign In</a>
            @endauth
        </div>
    </div>
</div>
@endsection
