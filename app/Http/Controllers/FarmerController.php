<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Mail\OrderAcceptedMail;
use App\Models\Category;
use App\Models\Farmer;
use App\Models\FarmerMarketChangeRequest;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class FarmerController extends Controller
{
    private function profile(): Farmer
    {
        $farmer = Farmer::query()->where('user_id', auth()->id())->with('products')->first();

        if (! $farmer) {
            abort(403, 'Your farmer profile is not available. Please contact an administrator.');
        }

        return $farmer;
    }

    private function approvalRedirect(Farmer $farmer): ?RedirectResponse
    {
        if ($farmer->status !== 'verified') {
            return redirect()->route('farmer.dashboard')->with('error', 'Your farmer account is pending administrator approval. You can update your profile, but product publishing is available after approval.');
        }

        return null;
    }

    public function index(): View
    {
        $farmer = $this->profile();

        $productIds = $farmer->products()->pluck('id');

        $orders = Order::query()
            ->whereHas('items', fn ($q) => $q->whereIn('product_id', $productIds))
            ->with(['items.product.farmer', 'user'])
            ->orderByDesc('placed_at')
            ->get();

        $revenue = DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->sum(DB::raw('qty * price'));

        $ordersCount = $orders->count();
        $productCount = $productIds->count();
        $reviews = $farmer->reviews()
            ->with(['user', 'product'])
            ->where('status', 'published')
            ->latest()
            ->limit(12)
            ->get();
        $reviewCount = $farmer->reviews()->where('status', 'published')->count();
        $averageRating = (float) ($farmer->reviews()->where('status', 'published')->avg('rating') ?? $farmer->rating);
        $markets = Market::query()->orderBy('name')->get(['id', 'name', 'location']);
        $marketChangeRequest = $farmer->marketChangeRequests()
            ->with(['currentMarket:id,name', 'requestedMarket:id,name'])
            ->latest()
            ->first();

        return view('farmer.dashboard', compact('farmer', 'orders', 'revenue', 'ordersCount', 'productCount', 'reviews', 'reviewCount', 'averageRating', 'markets', 'marketChangeRequest'));
    }

    public function products(): View
    {
        $farmer = $this->profile();
        $products = $farmer->products()->with('category')->orderByDesc('created_at')->get();

        return view('farmer.products', compact('farmer', 'products'));
    }

    public function create(): View|RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) {
            return $redirect;
        }

        return view('farmer.product-form', [
            'farmer' => $farmer,
            'categories' => Category::orderBy('name')->get(),
            'markets' => Market::orderBy('name')->get(),
            'selectedMarketId' => $farmer->market_id,
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) {
            return $redirect;
        }

        $data = $request->safe()->only(['name', 'description', 'category_id', 'unit', 'price', 'stock', 'image', 'market_id']);
        $marketId = $data['market_id'];
        unset($data['market_id']);

        $product = $farmer->products()->create($data);
        $product->markets()->sync([
            $marketId => [
                'status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => null,
            ],
        ]);

        return redirect()->route('farmer.products')->with('status', 'Product added.');
    }

    public function edit(Product $product): View|RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) {
            return $redirect;
        }

        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        $product->load('markets');

        return view('farmer.product-form', [
            'farmer' => $farmer,
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'markets' => Market::orderBy('name')->get(),
            'selectedMarketId' => $product->markets->first()?->id ?? $farmer->market_id,
        ]);
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) {
            return $redirect;
        }

        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        $data = $request->safe()->only(['name', 'description', 'category_id', 'unit', 'price', 'stock', 'image', 'market_id']);
        $marketId = $data['market_id'];
        unset($data['market_id']);

        $product->update($data);
        $product->markets()->sync([
            $marketId => [
                'status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => null,
            ],
        ]);

        return redirect()->route('farmer.products')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) {
            return $redirect;
        }

        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        $product->delete();

        return back()->with('status', 'Product removed.');
    }

    public function toggleProductVisibility(Product $product): RedirectResponse
    {
        $farmer = $this->profile();
        abort_unless($product->farmer_id === $farmer->id, 403);

        $product->update(['status' => $product->status === 'approved' ? 'hidden' : 'approved']);

        return back()->with('status', $product->status === 'approved' ? 'Product is visible to customers.' : 'Product is hidden from customers.');
    }

    public function orders(): View
    {
        $farmer = $this->profile();
        $productIds = $farmer->products()->pluck('id');

        $orders = Order::query()
            ->whereHas('items', fn ($q) => $q->whereIn('product_id', $productIds))
            ->with(['items.product.farmer', 'user'])
            ->orderByDesc('placed_at')
            ->paginate(15);

        return view('farmer.orders', compact('farmer', 'orders'));
    }

    public function updateOrder(Request $request, Order $order): RedirectResponse
    {
        $farmer = $this->profile();
        $productIds = $farmer->products()->pluck('id');

        if (! $order->items()->whereIn('product_id', $productIds)->exists()) {
            abort(403);
        }

        $status = $request->validate([
            'status' => ['required', 'in:Accepted,Rejected,Ready,Picked Up'],
        ])['status'];

        $order->update(['status' => $status]);

        if ($status === 'Accepted' && $order->user) {
            Mail::to($order->user->email)->send(new OrderAcceptedMail($order->load('user')));
        }

        return back()->with('status', "Order {$order->order_number} marked as {$status}.");
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'url', 'max:255'],
        ]);
        $farmer = $this->profile();
        $farmer->update($data);
        $farmer->user?->update(['name' => $data['owner_name']]);

        return back()->with('status', 'Farmer profile updated.');
    }

    public function requestMarketChange(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'requested_market_id' => ['required', 'integer', 'exists:markets,id'],
        ]);

        DB::transaction(function () use ($data): void {
            $farmer = Farmer::query()
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->firstOrFail();

            abort_if((int) $farmer->market_id === (int) $data['requested_market_id'], 422, 'That is already your assigned market.');
            abort_if($farmer->marketChangeRequests()->where('status', 'pending')->exists(), 422, 'You already have a pending market change request.');

            FarmerMarketChangeRequest::create([
                'farmer_id' => $farmer->id,
                'current_market_id' => $farmer->market_id,
                'requested_market_id' => $data['requested_market_id'],
                'requested_by' => auth()->id(),
                'status' => 'pending',
            ]);
        });

        return back()->with('status', 'Your market change request was sent to the administrator. Your current market stays active until it is approved.');
    }

    public function slots(): View
    {
        $farmer = $this->profile();

        return view('farmer.slots', compact('farmer'));
    }

    public function updateSlots(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'slots' => ['required', 'string', 'max:255'],
        ]);

        $this->profile()->update($data);

        return back()->with('status', 'Pickup slots updated.');
    }
}
