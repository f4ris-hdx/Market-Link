@props(['product', 'favoritesIds'])
<div class="col-sm-6 col-lg-4">
    <div class="product-card">
        <div class="product-media">
            <img src="{{ $product->image ?? 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $product->name }}" loading="lazy">
            <span class="product-cat">{{ $product->category->name }}</span>
            <form method="POST" action="{{ route('favorites.toggle', $product) }}">
                @csrf
                <button type="submit" class="fav-btn {{ $favoritesIds->contains($product->id) ? 'active' : '' }}" title="Save to favorites"><i class="fa-solid fa-heart"></i></button>
            </form>
            @if ($product->stock > 0 && $product->stock <= 8)
                <span class="low-stock-chip"><i class="fa-solid fa-fire"></i> Only {{ $product->stock }} left</span>
            @endif
        </div>
        <div class="product-body">
            <small class="product-farmer"><i class="fa-solid fa-tractor me-1"></i>{{ $product->farmer->name }}</small>
            <h6 class="product-name">{{ $product->name }}</h6>
            <div class="product-meta">
                <span class="rating"><i class="fa-solid fa-star"></i> {{ number_format($product->rating, 1) }}</span>
                <span>{{ $product->stock }} in stock</span>
            </div>
            <div class="product-footer">
                <div class="price"><span class="amount">${{ number_format($product->price, 2) }}</span><small> / {{ $product->unit }}</small></div>
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <input type="hidden" name="qty" value="1">
                    <button type="submit" class="btn btn-eco-primary btn-sm px-3 {{ $product->stock === 0 ? 'disabled' : '' }}" title="Add to cart"><i class="fa-solid fa-plus me-1"></i>Add</button>
                </form>
            </div>
        </div>
    </div>
</div>