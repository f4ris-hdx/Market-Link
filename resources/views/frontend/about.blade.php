<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About MarketLink</title>
    <meta name="description" content="Bridging the gap between independent organic farmers and conscious consumers.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body data-role="{{ auth()->check() ? auth()->user()->role : 'customer' }}">

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
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('markets.index') }}">Markets</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('farmers.index') }}">Farmers</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('how-it-works') }}">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('about') }}">About</a></li>
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
                </div>
            </div>
        </div>
    </nav>

    <!-- ===================== PAGE HERO ===================== -->
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow dark mb-2"><i class="fa-solid fa-heart"></i> Our Story</span>
            <h1 class="mt-2">About MarketLink</h1>
            <p class="page-hero-sub mb-0">Bridging the gap between independent organic farmers and conscious consumers.</p>
        </div>
    </section>

    <!-- ===================== WHO WE ARE ===================== -->
    <div class="container my-5">
        <div class="row g-4 align-items-center mb-5">
            <div class="col-lg-6">
                <span class="eyebrow"><i class="fa-solid fa-seedling"></i> Who We Are</span>
                <h2 class="section-title mt-2">Community. Freshness. Fairness.</h2>
                <p class="text-muted">MarketLink was born at a weekend farmers market where growers were throwing away perfectly good harvest because they had no way to know what shoppers wanted before setting up their stall.</p>
                <p class="text-muted mb-0">Today, we give every independent grower a modern pre-order storefront and every shopper a queue-free way to collect their weekly groceries — all while keeping 100% of the money with the farmer.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a class="btn btn-eco-primary" href="{{ route('markets.index') }}"><i class="fa-solid fa-store me-2"></i>Explore Our Markets</a>
                    <a class="btn btn-eco-outline" href="{{ route('products.index') }}">Shop the Harvest</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1595855759920-86582396756a?auto=format&fit=crop&w=900&q=80" alt="Local farmers market" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>

        <!-- ===================== VALUES ===================== -->
        <div class="row g-4 mb-5">
            <div class="col-md-4 reveal">
                <div class="eco-card p-4 h-100 text-center about-value-card">
                    <div class="icon-chip about-value-icon mb-3"><i class="fa-solid fa-leaf"></i></div>
                    <h5 class="fw-bold">Eco-First</h5>
                    <p class="text-muted small mb-0">Pre-ordering cuts harvest waste to near zero and lowers carbon miles for fresh produce.</p>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:.08s">
                <div class="eco-card p-4 h-100 text-center about-value-card">
                    <div class="icon-chip about-value-icon mb-3"><i class="fa-solid fa-hand-holding-heart"></i></div>
                    <h5 class="fw-bold">Farmer-First Margins</h5>
                    <p class="text-muted small mb-0">No platform commission cuts. Farmers keep what their hard work earns.</p>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:.16s">
                <div class="eco-card p-4 h-100 text-center about-value-card">
                    <div class="icon-chip about-value-icon mb-3"><i class="fa-solid fa-people-group"></i></div>
                    <h5 class="fw-bold">Community Powered</h5>
                    <p class="text-muted small mb-0">Every market hub stays local, every stall is verified, and every shopper is a neighbor.</p>
                </div>
            </div>
        </div>

        <!-- ===================== IMPACT STRIP ===================== -->
        <div class="row g-4">
            <div class="col-md-3 reveal">
                <div class="stat-mini-card">
                    <div class="stat-mini-icon"><i class="fa-solid fa-seedling"></i></div>
                    <div><h3>1.4k+</h3><p>Households served weekly</p></div>
                </div>
            </div>
            <div class="col-md-3 reveal" style="transition-delay:.06s">
                <div class="stat-mini-card">
                    <div class="stat-mini-icon"><i class="fa-solid fa-trash-can"></i></div>
                    <div><h3>2.3k kg</h3><p>Waste prevented this year</p></div>
                </div>
            </div>
            <div class="col-md-3 reveal" style="transition-delay:.12s">
                <div class="stat-mini-card">
                    <div class="stat-mini-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                    <div><h3>100%</h3><p>Of sales go to farmers</p></div>
                </div>
            </div>
            <div class="col-md-3 reveal" style="transition-delay:.18s">
                <div class="stat-mini-card">
                    <div class="stat-mini-icon"><i class="fa-solid fa-tractor"></i></div>
                    <div><h3>20+</h3><p>Active partner growers</p></div>
                </div>
            </div>
        </div>
    </div>

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

    <div class="back-top-btn" id="backToTopBtn" onclick="window.scrollTo({top:0, behavior:'smooth'})">
        <i class="fa-solid fa-arrow-up"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>window.ML_BACKEND = @json($frontendData ?? []); window.ML_URLS = { home: @json(route('home')), products: @json(route('products.index')), markets: @json(route('markets.index')), farmers: @json(route('farmers.index')), dashboard: @json(route('dashboard')), login: @json(route('login')), cartAdd: @json(url('/cart/add')), cartUpdate: @json(route('cart.update')), cartRemove: @json(url('/cart/remove')), favorites: @json(url('/favorites')), checkout: @json(route('checkout.place')), profile: @json(route('dashboard.profile')), farmerDashboard: @json(route('farmer.dashboard')), adminDashboard: @json(route('admin.dashboard')) };</script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        initGlobalUI();
    </script>
</body>
</html>