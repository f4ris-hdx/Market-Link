@extends('layouts.admin')
@section('title', 'User Management — MarketLink')
@section('content')
<div class="page-hero-sub d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div><h1 class="h3 fw-bold">User Management</h1><p class="text-muted mb-0">Create, view, edit, activate/deactivate and remove MarketLink accounts.</p></div>
    <a class="btn btn-eco-primary" href="{{ route('admin.users.create') }}"><i class="fa-solid fa-user-plus me-1"></i>Create User</a>
</div>

<form class="eco-card p-3 mt-4" method="GET">
    <div class="row g-2 align-items-end">
        <div class="col-md-5"><label class="form-label small fw-semibold">Search</label><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search name or email"></div>
        <div class="col-md-3"><label class="form-label small fw-semibold">Role</label><select class="form-select" name="role"><option value="">All roles</option>@foreach(['customer','farmer','admin'] as $role)<option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label small fw-semibold">Status</label><select class="form-select" name="status"><option value="">All statuses</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="inactive" @selected(request('status') === 'inactive')>Inactive</option></select></div>
        <div class="col-md-2"><button class="btn btn-eco-outline w-100" type="submit"><i class="fa-solid fa-filter me-1"></i>Filter</button></div>
    </div>
</form>

<div class="eco-card p-3 mt-3">
    @if($users->isEmpty())
        <div class="text-center py-5 text-muted"><div class="icon-chip mx-auto mb-3"><i class="fa-solid fa-users"></i></div><p class="mb-0">No users found.</p></div>
    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong><div class="small text-muted">{{ $user->phone }}</div></td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst($user->role) }}</span></td>
                        <td><span class="badge {{ ($user->status ?? 'active') === 'active' ? 'bg-mint text-forest' : 'bg-danger text-white' }}">{{ ucfirst($user->status ?? 'active') }}</span></td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.users.edit', $user) }}" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                @if(!$user->is(auth()->user()))
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">@csrf<button class="btn btn-sm btn-outline-secondary" type="submit">{{ ($user->status ?? 'active') === 'active' ? 'Disable' : 'Enable' }}</button></form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user and any linked farmer profile?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa-solid fa-trash"></i></button></form>
                                @else
                                    <span class="badge bg-light text-secondary align-self-center">Current account</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    @endif
</div>
@endsection
