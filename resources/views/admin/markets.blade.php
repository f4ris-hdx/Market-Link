@extends('layouts.admin')
@section('title', 'Market Management — MarketLink')
@section('content')
<div class="portal-page-heading"><span class="eyebrow"><i class="fa-solid fa-map-location-dot"></i> Administration</span><h1 class="h3 fw-bold mt-2 mb-1">Market Management</h1><p class="text-muted mb-0">Create, edit and remove local pickup market hubs.</p></div>
<div class="row g-4 mt-2">
<div class="col-lg-5"><div class="eco-card p-4"><h5 class="fw-bold mb-3">Add Market</h5><form id="marketCreateForm" method="POST" action="{{ route('admin.markets.store') }}">@csrf @include('admin.market-fields')<button class="btn btn-eco-primary" type="submit"><i class="fa-solid fa-plus me-1"></i>Create Market</button></form></div></div>
<div class="col-lg-7"><div class="eco-card p-3">
@if($markets->isEmpty())<div class="text-center py-5 text-muted">No markets found.</div>@else
<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Market</th><th>Days</th><th>Vendors</th><th>Actions</th></tr></thead><tbody>
@foreach($markets as $market)
<tr><td><strong>{{ $market->name }}</strong><div class="small text-muted">{{ $market->location }}</div></td><td>{{ $market->days }}</td><td>{{ $market->farmers_count }}</td><td><div class="d-flex gap-1"><button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editMarket{{ $market->id }}"><i class="fa-solid fa-pen"></i></button><form method="POST" action="{{ route('admin.markets.destroy', $market) }}" onsubmit="return confirm('Remove this market?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa-solid fa-trash"></i></button></form></div></td></tr>
@endforeach
</tbody></table></div>
@endif
</div></div></div>

@foreach($markets as $market)
<div class="modal fade" id="editMarket{{ $market->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit {{ $market->name }}</h5><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div><form method="POST" action="{{ route('admin.markets.update', $market) }}">@csrf @method('PUT')<div class="modal-body"><div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" value="{{ $market->name }}" required></div><div class="mb-3"><label class="form-label">Location / address</label><input class="form-control" name="location" value="{{ $market->location }}" required></div><div class="mb-3"><label class="form-label">Pin location on map</label><div class="market-picker" data-market-map="marketMap{{ $market->id }}" data-default-lat="{{ $market->latitude ?? 30.3753 }}" data-default-lng="{{ $market->longitude ?? 69.3451 }}"></div><input type="hidden" name="latitude" data-market-lat value="{{ $market->latitude }}"><input type="hidden" name="longitude" data-market-lng value="{{ $market->longitude }}"><div class="form-text">Click the map to update the exact coordinates.</div></div><div class="mb-3"><label class="form-label">Days & times</label><input class="form-control" name="days" value="{{ $market->days }}" required></div><div class="row g-2"><div class="col-6"><label class="form-label">Vendor count</label><input class="form-control" type="number" min="0" name="farmers_count" value="{{ $market->farmers_count }}"></div><div class="col-6"><label class="form-label">Distance</label><input class="form-control" type="number" step="0.1" min="0" name="distance" value="{{ $market->distance }}"></div></div></div><div class="modal-footer"><button class="btn btn-eco-outline" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-eco-primary" type="submit">Save Changes</button></div></form></div></div></div>
@endforeach
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>.market-picker{height:260px;border-radius:12px;overflow:hidden;border:1px solid #dfe7e2}.market-picker .leaflet-container{height:100%;width:100%}</style>
@endpush
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('[data-market-map]').forEach(function (container) {
		const latField = container.parentElement.querySelector('[data-market-lat]');
		const lngField = container.parentElement.querySelector('[data-market-lng]');
		const lat = parseFloat(latField.value || container.dataset.defaultLat);
		const lng = parseFloat(lngField.value || container.dataset.defaultLng);
		const map = L.map(container).setView([lat, lng], latField.value ? 14 : 5);
		L.tileLayer(@json(config('services.maps.tile_url')), { attribution: @json(config('services.maps.attribution')), maxZoom: 19 }).addTo(map);
		let marker = latField.value ? L.marker([lat, lng]).addTo(map) : null;
		map.on('click', function (event) {
			const coordinates = event.latlng;
			latField.value = coordinates.lat.toFixed(7);
			lngField.value = coordinates.lng.toFixed(7);
			if (marker) marker.setLatLng(coordinates); else marker = L.marker(coordinates).addTo(map);
			const locationField = container.closest('form')?.querySelector('input[name="location"]');
			if (locationField) {
				locationField.placeholder = 'Finding address...';
				fetch(@json(config('services.maps.reverse_geocoder_url')) + '?format=jsonv2&lat=' + encodeURIComponent(coordinates.lat) + '&lon=' + encodeURIComponent(coordinates.lng) + '&zoom=18&addressdetails=1', { headers: { Accept: 'application/json' } })
					.then(response => response.ok ? response.json() : Promise.reject(new Error('Reverse geocoding failed.')))
					.then(result => {
						if (result.display_name) locationField.value = result.display_name;
						locationField.placeholder = 'Location / address';
					})
					.catch(() => { locationField.placeholder = 'Enter location / address manually'; });
			}
		});
		const modal = container.closest('.modal');
		modal?.addEventListener('shown.bs.modal', function () { setTimeout(() => map.invalidateSize(), 100); });
	});
});
</script>
@endpush
@endsection
