<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function homeDataForAuthenticatedUser(): array
    {
        return $this->frontendData();
    }

    private function frontendData(): array
    {
        $products = Product::with(['farmer', 'category', 'markets'])
            ->where('stock', '>', 0)
            ->where('status', 'approved')
            ->whereHas('farmer', fn ($query) => $query->where('status', 'verified'))
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'farmerId' => $p->farmer_id,
                'marketIds' => $p->markets->pluck('id')->map(fn ($id) => (int) $id)->values()->all(),
                'category' => $p->category?->name ?? 'Other',
                'farmer' => $p->farmer?->name ?? 'Local Farmer',
                'price' => (float) $p->price,
                'unit' => $p->unit,
                'stock' => (int) $p->stock,
                'rating' => (float) ($p->rating ?? 0),
                'image' => $p->image ?: 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&w=600&q=80',
            ])->values();

        $markets = Market::withCount('farmers')->orderBy('name')->get()->map(fn (Market $m) => [
            'id' => $m->id,
            'name' => $m->name,
            'location' => $m->location,
            'latitude' => $m->latitude,
            'longitude' => $m->longitude,
            'days' => $m->days,
            'farmersCount' => (int) ($m->farmers_count ?: $m->farmers()->count()),
            'distance' => (float) ($m->distance ?? 0),
        ])->values();

        $farmers = Farmer::with('market')->where('status', 'verified')->orderBy('name')->get()->map(fn (Farmer $f) => [
            'id' => $f->id,
            'marketId' => $f->market_id,
            'name' => $f->name,
            'location' => $f->location,
            'specialty' => $f->specialty ?: 'Local farm produce',
            'rating' => (float) ($f->rating ?? 0),
            'image' => $f->image ?: 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=500&q=80',
        ])->values();

        $cart = auth()->check() ? collect(session('cart', []))->mapWithKeys(fn ($qty, $id) => [(int) $id => (int) $qty])->all() : [];
        $favorites = auth()->check() ? auth()->user()->favorites()->pluck('product_id')->map(fn ($id) => (int) $id)->values()->all() : [];

        return [
            'csrf' => csrf_token(),
            'authenticated' => auth()->check(),
            'role' => auth()->user()?->role ?? 'customer',
            'user' => auth()->user() ? ['id' => auth()->id(), 'name' => auth()->user()->name, 'email' => auth()->user()->email, 'phone' => auth()->user()->phone, 'location' => auth()->user()->location, 'latitude' => auth()->user()->latitude, 'longitude' => auth()->user()->longitude] : null,
            'products' => $products,
            'markets' => $markets,
            'farmers' => $farmers,
            'categories' => Category::orderBy('name')->get(['id', 'name', 'slug', 'icon']),
            'cart' => $cart,
            'favorites' => $favorites,
            'stats' => [
                'farmers' => $farmers->count(),
                'markets' => $markets->count(),
                'products' => $products->count(),
                'customers' => User::where('role', 'customer')->count(),
            ],
        ];
    }

    public function home(): View
    {
        return view('frontend.index', ['frontendData' => $this->frontendData()]);
    }

    public function products(Request $request): View
    {
        return view('frontend.products', ['frontendData' => $this->frontendData()]);
    }

    public function markets(): View
    {
        return view('frontend.markets', ['frontendData' => $this->frontendData()]);
    }

    public function farmers(Request $request): View
    {
        return view('frontend.farmers', ['frontendData' => $this->frontendData()]);
    }

    public function about(): View
    {
        return view('frontend.about', ['frontendData' => $this->frontendData()]);
    }

    public function howItWorks(): View
    {
        return view('frontend.how-it-works', ['frontendData' => $this->frontendData()]);
    }
}
