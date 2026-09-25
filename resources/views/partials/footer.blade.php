<footer class="home-footer">
    <div class="footer-main">
        <div class="container">
            <div class="row g-4 m-0">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="brand-icon"><i class="fa-solid fa-wheat-awn"></i></span>
                        <span class="brand-name fs-4">Market<span class="text-warning">Link</span></span>
                    </div>
                    <p class="text-muted small mb-3">Farm-fresh pre-orders from verified local growers, collected by you at the market. Zero spoilage, fair prices, true taste.</p>
                    <div class="social-links">
                        <a href="#" title="Instagram"><i class="fa-brands fa-instagram text-black "></i></a>
                        <a href="#" title="Facebook"><i class="fa-brands fa-facebook-f  text-black "></i></a>
                        <a href="#" title="X"><i class="fa-brands fa-x-twitter  text-black"></i></a>
                        <a href="#" title="Newsletter"><i class="fa-solid fa-envelope  text-black"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold mb-3">Explore</h6>
                    <ul class="list-unstyled footer-links small text-black">
                        <li><a href="{{ route('products.index') }}" class="text-black">Products</a></li>
                        <li><a href="{{ route('markets.index') }}" class="text-black">Markets</a></li>
                        <li><a href="{{ route('farmers.index') }}" class="text-black">Farmers</a></li>
                        <li><a href="{{ route('about') }}" class="text-black">About Us</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold mb-3">Accounts</h6>
                    <ul class="list-unstyled footer-links small">
                        @if (auth()->user()->isFarmer())
                            <li><a href="{{ route('farmer.products') }}" class="text-black">My Product List</a></li>
                            <li><a href="{{ route('farmer.orders') }}" class="text-black">Incoming Orders</a></li>
                            <li><a href="{{ route('farmer.slots') }}" class="text-black">Pickup Slots</a></li>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <li><a href="{{ route('admin.farmers') }}" class="text-black">Verify Farmers</a></li>
                            <li><a href="{{ route('admin.products') }}" class="text-black">All Products</a></li>
                        @endif
                        <li><a href="{{ route('dashboard') }}" class="text-black">My Dashboard</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-black">My Basket</a></li>
                    </ul>
                </div>
                <div class="col-12 col-lg-3">
                    <h6 class="fw-bold mb-3">Stay Fresh</h6>
                    <p class="text-muted small mb-2">Harvest alerts and market news straight to your inbox.</p>
                    <form class="newsletter-form d-flex" onsubmit="return false;">
                        <input type="email" class="form-control form-control-sm me-2" placeholder="you@example.com" aria-label="Email">
                        <button class="btn btn-eco-primary btn-sm" type="button"><i class="fa-solid fa-paper-plane "></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="small text-muted">© <span id="currentYear">2026</span> MarketLink. Farm fresh, direct.</span>
            <span class="small text-muted"><i class="fa-solid fa-location-dot me-1"></i>Local markets · Verified growers</span>
        </div>
    </div>
</footer>