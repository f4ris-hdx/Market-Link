<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MarketLink | Eco-Fresh Farmers Market Platform</title>
    <meta name="description" content="Reserve weekly farm-fresh produce directly from verified local farmers and pick up at your neighborhood market.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body data-role="{{ auth()->check() ? auth()->user()->role : 'customer' }}">

    <!-- Toast Stack -->
    <div id="toastStack" class="toast-stack"></div>

    <!-- ===================== GLOBAL NAVBAR ===================== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-market sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}">
                <span class="brand-logo"><i class="fa-solid fa-basket-shopping"></i></span>
                <span class="fs-4 text-white">Market<span class="text-warning">Link</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('markets.index') }}">Markets</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('farmers.index') }}">Farmers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('how-it-works') }}">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2 flex-wrap nav-actions">
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle rounded-pill btn-sm px-3" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-user-gear me-1"></i>
                            @auth
                                {{ ucfirst(auth()->user()->role) }}: <span id="currentRoleLabel" class="fw-bold text-warning">{{ auth()->user()->name }}</span>
                            @else
                                <span id="currentRoleLabel" class="fw-bold text-warning">Guest</span>
                            @endauth
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            @auth
                                <li><h6 class="dropdown-header">Your MarketLink</h6></li>
                                <li><a class="dropdown-item" href="{{ auth()->user()->isFarmer() ? route('farmer.dashboard') : (auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard')) }}"><i class="fa-solid fa-gauge-high text-success me-2"></i>{{ auth()->user()->isFarmer() ? 'Farmer Workspace' : (auth()->user()->isAdmin() ? 'Admin Portal' : 'My Dashboard') }}</a></li>
                                @if(auth()->user()->isFarmer())
                                    <li><a class="dropdown-item" href="{{ route('farmer.dashboard') }}"><i class="fa-solid fa-tractor text-primary me-2"></i>Farmer Workspace</a></li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-shield text-danger me-2"></i>Admin Portal</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item text-danger" type="submit"><i class="fa-solid fa-right-from-bracket me-2"></i>Sign Out</button></form></li>
                            @else
                                <li><h6 class="dropdown-header">MarketLink Account</h6></li>
                                <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket text-success me-2"></i>Sign In</a></li>
                                <li><a class="dropdown-item" href="{{ route('register') }}"><i class="fa-solid fa-user-plus text-primary me-2"></i>Create Account</a></li>
                            @endauth
                        </ul>
                    </div>

                    <button class="nav-icon-btn id-customer-action position-relative" onclick="openCartModal()" title="Shopping cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span id="cartBadge" class="cart-badge">0</span>
                    </button>

                    <button class="nav-icon-btn id-customer-action" onclick="openNotificationsModal()" title="Notifications">
                        <i class="fa-regular fa-bell"></i>
                    </button>

                    <a class="btn btn-eco-primary btn-sm px-3 id-customer-action my-portal-btn" href="{{ auth()->check() ? (auth()->user()->isFarmer() ? route('farmer.dashboard') : (auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard'))) : route('login') }}">
                        <i class="fa-solid fa-user-circle me-1"></i> My Portal
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===================== HERO ===================== -->
    <section class="hero-section">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="hero-badge mb-4"><i class="fa-solid fa-seedling"></i> Direct Local Harvest</span>
                    <h1 class="hero-title mb-3">Fresh From Local Farmers,<br><span class="text-warning">Directly to You</span></h1>
                    <p class="hero-lead mb-4">Reserve weekly farm-fresh vegetables, dairy, and artisanal goods directly from trusted local farmers. Pick up conveniently at your neighborhood market.</p>

                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a class="btn btn-gold btn-lg px-4" href="{{ route('markets.index') }}">
                            <i class="fa-solid fa-location-dot me-2"></i>Explore Markets
                        </a>
                        <a class="btn btn-ghost-light btn-lg px-4" href="{{ route('products.index') }}">
                            Browse Fresh Produce
                        </a>
                    </div>

                    <div class="glass-panel p-2 m-0 me-lg-5 hero-search-row">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-6">
                                <input type="text" id="homeSearchInput" class="form-control border-0 shadow-none" placeholder="Search products, farmers or markets...">
                            </div>
                            <div class="col-md-4">
                                <select id="homeCategorySelect" class="form-select border-0 shadow-none">
                                    <option value="">All Categories</option>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Fruits">Fruits</option>
                                    <option value="Dairy &amp; Eggs">Dairy &amp; Eggs</option>
                                    <option value="Baked Goods">Baked Goods</option>
                                    <option value="Herbs &amp; Spices">Herbs &amp; Spices</option>
                                    <option value="Honey &amp; Preserves">Honey &amp; Preserves</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-eco-primary w-100 hero-search-button" onclick="executeHomeSearch()">
                                    <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="hero-stats">
                        <div class="hero-stat"><b id="heroStatFarmers">0</b><span>Verified Farmers</span></div>
                        <div class="hero-stat"><b id="heroStatMarkets">0</b><span>Weekly Markets</span></div>
                        <div class="hero-stat"><b id="heroStatProducts">0</b><span>Fresh Products</span></div>
                        <div class="hero-stat"><b id="heroStatCustomers">0</b><span>Happy Customers</span></div>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block hero-visual">
                    <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=800&q=80" alt="Fresh produce basket" class="img-fluid">
                    <div class="hero-float-card">
                        <div class="icon-chip" style="background:var(--soft-mint);color:var(--fresh-green);"><i class="fa-solid fa-carrot"></i></div>
                        <div>
                            <b class="d-block text-forest">Zero Food Waste</b>
                            <small class="text-muted">Harvested only after you order</small>
                        </div>
                    </div>
                    <div class="hero-float-chip">
                        <i class="fa-solid fa-star text-warning"></i>
                        <div><b class="d-block text-forest" style="font-size:.9rem;">4.9/5</b><small class="text-muted">1,200+ reviews</small></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== TRUST STRIP ===================== -->
    <section class="trust-strip">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fa-solid fa-certificate"></i></div>
                        <div><h6>100% Verified Farmers</h6><p>Every stall owner identity-checked</p></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" style="transition-delay:.06s">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                        <div><h6>No Hidden Platform Fees</h6><p>Pay the farmer directly at pickup</p></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" style="transition-delay:.12s">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fa-solid fa-leaf"></i></div>
                        <div><h6>Zero Food Waste</h6><p>Harvest happens only after you order</p></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal" style="transition-delay:.18s">
                    <div class="trust-item">
                        <div class="trust-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        <div><h6>Queue-Free Pickup</h6><p>Reserve a slot, skip the morning rush</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== CATEGORIES ===================== -->
    <section class="container py-5 reveal">
        <div class="section-lead">
            <div>
                <span class="eyebrow"><i class="fa-solid fa-shapes"></i> Categories</span>
                <h2 class="section-title mt-2">Explore Farm Fresh Categories</h2>
                <p class="section-sub">Six curated categories, hand-picked from local eco-farms every week.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-eco-outline btn-sm">View All <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-3" id="categoriesGrid"></div>
    </section>

    <!-- ===================== FEATURED PRODUCTS ===================== -->
    <section class="container pb-5 reveal">
        <div class="section-lead">
            <div>
                <span class="eyebrow"><i class="fa-solid fa-seedling"></i> Weekly Harvest</span>
                <h2 class="section-title mt-2">Fresh Products This Week</h2>
                <p class="section-sub">Pre-order now and skip the morning rush at the market.</p>
            </div>
            <a class="btn btn-eco-outline btn-sm" href="{{ route('products.index') }}">Marketplace Grid</a>
        </div>
        <div class="row g-4" id="featuredProductsGrid"></div>
    </section>

    <!-- ===================== MARKETS + MAP ===================== -->
    <section class="py-5 my-2 reveal" style="background:linear-gradient(180deg, var(--cream-bg), #eaf2ec);">
        <div class="container">
            <div class="section-lead mb-4">
                <div>
                    <span class="eyebrow"><i class="fa-solid fa-location-dot"></i> Locations</span>
                    <h2 class="section-title mt-2">Nearby Farmers Markets</h2>
                </div>
                <a class="btn btn-eco-primary" href="{{ route('markets.index') }}">All Markets &amp; Schedules</a>
            </div>
            <div class="row g-4">
                <div class="col-lg-5" id="homeMarketsList"></div>
                <div class="col-lg-7">
                    <div class="map-placeholder p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-forest text-white px-3 py-2"><i class="fa-solid fa-map-pin me-1"></i> Interactive Map Preview</span>
                            <small class="text-muted">Click markers to inspect market slots</small>
                        </div>
                        <div class="map-pin" style="top:30%; left:25%;" onclick="showMarketMapDetail('Central Farmers Hub')" title="Central Farmers Hub">
                            <i class="fa-solid fa-location-dot text-danger"></i>
                        </div>
                        <div class="map-pin" style="top:60%; left:70%;" onclick="showMarketMapDetail('Green Valley Eco Market')" title="Green Valley Eco Market">
                            <i class="fa-solid fa-location-dot text-success"></i>
                        </div>
                        <div class="map-pin" style="top:44%; left:50%;" onclick="showMarketMapDetail('West End Organics Fair')" title="West End Organics Fair">
                            <i class="fa-solid fa-location-dot text-warning"></i>
                        </div>
                        <div class="position-absolute bottom-0 start-0 m-3 glass-panel p-3 shadow" id="mapDetailBox" style="max-width:290px; display:none;">
                            <h6 class="fw-bold mb-1 text-forest" id="mapDetailTitle">Market Selected</h6>
                            <p class="small text-muted mb-2" id="mapDetailSub">Address and timings</p>
                            <a class="btn btn-sm btn-eco-primary w-100" href="{{ route('markets.index') }}">View Market Stall</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== HOW IT WORKS ===================== -->
    <section class="container py-5 reveal">
        <div class="text-center mb-5">
            <span class="eyebrow"><i class="fa-solid fa-circle-check"></i> Simple Steps</span>
            <h2 class="section-title mt-2">How MarketLink Works</h2>
            <p class="section-sub mx-auto">From farm to your table in four easy steps — no apps, no queues.</p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-3 reveal" style="transition-delay:.05s">
                <div class="eco-card p-4 h-100 step-card">
                    <span class="step-num">1</span>
                    <div class="display-6 text-fresh mb-3 mt-2"><i class="fa-solid fa-magnifying-glass-location"></i></div>
                    <h5 class="fw-bold">Find a Market</h5>
                    <p class="text-muted small mb-0">Locate your nearest weekend or weekday farmers market.</p>
                </div>
            </div>
            <div class="col-md-3 reveal" style="transition-delay:.12s">
                <div class="eco-card p-4 h-100 step-card">
                    <span class="step-num">2</span>
                    <div class="display-6 text-fresh mb-3 mt-2"><i class="fa-solid fa-apple-whole"></i></div>
                    <h5 class="fw-bold">Pre-Order Produce</h5>
                    <p class="text-muted small mb-0">Browse weekly stock directly listed by verified local growers.</p>
                </div>
            </div>
            <div class="col-md-3 reveal" style="transition-delay:.19s">
                <div class="eco-card p-4 h-100 step-card">
                    <span class="step-num">3</span>
                    <div class="display-6 text-fresh mb-3 mt-2"><i class="fa-solid fa-clock-check"></i></div>
                    <h5 class="fw-bold">Select Pickup Window</h5>
                    <p class="text-muted small mb-0">Choose a convenient date and time slot for easy stall pickup.</p>
                </div>
            </div>
            <div class="col-md-3 reveal" style="transition-delay:.26s">
                <div class="eco-card p-4 h-100 step-card">
                    <span class="step-num">4</span>
                    <div class="display-6 text-fresh mb-3 mt-2"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                    <h5 class="fw-bold">Pay at Pickup</h5>
                    <p class="text-muted small mb-0">Collect your fresh order and pay the farmer directly in person.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== TESTIMONIALS ===================== -->
    <section class="py-5 reveal" style="background:var(--cream-bg);">
        <div class="container">
            <div class="text-center mb-5">
                <span class="eyebrow"><i class="fa-solid fa-heart"></i> Community Love</span>
                <h2 class="section-title mt-2">What Our Neighbors Say</h2>
                <p class="section-sub mx-auto">Real stories from shoppers and growers on the MarketLink network.</p>
            </div>
            <div class="row testimonials-grid">
                <div class="col-md-4 reveal">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon"></i>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        <blockquote>"I pre-order my weekly veggies every Saturday morning. I walk straight to the stall, grab my basket, and pay in cash. No crowds, no guessing — just fresh food."</blockquote>
                        <div class="t-user">
                            <div class="t-avatar">SJ</div>
                            <div><p class="t-name">Sarah Jenkins</p><p class="t-role">Customer · Downtown</p></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 reveal" style="transition-delay:.1s">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon"></i>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        <blockquote>"MarketLink changed how I sell. I used to throw away overripe produce every weekend. Now I grow exactly what's pre-ordered and my waste is almost zero."</blockquote>
                        <div class="t-user">
                            <div class="t-avatar">MD</div>
                            <div><p class="t-name">Thomas Greene</p><p class="t-role">Farmer · Green Valley Farm</p></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 reveal" style="transition-delay:.2s">
                    <div class="testimonial-card">
                        <i class="fa-solid fa-quote-right quote-icon"></i>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                        <blockquote>"As a mom of three, this saves us so much time. The kids love the honeycomb gift box and pickup takes under five minutes every week."</blockquote>
                        <div class="t-user">
                            <div class="t-avatar">RL</div>
                            <div><p class="t-name">Rachel Lim</p><p class="t-role">Customer · Northside</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== CTA ===================== -->
    <section class="container py-5 reveal">
        <div class="cta-banner">
            <div class="row align-items-center position-relative">
                <div class="col-lg-8">
                    <h2 class="text-white fw-bold mb-2">Ready to taste the difference?</h2>
                    <p class="mb-0" style="color:rgba(255,255,255,.78)">Join 1,400+ conscious consumers who pre-order their weekly harvest.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a class="btn btn-gold btn-lg" href="{{ route('products.index') }}"><i class="fa-solid fa-basket-shopping me-2"></i>Start Pre-Ordering</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== MODALS (customer) ===================== -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-basket-shopping me-2 text-warning"></i>Your Weekly Pre-Order Cart</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="cartItemsContainer"></div>
                    <div id="cartSummaryBox" class="eco-card p-4 mt-4 bg-light d-none">
                        <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span class="fw-bold" id="cartSubtotal">$0.00</span></div>
                        <div class="d-flex justify-content-between mb-2 text-success fw-semibold"><span>Platform Fee</span><span>$0.00 (Free)</span></div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3 fs-4 fw-bold text-forest"><span>Est. Total Due at Pickup</span><span id="cartTotal">$0.00</span></div>
                        <div class="alert alert-eco py-2 small mb-3"><i class="fa-solid fa-circle-info me-1"></i> Payment is collected directly by farmers during market pickup.</div>
                        <button class="btn btn-eco-primary w-100 btn-lg" onclick="proceedToCheckout()"><i class="fa-solid fa-arrow-right me-1"></i> Proceed to Slot Selection</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa-solid fa-calendar-check me-2 text-warning"></i>Select Market Pickup Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="eco-card p-3 bg-light mb-4" id="checkoutSummaryContent"></div>
                    <form id="checkoutForm" onsubmit="event.preventDefault(); completePreOrder();">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pickup Market Hub</label>
                                <select class="form-select" id="checkoutMarket" required>
                                    <option value="Central Farmers Hub">Central Farmers Hub (Sat 8 AM - 1 PM)</option>
                                    <option value="Green Valley Eco Market">Green Valley Eco Market (Sun 9 AM - 2 PM)</option>
                                    <option value="West End Organics Fair">West End Organics Fair (Wed 3 PM - 7 PM)</option>
                                    <option value="Riverside Sunday Market">Riverside Sunday Market (Sun 8 AM - 12 PM)</option>
                                    <option value="Harvest Square Hub">Harvest Square Hub (Sat 9 AM - 1 PM)</option>
                                    <option value="Summer Nights Bazaar">Summer Nights Bazaar (Thu 5 PM - 9 PM)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pickup Time Slot</label>
                                <select class="form-select" id="checkoutSlot" required>
                                    <option>08:00 AM - 09:30 AM</option>
                                    <option>09:30 AM - 11:00 AM</option>
                                    <option>11:00 AM - 12:30 PM</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" class="form-control" id="checkoutName" value="Sarah Jenkins" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phone Number (SMS reminders)</label>
                                <input type="tel" class="form-control" id="checkoutPhone" value="+1 (555) 234-5678" required>
                            </div>
                            <div class="col-12">
                                <div class="alert alert-eco d-flex align-items-center gap-2 mb-0">
                                    <i class="fa-solid fa-hand-holding-dollar fs-5"></i>
                                    <span>Payment Method: <strong>Pay Cash / Card in Person at Market Stall</strong></span>
                                </div>
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-eco-primary btn-lg px-4"><i class="fa-solid fa-check me-1"></i> Confirm Pre-Order</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0" id="quickViewBody"></div>
            </div>
        </div>
    </div>

    <!-- ===================== FOOTER ===================== -->
    <footer class="footer-market">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 mb-3">
                        <span class="brand-logo"><i class="fa-solid fa-basket-shopping"></i></span>
                        <span class="fs-4 fw-bold text-white">Market<span class="text-warning">Link</span></span>
                    </a>
                    <p class="small" style="max-width:320px;">Pre-order fresh, organic harvest directly from verified local farmers and collect it at your neighborhood weekend market.</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="#" class="social-btn" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="social-btn" title="X"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="social-btn" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6>Marketplace</h6>
                    <a href="{{ route('products.index') }}">Weekly Products</a>
                    <a href="{{ route('markets.index') }}">Markets</a>
                    <a href="{{ route('farmers.index') }}">Farmers</a>
                    <a href="{{ route('dashboard') }}" class="id-customer-action">My Portal</a>
                </div>

                <div class="col-6 col-lg-2">
                    <h6>Company</h6>
                    <a href="{{ route('about') }}">About Us</a>
                    <a href="{{ route('how-it-works') }}">How It Works</a>
                    <a href="{{ route('how-it-works') }}">Sustainability</a>
                    <a href="{{ route('about') }}">Contact</a>
                </div>

                <div class="col-lg-4">
                    <h6>Fresh Harvest Newsletter</h6>
                    <p class="small">Seasonal recipes, new farmer alerts, and market schedules — straight to your inbox.</p>
                    <form id="newsletterForm" class="d-flex gap-2" onsubmit="event.preventDefault(); subscribeNewsletter();">
                        <input type="email" id="newsletterEmail" class="form-control" placeholder="you@example.com" required>
                        <button class="btn btn-gold px-3" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>&copy; <span id="currentYear">2026</span> MarketLink. All rights reserved.</span>
                <div class="d-flex gap-3">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Accessibility</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ===================== FLOATING AI ASSISTANT ===================== -->
    <div class="ai-assistant-btn" onclick="toggleAIChat()" title="Ask MarketLink AI Assistant">
        <i class="fa-solid fa-robot"></i>
        <span class="pulse-dot"></span>
    </div>

    <div class="ai-chat-window" id="aiChatWindow">
        <div class="ai-chat-header">
            <span><i class="fa-solid fa-seedling me-2"></i>MarketLink AI Assistant</span>
            <i class="fa-solid fa-xmark text-white" style="cursor:pointer;" onclick="toggleAIChat()"></i>
        </div>
        <div class="ai-chat-body d-flex flex-column" id="aiChatMessages">
            <div class="chat-msg bot">Hello! I'm your MarketLink assistant. Ask about markets, seasonal produce, or how pickup slots work!</div>
        </div>
        <div class="quick-chips" id="aiQuickReplies"></div>
        <div class="p-2 bg-white border-top d-flex gap-2">
            <input type="text" id="aiInput" class="form-control form-control-sm border-0" placeholder="Ask a question..." onkeypress="if(event.key==='Enter') sendAIMessage();">
            <button class="btn btn-eco-primary btn-sm" onclick="sendAIMessage()"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>

    <!-- Back to top -->
    <div class="back-top-btn" id="backToTopBtn" onclick="window.scrollTo({top:0, behavior:'smooth'})">
        <i class="fa-solid fa-arrow-up"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>window.ML_BACKEND = @json($frontendData ?? []); window.ML_URLS = { home: @json(route('home')), products: @json(route('products.index')), markets: @json(route('markets.index')), farmers: @json(route('farmers.index')), dashboard: @json(route('dashboard')), login: @json(route('login')), cartAdd: @json(url('/cart/add')), cartUpdate: @json(route('cart.update')), cartRemove: @json(url('/cart/remove')), favorites: @json(url('/favorites')), checkout: @json(route('checkout.place')), profile: @json(route('dashboard.profile')), farmerDashboard: @json(route('farmer.dashboard')), adminDashboard: @json(route('admin.dashboard')) };</script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        initGlobalUI();
        initHome();
    </script>
</body>
</html>