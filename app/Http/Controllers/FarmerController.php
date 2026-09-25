<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Farmer;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return view('farmer.dashboard', compact('farmer', 'orders', 'revenue', 'ordersCount', 'productCount'));
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
        if ($redirect = $this->approvalRedirect($farmer)) return $redirect;

        return view('farmer.product-form', ['farmer' => $farmer, 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) return $redirect;

        $farmer->products()->create(
            $request->safe()->only(['name', 'description', 'category_id', 'unit', 'price', 'stock', 'image'])
        );

        return redirect()->route('farmer.products')->with('status', 'Product added.');
    }

    public function edit(Product $product): View|RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) return $redirect;

        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        return view('farmer.product-form', ['product' => $product, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) return $redirect;

        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        $product->update($request->safe()->only(['name', 'description', 'category_id', 'unit', 'price', 'stock', 'image']));

        return redirect()->route('farmer.products')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $farmer = $this->profile();
        if ($redirect = $this->approvalRedirect($farmer)) return $redirect;

        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        $product->delete();

        return back()->with('status', 'Product removed.');
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
