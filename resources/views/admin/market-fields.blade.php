<div class="mb-3"><label class="form-label small fw-semibold">Market name</label><input class="form-control" name="name" required></div>
<div class="mb-3"><label class="form-label small fw-semibold">Location / address</label><input class="form-control" name="location" required></div>
<div class="mb-3">
	<label class="form-label small fw-semibold">Pin location on map <span class="text-muted fw-normal">(optional)</span></label>
	<div class="market-picker" data-market-map="marketMapCreate" data-market-form="marketCreateForm" data-default-lat="30.3753" data-default-lng="69.3451"></div>
	<input type="hidden" name="latitude" data-market-lat value="">
	<input type="hidden" name="longitude" data-market-lng value="">
	<div class="form-text">Click the map to save exact coordinates. You can still use the address field above manually.</div>
</div>
<div class="mb-3"><label class="form-label small fw-semibold">Days & times</label><input class="form-control" name="days" placeholder="Saturdays, 8 AM - 1 PM" required></div>
<div class="row g-2 mb-3"><div class="col-6"><label class="form-label small fw-semibold">Vendor count</label><input class="form-control" type="number" min="0" name="farmers_count"></div><div class="col-6"><label class="form-label small fw-semibold">Distance</label><input class="form-control" type="number" step="0.1" min="0" name="distance"></div></div>
