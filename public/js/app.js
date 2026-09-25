/* ==========================================================
   MarketLink — Shared App Library (data, state, cart, AI, UI)
   Loaded by every page. Page-specific init scripts live at
   the bottom of each HTML file.
   ========================================================== */

const ML_VERSION = 'ml_v3';
const STORAGE_KEYS = ['ml_products', 'ml_cart', 'ml_favorites', 'ml_orders', 'ml_role', 'ml_queue', 'ml_announcements'];

/* ---------- Category catalog ---------- */
const CATEGORIES = [
    { name: 'Vegetables', icon: 'fa-carrot', color: 'text-success' },
    { name: 'Fruits', icon: 'fa-apple-whole', color: 'text-danger' },
    { name: 'Dairy & Eggs', icon: 'fa-cow', color: 'text-warning' },
    { name: 'Baked Goods', icon: 'fa-bread-slice', color: 'text-primary' },
    { name: 'Herbs & Spices', icon: 'fa-leaf', color: 'text-success' },
    { name: 'Honey & Preserves', icon: 'fa-jar-wheat', color: 'text-warning' }
];

/* ---------- Product catalog (32 items) ---------- */
const IMG = {
    tomatoes: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80',
    carrots: 'https://images.unsplash.com/photo-1447175008436-054170c2e979?auto=format&fit=crop&w=600&q=80',
    milk: 'https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&w=600&q=80',
    apples: 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80',
    bread: 'https://images.unsplash.com/photo-1585478259715-876acc5be8eb?auto=format&fit=crop&w=600&q=80',
    herbs: 'https://images.unsplash.com/photo-1608683222718-406a6b5711e5?auto=format&fit=crop&w=600&q=80',
    eggs: 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?auto=format&fit=crop&w=600&q=80',
    honey: 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?auto=format&fit=crop&w=600&q=80',
    berries: 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=600&q=80',
    basket: 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=600&q=80',
    vegmarket: 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=600&q=80',
    orange: 'https://images.unsplash.com/photo-1611080626919-7cf5a9dbab5b?auto=format&fit=crop&w=600&q=80',
    cheese: 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?auto=format&fit=crop&w=600&q=80',
    yogurt: 'https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=600&q=80',
    pastry: 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80'
};

let sampleProducts = [
    // Vegetables
    { id: 101, name: 'Organic Heirloom Tomatoes', category: 'Vegetables', farmer: 'Green Valley Farm', price: 4.50, unit: 'lb', stock: 25, rating: 4.9, image: IMG.tomatoes },
    { id: 102, name: 'Rainbow Carrots Bundle', category: 'Vegetables', farmer: 'Willow Creek Produce', price: 3.20, unit: 'bunch', stock: 30, rating: 4.8, image: IMG.carrots },
    { id: 103, name: 'Curly Kale Greens', category: 'Vegetables', farmer: 'Green Valley Farm', price: 3.00, unit: 'bunch', stock: 22, rating: 4.7, image: IMG.vegmarket },
    { id: 104, name: 'Sweet Corn Pack (4)', category: 'Vegetables', farmer: 'Harvest Hills Farm', price: 5.00, unit: 'pack', stock: 18, rating: 4.8, image: IMG.vegmarket },
    { id: 105, name: 'Shishito Peppers', category: 'Vegetables', farmer: 'Willow Creek Produce', price: 4.00, unit: 'lb', stock: 20, rating: 4.6, image: IMG.basket },
    { id: 106, name: 'Heirloom Beets', category: 'Vegetables', farmer: 'Green Valley Farm', price: 3.75, unit: 'lb', stock: 14, rating: 4.7, image: IMG.carrots },
    // Fruits
    { id: 107, name: 'Crisp Honeycrisp Apples', category: 'Fruits', farmer: 'Heritage Orchard', price: 3.80, unit: 'lb', stock: 40, rating: 5.0, image: IMG.apples },
    { id: 108, name: 'Sweet Summer Strawberries', category: 'Fruits', farmer: 'Willow Creek Produce', price: 6.20, unit: 'pint', stock: 6, rating: 4.9, image: IMG.berries },
    { id: 109, name: 'Sun-Ripened Peaches', category: 'Fruits', farmer: 'Heritage Orchard', price: 5.50, unit: 'lb', stock: 16, rating: 4.9, image: IMG.basket },
    { id: 110, name: 'Wild Blueberries', category: 'Fruits', farmer: 'Blueberry Hill Farm', price: 7.25, unit: 'pint', stock: 12, rating: 4.9, image: IMG.berries },
    { id: 111, name: 'Navel Oranges', category: 'Fruits', farmer: 'Citrus Ridge Farm', price: 4.20, unit: 'lb', stock: 24, rating: 4.7, image: IMG.orange },
    { id: 112, name: 'Bartlett Pears', category: 'Fruits', farmer: 'Heritage Orchard', price: 4.75, unit: 'lb', stock: 18, rating: 4.8, image: IMG.apples },
    // Dairy & Eggs
    { id: 113, name: 'Farm Fresh Whole Milk', category: 'Dairy & Eggs', farmer: 'Sunny Acres Dairy', price: 5.20, unit: 'half-gal', stock: 12, rating: 4.8, image: IMG.milk },
    { id: 114, name: 'Pasture-Raised Eggs (dozen)', category: 'Dairy & Eggs', farmer: 'Sunny Acres Dairy', price: 6.50, unit: 'dozen', stock: 15, rating: 5.0, image: IMG.eggs },
    { id: 115, name: 'Aged White Cheddar', category: 'Dairy & Eggs', farmer: 'Meadow Creek Dairy', price: 9.00, unit: 'lb', stock: 10, rating: 4.9, image: IMG.cheese },
    { id: 116, name: 'Thick Greek Yogurt', category: 'Dairy & Eggs', farmer: 'Meadow Creek Dairy', price: 7.50, unit: 'tub', stock: 9, rating: 4.7, image: IMG.yogurt },
    { id: 117, name: 'Cultured Butter', category: 'Dairy & Eggs', farmer: 'Sunny Acres Dairy', price: 8.25, unit: 'lb', stock: 8, rating: 4.8, image: IMG.cheese },
    // Baked Goods
    { id: 118, name: 'Artisanal Sourdough Loaf', category: 'Baked Goods', farmer: 'Rustic Loaf Bakery', price: 7.00, unit: 'loaf', stock: 10, rating: 4.7, image: IMG.bread },
    { id: 119, name: 'Baguette 2-Pack', category: 'Baked Goods', farmer: 'Rustic Loaf Bakery', price: 5.50, unit: 'pack', stock: 14, rating: 4.6, image: IMG.pastry },
    { id: 120, name: 'Butter Croissants (4)', category: 'Baked Goods', farmer: 'Rustic Loaf Bakery', price: 6.75, unit: 'box', stock: 11, rating: 4.8, image: IMG.pastry },
    { id: 121, name: 'Cinnamon Rolls (3)', category: 'Baked Goods', farmer: 'Rustic Loaf Bakery', price: 8.50, unit: 'box', stock: 7, rating: 4.9, image: IMG.pastry },
    { id: 122, name: 'Homemade Granola', category: 'Baked Goods', farmer: 'The Oat Barn', price: 6.40, unit: 'jar', stock: 13, rating: 4.6, image: IMG.yogurt },
    // Herbs & Spices
    { id: 123, name: 'Fresh Organic Basil', category: 'Herbs & Spices', farmer: 'Green Valley Farm', price: 2.50, unit: 'bunch', stock: 18, rating: 4.9, image: IMG.herbs },
    { id: 124, name: 'Garden Mint', category: 'Herbs & Spices', farmer: 'The Herb Patch', price: 2.20, unit: 'bunch', stock: 20, rating: 4.6, image: IMG.herbs },
    { id: 125, name: 'Fresh Thyme', category: 'Herbs & Spices', farmer: 'The Herb Patch', price: 2.40, unit: 'bunch', stock: 16, rating: 4.7, image: IMG.herbs },
    { id: 126, name: 'Rosemary Bundle', category: 'Herbs & Spices', farmer: 'The Herb Patch', price: 2.60, unit: 'bunch', stock: 15, rating: 4.7, image: IMG.herbs },
    { id: 127, name: 'Ground Turmeric (2oz)', category: 'Herbs & Spices', farmer: 'Spice Mill Collective', price: 5.80, unit: 'jar', stock: 10, rating: 4.8, image: IMG.honey },
    // Honey & Preserves
    { id: 128, name: 'Raw Wildflower Honey', category: 'Honey & Preserves', farmer: 'Golden Hive Apiary', price: 9.75, unit: 'jar', stock: 8, rating: 4.8, image: IMG.honey },
    { id: 129, name: 'Honeycomb Gift Box', category: 'Honey & Preserves', farmer: 'Golden Hive Apiary', price: 12.50, unit: 'box', stock: 6, rating: 5.0, image: IMG.honey },
    { id: 130, name: 'Strawberry Chia Jam', category: 'Honey & Preserves', farmer: 'Oak Grove Kitchen', price: 7.20, unit: 'jar', stock: 14, rating: 4.6, image: IMG.honey },
    { id: 131, name: 'Peach Preserves', category: 'Honey & Preserves', farmer: 'Oak Grove Kitchen', price: 7.50, unit: 'jar', stock: 12, rating: 4.7, image: IMG.honey }
];

/* ---------- Markets (6) ---------- */
let sampleMarkets = [
    { name: 'Central Farmers Hub', location: 'Downtown Plaza', days: 'Saturdays, 8:00 AM - 1:00 PM', farmersCount: 14, distance: 1.2 },
    { name: 'Green Valley Eco Market', location: 'Northside Park', days: 'Sundays, 9:00 AM - 2:00 PM', farmersCount: 9, distance: 3.5 },
    { name: 'West End Organics Fair', location: 'Community Center', days: 'Wednesdays, 3:00 PM - 7:00 PM', farmersCount: 11, distance: 4.1 },
    { name: 'Riverside Sunday Market', location: 'Riverfront Promenade', days: 'Sundays, 8:00 AM - 12:00 PM', farmersCount: 7, distance: 5.2 },
    { name: 'Harvest Square Hub', location: 'Old Town Square', days: 'Saturdays, 9:00 AM - 1:00 PM', farmersCount: 16, distance: 2.4 },
    { name: 'Summer Nights Bazaar', location: 'Harbor Green', days: 'Thursdays, 5:00 PM - 9:00 PM', farmersCount: 6, distance: 6.4 }
];

/* ---------- Farmers (14) ---------- */
let sampleFarmers = [
    { name: 'Green Valley Farm', location: 'North District', specialty: 'Organic Heirloom Vegetables & Herbs', rating: 4.9, image: 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=500&q=80' },
    { name: 'Sunny Acres Dairy', location: 'East Valley', specialty: 'Raw Milk, Cheese & Pasture Eggs', rating: 4.8, image: 'https://images.unsplash.com/photo-1527153857715-3908f2bae5e8?auto=format&fit=crop&w=500&q=80' },
    { name: 'Heritage Orchard', location: 'South Hills', specialty: 'Seasonal Apples, Peaches & Pears', rating: 5.0, image: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=500&q=80' },
    { name: 'Rustic Loaf Bakery', location: 'Old Town', specialty: 'Sourdough & Artisanal Pastries', rating: 4.7, image: 'https://images.unsplash.com/photo-1586444248902-2f64eddc13df?auto=format&fit=crop&w=500&q=80' },
    { name: 'Golden Hive Apiary', location: 'West Valley', specialty: 'Raw Wildflower Honey & Honeycomb', rating: 4.8, image: 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?auto=format&fit=crop&w=500&q=80' },
    { name: 'Willow Creek Produce', location: 'North District', specialty: 'Leafy Greens & Summer Vegetables', rating: 4.8, image: 'https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&w=500&q=80' },
    { name: 'Meadow Creek Dairy', location: 'East Valley', specialty: 'Artisan Cheeses & Yogurts', rating: 4.9, image: 'https://images.unsplash.com/photo-1527153857715-3908f2bae5e8?auto=format&fit=crop&w=500&q=80' },
    { name: 'Blueberry Hill Farm', location: 'South Hills', specialty: 'Wild Blueberries & Soft Fruit', rating: 4.9, image: 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=500&q=80' },
    { name: 'Harvest Hills Farm', location: 'West Valley', specialty: 'Field Vegetables & Sweet Corn', rating: 4.7, image: 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=500&q=80' },
    { name: 'The Herb Patch', location: 'Downtown', specialty: 'Fresh Culinary Herbs & Microgreens', rating: 4.6, image: 'https://images.unsplash.com/photo-1608683222718-406a6b5711e5?auto=format&fit=crop&w=500&q=80' },
    { name: 'The Oat Barn', location: 'North District', specialty: 'Granola, Oats & Baked Staples', rating: 4.6, image: 'https://images.unsplash.com/photo-1517679284304-f76e6fdc1243?auto=format&fit=crop&w=500&q=80' },
    { name: 'Citrus Ridge Farm', location: 'South Hills', specialty: 'Oranges & Mediterranean Fruit', rating: 4.7, image: 'https://images.unsplash.com/photo-1611080626919-7cf5a9dbab5b?auto=format&fit=crop&w=500&q=80' },
    { name: 'Oak Grove Kitchen', location: 'Old Town', specialty: 'Small-Batch Jams & Preserves', rating: 4.7, image: 'https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=500&q=80' },
    { name: 'Spice Mill Collective', location: 'Downtown', specialty: 'Single-Origin Ground Spices', rating: 4.8, image: 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?auto=format&fit=crop&w=500&q=80' }
];

/* ---------- Laravel backend bridge ---------- */
const ML_BACKEND = window.ML_BACKEND || {};
if (Array.isArray(ML_BACKEND.products)) sampleProducts = ML_BACKEND.products;
if (Array.isArray(ML_BACKEND.markets)) sampleMarkets = ML_BACKEND.markets;
if (Array.isArray(ML_BACKEND.farmers)) sampleFarmers = ML_BACKEND.farmers;
function mlCsrf() { return ML_BACKEND.csrf || document.querySelector('meta[name="csrf-token"]')?.content || ''; }
async function mlPost(url, data = {}) {
    const body = new URLSearchParams(); Object.entries(data).forEach(([k,v]) => body.append(k,v));
    const res = await fetch(url, {method:'POST', headers:{'X-CSRF-TOKEN':mlCsrf(),'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body});
    const payload = await res.json().catch(()=>({})); if(!res.ok) throw new Error(payload.message || 'Request failed.'); return payload;
}

/* ---------- State (persisted) ---------- */
let state = {
    products: Array.isArray(ML_BACKEND.products) ? ML_BACKEND.products : (JSON.parse(localStorage.getItem('ml_products')) || sampleProducts),
    cart: ML_BACKEND.cart ? Object.entries(ML_BACKEND.cart).map(([id,qty]) => { const p=sampleProducts.find(x=>Number(x.id)===Number(id)); return p?{id:Number(id),name:p.name,price:p.price,farmer:p.farmer,unit:p.unit,qty:Number(qty)}:null; }).filter(Boolean) : (JSON.parse(localStorage.getItem('ml_cart')) || []),
    favorites: Array.isArray(ML_BACKEND.favorites) ? ML_BACKEND.favorites.map(Number) : (JSON.parse(localStorage.getItem('ml_favorites')) || []),
    orders: Array.isArray(ML_BACKEND.orders) ? ML_BACKEND.orders : (JSON.parse(localStorage.getItem('ml_orders')) || []),
    role: ML_BACKEND.role || document.body.dataset.role || 'customer',
    farmerQueue: JSON.parse(localStorage.getItem('ml_queue')) || [
        { name: 'Oakridge Orchard', owner: 'David Miller', market: 'West End Organics', status: 'pending' },
        { name: 'Green Valley Farm', owner: 'John Doe', market: 'Central Farmers Hub', status: 'verified' },
        { name: 'Maple Hollow Produce', owner: 'Priya Shah', market: 'Harvest Square Hub', status: 'pending' }
    ],
    announcements: JSON.parse(localStorage.getItem('ml_announcements')) || []
};

function ensureStorageVersion() {
    try {
        if (localStorage.getItem('ml_version') !== ML_VERSION) {
            STORAGE_KEYS.forEach(k => localStorage.removeItem(k));
            localStorage.setItem('ml_version', ML_VERSION);
        }
    } catch (e) { /* storage unavailable */ }
}
ensureStorageVersion();

function saveState() {
    try {
        localStorage.setItem('ml_products', JSON.stringify(state.products));
        localStorage.setItem('ml_cart', JSON.stringify(state.cart));
        localStorage.setItem('ml_favorites', JSON.stringify(state.favorites));
        localStorage.setItem('ml_orders', JSON.stringify(state.orders));
        localStorage.setItem('ml_queue', JSON.stringify(state.farmerQueue));
        localStorage.setItem('ml_announcements', JSON.stringify(state.announcements));
    } catch (e) { /* ignore */ }
    updateCartBadge();
}

/* ---------- Formatting helpers ---------- */
function fmtMoney(n) { return '$' + Number(n || 0).toFixed(2); }
function milesText(m) { return m.toFixed(1) + ' miles'; }
function escapeHTML(str) {
    return String(str).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

/* ==========================================================
   GLOBAL UI (navbar, footer, role badge, toast)
   ========================================================== */
function initGlobalUI() {
    const y = document.getElementById('currentYear'); if (y) y.textContent = new Date().getFullYear();
    const roleLabel = document.getElementById('currentRoleLabel');
    if (roleLabel) roleLabel.textContent = state.role.charAt(0).toUpperCase() + state.role.slice(1);
    updateCartBadge();
    renderQuickReplies();
    initScrollEffects();
    initReveal();
}

function activateRole(role) {
    const pages = { customer: ML_URLS.home, farmer: ML_URLS.farmerDashboard, admin: ML_URLS.adminDashboard };
    window.location.href = pages[role] || 'index.html';
}

function initScrollEffects() {
    const nav = document.getElementById('mainNav');
    const backBtn = document.getElementById('backToTopBtn');
    window.addEventListener('scroll', () => {
        if (nav) nav.classList.toggle('navbar-scrolled', window.scrollY > 12);
        if (backBtn) backBtn.classList.toggle('visible', window.scrollY > 450);
    }, { passive: true });
}

function initReveal() {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) { entry.target.classList.add('in-view'); observer.unobserve(entry.target); }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
}

function showToast(message, type) {
    const stack = document.getElementById('toastStack');
    if (!stack) return;
    const toast = document.createElement('div');
    toast.className = 'ml-toast ' + (type || 'success');
    const icon = type === 'error' ? 'fa-circle-exclamation' : (type === 'info' ? 'fa-circle-info' : 'fa-circle-check');
    toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${escapeHTML(message)}</span>`;
    stack.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'opacity .4s ease, transform .4s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 420);
    }, 3000);
}

function subscribeNewsletter() {
    const input = document.getElementById('newsletterEmail');
    const email = input ? input.value.trim() : '';
    if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) { showToast('Please enter a valid email address.', 'error'); return; }
    if (input) input.value = '';
    showToast("You're subscribed to the fresh harvest newsletter! 🌱", 'success');
}

function openNotificationsModal() {
    showToast('Order ORD-9821 is ready for Saturday pickup!', 'info');
}

/* ==========================================================
   CART & CHECKOUT
   ========================================================== */
async function addToCart(productId, qty) {
    if (!ML_BACKEND.authenticated) { window.location.href = ML_URLS.login; return; }
    const product = state.products.find(p => Number(p.id) === Number(productId)); if (!product) return;
    const addQty = qty || 1; const existing = state.cart.find(c => Number(c.id) === Number(productId));
    const currentQty = existing ? existing.qty : 0;
    if (currentQty + addQty > product.stock) { showToast(`Only ${product.stock} of "${product.name}" available`, 'error'); return; }
    try {
        const payload = await mlPost(`${ML_URLS.cartAdd}/${productId}`, {qty:addQty});
        state.cart = Object.entries(payload.cart || {}).map(([id,q]) => { const p=state.products.find(x=>Number(x.id)===Number(id)); return p?{id:Number(id),name:p.name,price:p.price,farmer:p.farmer,unit:p.unit,qty:Number(q)}:null; }).filter(Boolean);
        saveState(); showToast(`Added ${product.name} to your cart!`, 'success');
    } catch(e) { showToast(e.message,'error'); }
}
function updateCartBadge() {
    const count = state.cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('cartBadge');
    if (!badge) return;
    badge.textContent = count;
    badge.classList.add('pop');
    setTimeout(() => badge.classList.remove('pop'), 260);
}

function openCartModal() { renderCartItems(); new bootstrap.Modal(document.getElementById('cartModal')).show(); }

function renderCartItems() {
    const container = document.getElementById('cartItemsContainer');
    const summaryBox = document.getElementById('cartSummaryBox');
    if (!container) return;
    if (state.cart.length === 0) {
        container.innerHTML = `<div class="text-center py-5">
            <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.5rem;"><i class="fa-solid fa-basket-shopping"></i></div>
            <p class="fw-semibold mb-1">Your basket is empty</p>
            <p class="text-muted small mb-3">Browse the weekly marketplace to add fresh produce.</p>
            <a class="btn btn-eco-primary btn-sm" href="${window.ML_URLS?.products || '/products'}"><i class="fa-solid fa-carrot me-1"></i>Start Shopping</a>
        </div>`;
        summaryBox.classList.add('d-none');
        return;
    }
    let subtotal = 0;
    container.innerHTML = state.cart.map((item, index) => {
        const total = item.price * item.qty;
        subtotal += total;
        return `<div class="d-flex justify-content-between align-items-center p-3 border-bottom flex-wrap gap-2">
            <div><h6 class="fw-bold mb-0 text-forest">${item.name}</h6>
            <small class="text-muted">${item.farmer} · ${fmtMoney(item.price)}/${item.unit}</small></div>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group"><button class="btn btn-sm btn-outline-secondary" onclick="changeQty(${index},-1)"><i class="fa-solid fa-minus"></i></button>
                <span class="btn btn-sm btn-light fw-bold" style="min-width:40px;">${item.qty}</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="changeQty(${index},1)"><i class="fa-solid fa-plus"></i></button></div>
                <span class="fw-bold ms-2" style="min-width:62px;text-align:right;">${fmtMoney(total)}</span>
                <button class="btn btn-sm text-danger ms-1" onclick="removeCartItem(${index})" title="Remove"><i class="fa-solid fa-trash"></i></button>
            </div></div>`;
    }).join('');
    document.getElementById('cartSubtotal').textContent = fmtMoney(subtotal);
    document.getElementById('cartTotal').textContent = fmtMoney(subtotal);
    summaryBox.classList.remove('d-none');
}

async function changeQty(index, delta) {
    const item=state.cart[index]; if(!item) return; const product=state.products.find(p=>Number(p.id)===Number(item.id));
    const nextQty=item.qty+delta; if(nextQty<0) return; if(product && nextQty>product.stock){showToast(`Only ${product.stock} of "${product.name}" available`,'error');return;}
    const body=new URLSearchParams(); state.cart.forEach((c,i)=>body.append(`qty[${c.id}]`, i===index?nextQty:c.qty));
    try{const res=await fetch(ML_URLS.cartUpdate,{method:'POST',headers:{'X-CSRF-TOKEN':mlCsrf(),'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},body});const payload=await res.json();if(!res.ok)throw new Error(payload.message||'Could not update basket.');state.cart=Object.entries(payload.cart||{}).map(([id,q])=>{const p=state.products.find(x=>Number(x.id)===Number(id));return p?{id:Number(id),name:p.name,price:p.price,farmer:p.farmer,unit:p.unit,qty:Number(q)}:null;}).filter(Boolean);saveState();renderCartItems();}catch(e){showToast(e.message,'error');}
}
async function removeCartItem(index) {
    const item=state.cart[index]; if(!item) return;
    try{const payload=await mlPost(`${ML_URLS.cartRemove}/${item.id}`,{});state.cart=Object.entries(payload.cart||{}).map(([id,q])=>{const p=state.products.find(x=>Number(x.id)===Number(id));return p?{id:Number(id),name:p.name,price:p.price,farmer:p.farmer,unit:p.unit,qty:Number(q)}:null;}).filter(Boolean);saveState();renderCartItems();}catch(e){showToast(e.message,'error');}
}
function proceedToCheckout() {
    if (state.cart.length === 0) { showToast('Your cart is empty — add some produce first!', 'info'); return; }
    bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
    renderCheckoutSummary();
    new bootstrap.Modal(document.getElementById('checkoutModal')).show();
}

function renderCheckoutSummary() {
    const box = document.getElementById('checkoutSummaryContent'); if (!box) return;
    const subtotal = state.cart.reduce((sum, item) => sum + item.price * item.qty, 0);
    box.innerHTML = `<div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-semibold">${state.cart.reduce((s, i) => s + i.qty, 0)} item(s) in basket</span>
        <span class="fw-bold text-forest">${fmtMoney(subtotal)}</span></div>
        <div class="small text-muted">${state.cart.map(i => `${i.qty}x ${i.name}`).join(' · ')}</div>`;
}

async function completePreOrder() {
    if(!state.cart.length){showToast('Your cart is empty — add some produce first!','info');return;} if(!ML_BACKEND.authenticated){window.location.href=ML_URLS.login;return;}
    const marketName=document.getElementById('checkoutMarket').value, market=sampleMarkets.find(m=>m.name===marketName), slot=document.getElementById('checkoutSlot').value;
    const name=(document.getElementById('checkoutName')||{value:''}).value, phone=(document.getElementById('checkoutPhone')||{value:''}).value;
    try{const payload=await mlPost(ML_URLS.checkout,{pickup_name:name,pickup_phone:phone,market_id:market?.id||'',slot});const cm=bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));if(cm)cm.hide();state.cart=[];saveState();showToast(`Order ${payload.order_number} placed! Pay in person at pickup.`,'success');setTimeout(()=>window.location.href=ML_URLS.dashboard,500);}catch(e){showToast(e.message,'error');}
}
/* ==========================================================
   PRODUCT RENDERERS
   ========================================================== */
function productCardHTML(prod) {
    const isFav = state.favorites.includes(prod.id);
    const lowStock = prod.stock > 0 && prod.stock <= 8;
    return `<div class="col-sm-6 col-lg-4">
        <div class="product-card">
            <div class="product-media" onclick="openQuickView(${prod.id})" style="cursor:pointer;">
                <img src="${prod.image}" alt="${prod.name}" loading="lazy">
                <span class="product-cat">${prod.category}</span>
                <button class="fav-btn ${isFav ? 'active' : ''}" onclick="event.stopPropagation(); toggleFavorite(${prod.id})" title="Save to favorites"><i class="fa-solid fa-heart"></i></button>
                ${lowStock ? `<span class="low-stock-chip"><i class="fa-solid fa-fire"></i> Only ${prod.stock} left</span>` : ''}
                <button class="btn btn-light btn-sm rounded-pill shadow-sm add-btn-hover" onclick="event.stopPropagation(); openQuickView(${prod.id})" title="Quick view"><i class="fa-solid fa-eye me-1"></i>Quick View</button>
            </div>
            <div class="product-body">
                <small class="product-farmer">${prod.farmer}</small>
                <h6 class="product-name">${prod.name}</h6>
                <div class="product-meta">
                    <span class="rating"><i class="fa-solid fa-star"></i> ${prod.rating.toFixed(1)}</span>
                    <span>${prod.stock} in stock</span>
                </div>
                <div class="product-footer">
                    <div class="price"><span class="amount">${fmtMoney(prod.price)}</span><small> / ${prod.unit}</small></div>
                    <button class="btn btn-eco-primary btn-sm px-3" onclick="addToCart(${prod.id})" title="Add to cart"><i class="fa-solid fa-plus me-1"></i>Add</button>
                </div>
            </div>
        </div></div>`;
}

function emptyStateHTML(message) {
    return `<div class="col-12 text-center py-5">
        <div class="icon-chip mx-auto mb-3" style="width:64px;height:64px;font-size:1.6rem;"><i class="fa-solid fa-lemon"></i></div>
        <p class="text-muted fw-semibold">${message}</p></div>`;
}

function renderProductsGrid(productsList, targetElementId) {
    const container = document.getElementById(targetElementId);
    if (!container) return;
    container.innerHTML = productsList.length ? productsList.map(productCardHTML).join('') : emptyStateHTML('No fresh items matched your search criteria.');
}

function renderCategories() {
    const grid = document.getElementById('categoriesGrid'); if (!grid) return;
    grid.innerHTML = '<div class="col-12"><p class="text-muted">Loading categories…</p></div>';
    grid.innerHTML = CATEGORIES.map(cat => {
        const count = state.products.filter(p => p.category === cat.name).length;
        return `<div class="col-6 col-md-4 col-lg-2">
            <div class="category-card" onclick="filterByCategory('${cat.name}')">
                <div class="cat-icon"><i class="fa-solid ${cat.icon}"></i></div>
                <h6 class="fw-bold mb-1 text-forest">${cat.name}</h6>
                <small class="text-muted">${count} items</small></div></div>`;
    }).join('');
}

/* ==========================================================
   FILTERS & SEARCH (products page + home)
   ========================================================== */
function renderFilterChips() {
    const container = document.getElementById('filterChips'); if (!container) return;
    const current = document.getElementById('filterCategory').value;
    container.innerHTML = `<span class="chip ${!current ? 'active' : ''}" onclick="setCatFilter('')">All</span>` +
        CATEGORIES.map(c => `<span class="chip ${current === c.name ? 'active' : ''}" onclick="setCatFilter('${c.name}')">${c.name}</span>`).join('');
}

function setCatFilter(cat) { const el = document.getElementById('filterCategory'); if (el) el.value = cat; applyProductFilters(); }

function applyProductFilters() {
    const cat = document.getElementById('filterCategory') ? document.getElementById('filterCategory').value : '';
    const farmer = document.getElementById('filterFarmer') ? document.getElementById('filterFarmer').value : '';
    const maxPrice = document.getElementById('filterPriceRange') ? parseFloat(document.getElementById('filterPriceRange').value) : 30;
    const sort = document.getElementById('sortProductsSelect') ? document.getElementById('sortProductsSelect').value : 'popular';
    const search = (document.getElementById('productSearchInput') || { value: '' }).value.trim().toLowerCase();

    let filtered = state.products.filter(p => {
        const mCat = !cat || p.category === cat;
        const mFarmer = !farmer || p.farmer === farmer;
        const mPrice = p.price <= maxPrice;
        const mSearch = !search || (p.name + ' ' + p.farmer + ' ' + p.category).toLowerCase().includes(search);
        return mCat && mFarmer && mPrice && mSearch;
    });
    if (sort === 'low') filtered.sort((a, b) => a.price - b.price);
    else if (sort === 'high') filtered.sort((a, b) => b.price - a.price);
    else if (sort === 'rating') filtered.sort((a, b) => b.rating - a.rating);
    else filtered.sort((a, b) => b.rating * b.stock - a.rating * a.stock);

    renderProductsGrid(filtered, 'mainProductsGrid');
    renderFilterChips();
    const lbl = document.getElementById('productsCountLabel');
    if (lbl) lbl.textContent = `Showing ${filtered.length} fresh product${filtered.length === 1 ? '' : 's'}`;
}

function filterByCategory(catName) { window.location.href = (window.ML_URLS?.products || '/products') + '?cat=' + encodeURIComponent(catName); }
function updatePriceLabel(val) { const el = document.getElementById('priceValueLabel'); if (el) el.textContent = fmtMoney(val); }

function resetFilters() {
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterFarmer').value = '';
    document.getElementById('filterPriceRange').value = 30;
    document.getElementById('productSearchInput').value = '';
    updatePriceLabel(30);
    applyProductFilters();
}

function executeHomeSearch() {
    const q = (document.getElementById('homeSearchInput') || { value: '' }).value.trim();
    const cat = (document.getElementById('homeCategorySelect') || { value: '' }).value;
    let url = window.ML_URLS?.products || '/products';
    const params = new URLSearchParams();
    if (q) params.set('q', q);
    if (cat) params.set('cat', cat);
    const qs = params.toString();
    window.location.href = qs ? url + '?' + qs : url;
}

function initProducts() {
    const params = new URLSearchParams(window.location.search);
    const catFilter = params.get('cat');
    const qFilter = params.get('q');

    const farmerSelect = document.getElementById('filterFarmer');
    if (farmerSelect) {
        const farmers = [...new Set(state.products.map(p => p.farmer))].sort();
        farmerSelect.innerHTML = '<option value="">All Farmers</option>' +
            farmers.map(f => `<option value="${f}">${f}</option>`).join('');
    }
    if (catFilter) { document.getElementById('filterCategory').value = catFilter; }
    if (qFilter) { const si = document.getElementById('productSearchInput'); if (si) si.value = qFilter; }
    applyProductFilters();
    if (qFilter && !catFilter) showToast('Showing results for your search', 'info');
}

/* ==========================================================
   MARKETS & FARMERS PAGES
   ========================================================== */
function marketCardHTML(m) {
    return `<div class="col-md-4">
        <div class="eco-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="icon-chip"><i class="fa-solid fa-store"></i></div>
                <span class="badge bg-mint text-forest">${m.farmersCount} Vendors</span></div>
            <h5 class="fw-bold mb-1 mt-2">${m.name}</h5>
            <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1 text-fresh"></i>${m.location} · ${milesText(m.distance)}</p>
            <p class="fw-semibold small text-dark mb-4"><i class="fa-regular fa-calendar me-1 text-warning"></i>${m.days}</p>
            <button class="btn btn-eco-outline w-100 mt-auto" onclick="location.href=(window.ML_URLS?.farmers || '/farmers')"><i class="fa-solid fa-tractor me-1"></i>Farmers at this Market</button>
        </div></div>`;
}

function renderMarkets() {
    const homeContainer = document.getElementById('homeMarketsList');
    const fullContainer = document.getElementById('fullMarketsGrid');
    const search = (document.getElementById('marketSearchInput') || { value: '' }).value.trim().toLowerCase();
    const sortBy = document.getElementById('marketSortSelect') ? document.getElementById('marketSortSelect').value : 'name';

    let list = [...sampleMarkets];
    if (search) list = list.filter(m => (m.name + ' ' + m.location + ' ' + m.days).toLowerCase().includes(search));
    if (sortBy === 'farmers') list.sort((a, b) => b.farmersCount - a.farmersCount);
    else if (sortBy === 'near') list.sort((a, b) => a.distance - b.distance);
    else list.sort((a, b) => a.name.localeCompare(b.name));

    if (homeContainer) homeContainer.innerHTML = list.slice(0, 3).map(m => `
        <div class="eco-card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-start">
                <div><h6 class="fw-bold mb-1">${m.name}</h6>
                <p class="small text-muted mb-1"><i class="fa-solid fa-location-dot me-1 text-fresh"></i>${m.location} · ${milesText(m.distance)}</p>
                <p class="small text-dark fw-semibold mb-0"><i class="fa-regular fa-clock me-1 text-warning"></i>${m.days}</p></div>
                <span class="badge bg-mint text-forest">${m.farmersCount}</span>
            </div></div>`).join('');

    if (fullContainer) {
        fullContainer.innerHTML = list.length ? list.map(marketCardHTML).join('') : emptyStateHTML('No markets matched your search.');
        const lbl = document.getElementById('marketsCountLabel');
        if (lbl) lbl.textContent = `${list.length} market${list.length === 1 ? '' : 's'}`;
    }
}

function initMarkets() { renderMarkets(); }

function renderFarmersDirectory() {
    const container = document.getElementById('farmersDirectoryGrid'); if (!container) return;
    const search = (document.getElementById('farmerSearchInput') || { value: '' }).value.trim().toLowerCase();
    let list = sampleFarmers;
    if (search) list = list.filter(f => (f.name + ' ' + f.specialty + ' ' + f.location).toLowerCase().includes(search));

    container.innerHTML = list.length ? list.map(f => `
        <div class="col-md-4">
            <div class="eco-card h-100 p-4 text-center">
                <img src="${f.image}" class="rounded-circle mb-3 border border-3 border-success" style="width:96px;height:96px;object-fit:cover;" alt="${f.name}" loading="lazy">
                <h5 class="fw-bold mb-1">${f.name}</h5>
                <p class="small text-muted mb-1"><i class="fa-solid fa-map-pin me-1"></i>${f.location}</p>
                <p class="small text-dark fw-semibold mb-3">${f.specialty}</p>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <span class="rating text-gold"><i class="fa-solid fa-star"></i> ${f.rating.toFixed(1)}</span>
                    <span class="badge bg-mint text-forest"><i class="fa-solid fa-circle-check me-1"></i>Verified</span></div>
                <div class="d-flex gap-2">
                    <button class="btn btn-eco-primary btn-sm flex-fill" onclick="location.href=(window.ML_URLS?.products || '/products')">View Produce</button>
                    <button class="btn btn-eco-outline btn-sm" onclick="showToast('Message request sent to ${f.name}!', 'success')" title="Contact farmer"><i class="fa-solid fa-envelope"></i></button>
                </div>
            </div></div>`).join('') : emptyStateHTML('No farmers matched your search.');

    const lbl = document.getElementById('farmersCountLabel');
    if (lbl) lbl.textContent = `${list.length} farmer${list.length === 1 ? '' : 's'}`;
}

function initFarmers() { renderFarmersDirectory(); }

/* ==========================================================
   HOME PAGE
   ========================================================== */
function renderHomeStats() {
    const text = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    text('heroStatFarmers', ML_BACKEND.stats?.farmers ?? sampleFarmers.length);
    text('heroStatMarkets', ML_BACKEND.stats?.markets ?? sampleMarkets.length);
    text('heroStatProducts', ML_BACKEND.stats?.products ?? state.products.length);
    text('heroStatCustomers', ML_BACKEND.stats?.customers ?? '1.4k');
}

function renderHomeFeatured() {
    const grid = document.getElementById('featuredProductsGrid'); if (!grid) return;
    const featured = [...state.products].sort((a, b) => b.rating - a.rating).slice(0, 8);
    renderProductsGrid(featured, 'featuredProductsGrid');
}

function initHome() {
    renderHomeStats();
    renderCategories();
    renderHomeFeatured();
    renderMarkets();
}

/* ==========================================================
   QUICK VIEW
   ========================================================== */
let quickViewTarget = null;
function openQuickView(productId) {
    const product = state.products.find(p => p.id === productId); if (!product) return;
    quickViewTarget = productId;
    const isFav = state.favorites.includes(productId);
    document.getElementById('quickViewBody').innerHTML = `
        <div class="row g-0">
            <div class="col-md-6"><img src="${product.image}" alt="${product.name}" class="w-100 h-100" style="object-fit:cover; min-height:260px;"></div>
            <div class="col-md-6 p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-mint text-forest px-3 py-2">${product.category}</span>
                    <button class="btn btn-light btn-sm rounded-circle" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button></div>
                <h3 class="fw-bold mb-1">${product.name}</h3>
                <small class="text-muted mb-2"><i class="fa-solid fa-tractor me-1 text-fresh"></i>${product.farmer}</small>
                <div class="d-flex gap-3 mb-3">
                    <span class="rating text-gold fw-bold"><i class="fa-solid fa-star"></i> ${product.rating.toFixed(1)}</span>
                    <span class="text-muted small"><i class="fa-solid fa-boxes-stacked me-1"></i>${product.stock} in stock</span></div>
                <div class="fs-3 fw-bold text-forest mb-3">${fmtMoney(product.price)} <small class="fs-6 text-muted fw-normal">/ ${product.unit}</small></div>
                <p class="text-muted small mb-3">Harvested fresh for your pre-order and collected directly from the farmer at the market stall on pickup day.</p>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <label class="form-label fw-bold mb-0">Qty</label>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary" onclick="changeQuickQty(-1)"><i class="fa-solid fa-minus"></i></button>
                        <span class="btn btn-sm btn-light fw-bold" style="min-width:44px;" id="quickViewQty">1</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="changeQuickQty(1)"><i class="fa-solid fa-plus"></i></button></div></div>
                <div class="d-flex gap-2 mt-auto pt-2">
                    <button class="btn btn-eco-primary flex-fill" onclick="quickViewAdd()"><i class="fa-solid fa-plus me-1"></i>Add to Cart</button>
                    <button class="btn ${isFav ? 'btn-danger' : 'btn-outline-danger'}" onclick="quickViewFav()" id="quickViewFavBtn"><i class="fa-solid fa-heart"></i></button></div>
            </div></div>`;
    new bootstrap.Modal(document.getElementById('quickViewModal')).show();
}
function changeQuickQty(delta) {
    const el = document.getElementById('quickViewQty');
    const product = state.products.find(p => p.id === quickViewTarget);
    let q = parseInt(el.textContent) + delta;
    q = Math.max(1, Math.min(product ? product.stock : 99, q));
    el.textContent = q;
}
function quickViewAdd() { const qty = parseInt(document.getElementById('quickViewQty').textContent); addToCart(quickViewTarget, qty); document.getElementById('quickViewQty').textContent = '1'; }
function quickViewFav() {
    toggleFavorite(quickViewTarget);
    const btn = document.getElementById('quickViewFavBtn'); if (!btn) return;
    btn.className = 'btn ' + (state.favorites.includes(quickViewTarget) ? 'btn-danger' : 'btn-outline-danger');
}

/* ==========================================================
   FAVORITES
   ========================================================== */
async function toggleFavorite(prodId) {
    if(!ML_BACKEND.authenticated){window.location.href=ML_URLS.login;return;}
    try{const payload=await mlPost(`${ML_URLS.favorites}/${prodId}`,{});const id=Number(prodId),idx=state.favorites.indexOf(id);if(payload.favorite&&idx===-1)state.favorites.push(id);if(!payload.favorite&&idx>-1)state.favorites.splice(idx,1);saveState();if(document.getElementById('featuredProductsGrid'))renderHomeFeatured();if(document.getElementById('mainProductsGrid'))applyProductFilters();const favPanel=document.querySelector('#custTabFavorites'),favGrid=document.getElementById('customerFavoritesGrid');if(favGrid&&favPanel&&!favPanel.classList.contains('d-none'))renderCustomerFavorites();showToast(payload.favorite?'Saved to favorites.':'Removed from favorites.','success');}catch(e){showToast(e.message,'error');}
}
/* ==========================================================
   CUSTOMER DASHBOARD
   ========================================================== */
function switchCustomerTab(tabName) {
    document.querySelectorAll('.customer-tab-content').forEach(c => c.classList.add('d-none'));
    document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
    const els = { orders: 'custTabOrders', favorites: 'custTabFavorites', profile: 'custTabProfile' };
    const panel = document.getElementById(els[tabName]); if (panel) panel.classList.remove('d-none');
    const link = document.querySelector(`.sidebar-link[data-tab="${tabName}"]`); if (link) link.classList.add('active');
}

function initDashboard() {
    renderCustomerStats();
    renderCustomerOrders();
    renderCustomerFavorites();
    switchCustomerTab('orders');
}

function renderCustomerStats() {
    const container = document.getElementById('customerStatCards'); if (!container) return;
    const activeOrders = state.orders.filter(o => o.status !== 'Picked Up');
    const totalSpent = state.orders.reduce((s, o) => s + o.total, 0);
    container.innerHTML = [
        { icon: 'fa-box-open', cls: 'green', label: 'Active Pre-Orders', value: activeOrders.length },
        { icon: 'fa-clock-rotate-left', cls: 'blue', label: 'Total Orders', value: state.orders.length },
        { icon: 'fa-heart', cls: 'red', label: 'Favorites Saved', value: state.favorites.length },
        { icon: 'fa-sack-dollar', cls: 'gold', label: 'Lifetime Spend', value: fmtMoney(totalSpent) }
    ].map(s => `<div class="col-6 col-lg-3">
        <div class="stat-card"><div class="stat-icon ${s.cls}"><i class="fa-solid ${s.icon}"></i></div>
        <div><small>${s.label}</small><h3 class="mb-0">${s.value}</h3></div></div></div>`).join('');
}

function renderCustomerOrders() {
    const container = document.getElementById('customerOrdersContainer'); if (!container) return;
    if (state.orders.length === 0) { container.innerHTML = emptyStateHTML('No pre-orders placed yet. Head to the marketplace to start!'); return; }
    container.innerHTML = state.orders.map(ord => `
        <div class="eco-card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <div><span class="fw-bold text-forest">${ord.id}</span><small class="text-muted ms-2"><i class="fa-regular fa-calendar me-1"></i>${ord.date}</small></div>
                <span class="status-pill badge ${ord.status === 'Picked Up' ? 'bg-mint text-forest' : 'bg-warning text-dark'}">${ord.status}</span></div>
            <p class="small mb-1"><strong><i class="fa-solid fa-location-dot me-1 text-fresh"></i>Pickup:</strong> ${ord.market}</p>
            <p class="small text-muted mb-2"><strong>Items:</strong> ${ord.items}</p>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top flex-wrap gap-2">
                <span class="fw-bold text-forest">Total: ${fmtMoney(ord.total)} <small class="text-muted fw-normal">(pay at pickup)</small></span>
                <div class="d-flex gap-2">
                    ${ord.status === 'Placed' ? `<button class="btn btn-sm btn-eco-outline" onclick="showToast('Order modification requested!', 'info')">Modify</button>` : ''}
                    ${ord.status !== 'Picked Up' ? `<button class="btn btn-sm btn-outline-danger" onclick="showToast('Cancellation request submitted!', 'info')">Cancel</button>` : ''}
                </div></div></div>`).join('');
}

function renderCustomerFavorites() {
    const favProducts = state.products.filter(p => state.favorites.includes(p.id));
    renderProductsGrid(favProducts, 'customerFavoritesGrid');
}

/* ==========================================================
   FARMER WORKSPACE
   ========================================================== */
function switchFarmerSubTab(tabName) {
    document.querySelectorAll('.farmer-tab-panel').forEach(p => p.classList.add('d-none'));
    document.querySelectorAll('#farmerTabs .nav-link').forEach(l => l.classList.remove('active'));
    const panels = { orders: 'farmerTabOrders', inventory: 'farmerTabInventory', slots: 'farmerTabSlots' };
    const panel = document.getElementById(panels[tabName]); if (panel) panel.classList.remove('d-none');
    const link = document.querySelector(`#farmerTabs .nav-link[data-tab="${tabName}"]`); if (link) link.classList.add('active');
}

function initFarmer() { renderFarmerMetrics(); renderFarmerOrdersTable(); renderFarmerProductsTable(); switchFarmerSubTab('orders'); }

function renderFarmerMetrics() {
    const active = state.orders.filter(o => o.status !== 'Rejected');
    const text = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    text('farmerMetricOrders', active.length);
    text('farmerMetricRevenue', fmtMoney(active.reduce((s, o) => s + o.total, 0)));
    text('farmerMetricProducts', state.products.length);
}

function renderFarmerOrdersTable() {
    const tbody = document.getElementById('farmerOrdersTableBody'); if (!tbody) return;
    const countEl = document.getElementById('farmerOrdersCount');
    if (state.orders.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">No pre-orders yet.</td></tr>`;
        if (countEl) countEl.textContent = '0 pending';
        return;
    }
    const pending = state.orders.filter(o => o.status === 'Placed').length;
    if (countEl) countEl.textContent = pending + ' pending';
    tbody.innerHTML = state.orders.map(o => {
        let action = '';
        if (o.status === 'Placed') action = `<div class="d-flex gap-1"><button class="btn btn-sm btn-success" onclick="acceptFarmerOrder('${o.id}')"><i class="fa-solid fa-check me-1"></i>Accept</button><button class="btn btn-sm btn-outline-danger" onclick="rejectFarmerOrder('${o.id}')"><i class="fa-solid fa-xmark"></i></button></div>`;
        else if (o.status === 'Accepted') action = `<button class="btn btn-sm btn-eco-outline" onclick="markPickedUp('${o.id}')"><i class="fa-solid fa-hand-holding-heart me-1"></i>Picked Up</button>`;
        const cls = o.status === 'Accepted' ? 'bg-info text-white' : (o.status === 'Picked Up' ? 'bg-mint text-forest' : 'bg-warning text-dark');
        return `<tr><td class="fw-bold">${o.id}</td><td>${o.customer || 'Sarah Jenkins'}</td><td>Sat, 09:30 AM</td>
            <td class="small">${o.items}</td><td class="fw-bold">${fmtMoney(o.total)}</td>
            <td><span class="badge ${cls}">${o.status}</span></td><td>${action}</td></tr>`;
    }).join('');
}

function acceptFarmerOrder(id) { const o = state.orders.find(x => x.id === id); if (o) { o.status = 'Accepted'; saveState(); showToast(`Order ${id} accepted — customer notified`, 'success'); } renderFarmerOrdersTable(); renderFarmerMetrics(); }
function rejectFarmerOrder(id) { const o = state.orders.find(x => x.id === id); if (o) { o.status = 'Rejected'; saveState(); showToast(`Order ${id} rejected.`, 'error'); } renderFarmerOrdersTable(); renderFarmerMetrics(); }
function markPickedUp(id) { const o = state.orders.find(x => x.id === id); if (o) { o.status = 'Picked Up'; saveState(); showToast(`Order ${id} picked up! 🎉`, 'success'); } renderFarmerOrdersTable(); renderFarmerMetrics(); }

function renderFarmerProductsTable() {
    const tbody = document.getElementById('farmerProductsTableBody'); if (!tbody) return;
    if (state.products.length === 0) { tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">No products yet — add your first harvest.</td></tr>`; return; }
    tbody.innerHTML = state.products.map(p => `
        <tr><td><div class="d-flex align-items-center gap-2"><img src="${p.image}" class="rounded" style="width:42px;height:42px;object-fit:cover;" alt=""><span class="fw-bold">${p.name}</span></div></td>
        <td>${p.category}</td><td>${fmtMoney(p.price)} / ${p.unit}</td><td><span class="fw-bold">${p.stock}</span></td>
        <td><span class="badge ${p.stock > 0 ? 'bg-mint text-forest' : 'bg-danger text-white'}">${p.stock > 0 ? 'Available' : 'Sold Out'}</span></td>
        <td><button class="btn btn-sm btn-outline-danger" onclick="deleteFarmerProduct(${p.id})" title="Delete"><i class="fa-solid fa-trash"></i></button></td></tr>`).join('');
}

function openAddProductModal() { new bootstrap.Modal(document.getElementById('addProductModal')).show(); }

function saveFarmerProduct() {
    const name = document.getElementById('newProdName').value.trim();
    const category = document.getElementById('newProdCategory').value;
    const unit = document.getElementById('newProdUnit').value.trim();
    const price = parseFloat(document.getElementById('newProdPrice').value);
    const stock = parseInt(document.getElementById('newProdStock').value);
    const image = document.getElementById('newProdImage').value.trim() || IMG.basket;
    if (!name || !unit || isNaN(price) || price < 0 || isNaN(stock) || stock < 1) { showToast('Please fill all required product fields.', 'error'); return; }
    state.products.unshift({ id: Date.now(), name, category, farmer: 'Green Valley Farm', price, unit, stock, rating: 5.0, image });
    saveState();
    if (bootstrap.Modal.getInstance(document.getElementById('addProductModal'))) bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
    const form = document.getElementById('addProductForm'); if (form) form.reset();
    showToast('New harvest product published to the marketplace!', 'success');
    renderFarmerProductsTable(); renderFarmerMetrics();
}

function deleteFarmerProduct(id) {
    state.products = state.products.filter(p => p.id !== id);
    saveState(); renderFarmerProductsTable(); renderFarmerMetrics();
    showToast('Product removed from catalog.', 'info');
}

/* ==========================================================
   ADMIN PORTAL
   ========================================================== */
function initAdmin() { renderAdminMetrics(); renderAdminQueues(); renderAdminOrders(); renderAnnouncements(); }

function renderAdminMetrics() {
    const text = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v; };
    text('adminMetricFarmers', sampleFarmers.length + state.farmerQueue.filter(f => f.status === 'verified').length);
    text('adminMetricMarkets', sampleMarkets.length);
    text('adminMetricCustomers', (1420 + state.orders.length).toLocaleString());
    text('adminMetricVolume', fmtMoney(state.orders.reduce((s, o) => s + o.total, 0)));
}

function renderAdminQueues() {
    const tbody = document.getElementById('adminFarmersTable'); if (!tbody) return;
    const countEl = document.getElementById('adminQueueCount');
    const queue = state.farmerQueue;
    const pending = queue.filter(f => f.status === 'pending').length;
    if (countEl) countEl.textContent = pending + ' pending';
    tbody.innerHTML = queue.map(f => {
        let action = '';
        if (f.status === 'pending') action = `<div class="d-flex gap-1"><button class="btn btn-sm btn-success" onclick="verifyFarmer('${f.name}')"><i class="fa-solid fa-check me-1"></i>Approve</button><button class="btn btn-sm btn-outline-danger" onclick="rejectFarmer('${f.name}')"><i class="fa-solid fa-xmark"></i></button></div>`;
        else action = `<button class="btn btn-sm btn-outline-secondary" onclick="suspendFarmer('${f.name}')" title="Suspend"><i class="fa-solid fa-ban"></i></button>`;
        const badge = f.status === 'verified' ? '<span class="badge bg-mint text-forest">Verified</span>' : '<span class="badge bg-warning text-dark">Pending</span>';
        return `<tr><td><strong>${f.name}</strong></td><td>${f.owner}</td><td>${f.market}</td><td>${badge}</td><td>${action}</td></tr>`;
    }).join('');
}

function verifyFarmer(name) { const f = state.farmerQueue.find(x => x.name === name); if (f) { f.status = 'verified'; showToast(`${name} approved and verified!`, 'success'); } renderAdminQueues(); renderAdminMetrics(); }
function rejectFarmer(name) { state.farmerQueue = state.farmerQueue.filter(x => x.name !== name); showToast(`${name} request rejected.`, 'error'); renderAdminQueues(); renderAdminMetrics(); }
function suspendFarmer(name) { const f = state.farmerQueue.find(x => x.name === name); if (f) { f.status = 'pending'; showToast(`${name} suspended — review required.`, 'info'); } renderAdminQueues(); renderAdminMetrics(); }

function renderAdminOrders() {
    const tbody = document.getElementById('adminRecentOrdersTable'); if (!tbody) return;
    if (state.orders.length === 0) { tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">No orders on the platform yet.</td></tr>`; return; }
    const cls = { Placed: 'bg-warning text-dark', Accepted: 'bg-info text-white', Rejected: 'bg-danger text-white', 'Picked Up': 'bg-mint text-forest' };
    tbody.innerHTML = state.orders.map(o => `<tr><td class="fw-bold">${o.id}</td><td>${o.customer || 'Sarah Jenkins'}</td><td>${o.market}</td>
        <td class="small">${o.items}</td><td class="fw-bold">${fmtMoney(o.total)}</td><td><span class="badge ${cls[o.status] || 'bg-light'}">${o.status}</span></td></tr>`).join('');
}

function publishAnnouncement() {
    const title = document.getElementById('announcementTitle').value.trim();
    const message = document.getElementById('announcementMessage').value.trim();
    if (!title || !message) { showToast('Announcement needs a title and message.', 'error'); return; }
    state.announcements.unshift({ title, message, date: new Date().toISOString().split('T')[0] });
    const form = document.getElementById('announcementForm'); if (form) form.reset();
    renderAnnouncements();
    showToast('Announcement broadcast to all customers!', 'success');
}

function renderAnnouncements() {
    const list = document.getElementById('announcementsList'); if (!list) return;
    list.innerHTML = state.announcements.length
        ? state.announcements.map(a => `
            <div class="p-3 border-start border-4 border-success mb-3 bg-light rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <b class="text-forest">${a.title}</b>
                    <small class="text-muted">${a.date}</small></div>
                <p class="small text-muted mb-0 mt-1">${a.message}</p></div>`).join('')
        : '<p class="text-muted small mb-0">No active announcements yet.</p>';
}

/* ==========================================================
   AI ASSISTANT
   ========================================================== */
const AI_QUICK_REPLIES = ['Market hours?', 'How do I pay?', 'Is produce fresh?', 'Where to pick up?'];
function renderQuickReplies() {
    const box = document.getElementById('aiQuickReplies'); if (!box) return;
    box.innerHTML = AI_QUICK_REPLIES.map(r => `<span class="chip" onclick="sendAIMessage('${r}')">${r}</span>`).join('');
}
function toggleAIChat() {
    const el = document.getElementById('aiChatWindow');
    el.style.display = el.style.display === 'flex' ? 'none' : 'flex';
    const body = document.getElementById('aiChatMessages'); if (body) body.scrollTop = body.scrollHeight;
}
function sendAIMessage(preset) {
    const input = document.getElementById('aiInput');
    const text = (preset || input.value).trim(); if (!text) return;
    const chat = document.getElementById('aiChatMessages');
    chat.innerHTML += `<div class="chat-msg user align-self-end">${escapeHTML(text)}</div>`;
    input.value = '';
    chat.scrollTop = chat.scrollHeight;
    setTimeout(() => {
        let reply = "MarketLink connects you directly with local farmers. Pre-orders are picked up on weekends and payment is collected in person.";
        const q = text.toLowerCase();
        if (q.includes('hour') || q.includes('time') || q.includes('schedule') || q.includes('when')) reply = "Most markets run Saturdays 8 AM - 1 PM or Sundays 9 AM - 2 PM. Check the Markets tab for exact stall schedules!";
        else if (q.includes('pay') || q.includes('card') || q.includes('cash')) reply = "There are no online payment fees — you pay the farmer directly in cash or card when you pick up your fresh basket.";
        else if (q.includes('fresh') || q.includes('organic')) reply = "Everything is farm-fresh! Growers harvest only what was pre-ordered, so items reach you within hours of picking.";
        else if (q.includes('pickup') || q.includes('pick up') || q.includes('stall')) reply = "After checkout you'll get a reserved pickup window. Just bring the order number to the farmer's stall at the market.";
        else if (q.includes('apple') || q.includes('tomato') || q.includes('honey') || q.includes('milk')) reply = "That's in-stock this week! Head to the Products marketplace and filter by category to reserve it.";
        else if (q.includes('hi') || q.includes('hello') || q.includes('hey')) reply = "Hello there! 👋 Ask me about market schedules, how pickup works, or what's fresh this week.";
        chat.innerHTML += `<div class="chat-msg bot">${escapeHTML(reply)}</div>`;
        chat.scrollTop = chat.scrollHeight;
    }, 550);
}

/* ---------- Map helper (home) ---------- */
function showMarketMapDetail(title) {
    const market = sampleMarkets.find(m => m.name === title) || {};
    const box = document.getElementById('mapDetailBox'); if (!box) return;
    document.getElementById('mapDetailTitle').textContent = title;
    document.getElementById('mapDetailSub').textContent = market.days || 'Weekend market';
    box.style.display = 'block';
}

/* ---------- Shared footer / nav assignments happen in page scripts ---------- */