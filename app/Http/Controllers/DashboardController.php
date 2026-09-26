<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): RedirectResponse|View
    {
        $user = auth()->user();
        if ($user->isFarmer()) {
            return redirect()->route('farmer.dashboard');
        }
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $orders = Order::with(['items', 'market'])
            ->where('user_id', $user->id)
            ->orderByDesc('placed_at')
            ->get();
        $favorites = $user->favorites()->with('product.farmer')->get();
        $favoriteFarmers = $user->favoriteFarmers()->with(['farmer' => fn ($query) => $query->withCount('reviews')])->get();
        $reviewableProducts = Product::with(['farmer', 'category', 'reviews' => fn ($query) => $query->where('user_id', $user->id)])
            ->whereHas('farmer', fn ($query) => $query->where('status', 'verified'))
            ->whereHas('orderItems.order', fn ($query) => $query->where('user_id', $user->id)->where('status', 'Picked Up'))
            ->orderByDesc('created_at')
            ->get();
        $reviewableFarmers = Farmer::with(['market', 'reviews' => fn ($query) => $query->where('user_id', $user->id)])
            ->where('status', 'verified')
            ->whereHas('products.orderItems.order', fn ($query) => $query->where('user_id', $user->id)->where('status', 'Picked Up'))
            ->orderBy('name')
            ->limit(12)
            ->get();
        $products = Product::with(['farmer', 'category'])->where('stock', '>', 0)->orderByDesc('created_at')->get();

        $frontendData = app(PublicController::class)->homeDataForAuthenticatedUser();
        $frontendData['orders'] = $orders->map(fn (Order $o) => [
            'id' => $o->order_number,
            'orderId' => $o->id,
            'date' => optional($o->placed_at)->format('Y-m-d'),
            'market' => ($o->market?->name ?? 'Pickup market').' ('.$o->slot.')',
            'total' => (float) $o->total,
            'items' => $o->items->map(fn ($i) => $i->qty.'x '.$i->name)->implode(', '),
            'status' => $o->status,
            'customer' => $user->name,
        ])->values();
        $frontendData['favorites'] = $favorites->pluck('product_id')->map(fn ($id) => (int) $id)->values();
        $frontendData['favoriteFarmers'] = $favoriteFarmers->map(fn ($favorite) => [
            'id' => $favorite->farmer->id,
            'name' => $favorite->farmer->name,
            'rating' => (float) $favorite->farmer->rating,
            'reviewsCount' => $favorite->farmer->reviews_count,
            'market' => $favorite->farmer->market?->name,
        ])->values();
        $frontendData['reviewableProducts'] = $reviewableProducts->map(fn ($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'farmer' => $product->farmer?->name,
            'rating' => (float) $product->rating,
            'review' => $product->reviews->first() ? [
                'rating' => $product->reviews->first()->rating,
                'review' => $product->reviews->first()->review,
            ] : null,
        ])->values();
        $frontendData['reviewableFarmers'] = $reviewableFarmers->map(fn ($farmer) => [
            'id' => $farmer->id,
            'name' => $farmer->name,
            'rating' => (float) $farmer->rating,
            'market' => $farmer->market?->name,
            'review' => $farmer->reviews->first() ? [
                'rating' => $farmer->reviews->first()->rating,
                'review' => $farmer->reviews->first()->review,
            ] : null,
        ])->values();

        return view('frontend.dashboard', compact('frontendData', 'favoriteFarmers', 'reviewableProducts', 'reviewableFarmers'));
    }

    public function toggleFavorite(Product $product): RedirectResponse|JsonResponse
    {
        $user = auth()->user();
        $exists = $user->favorites()->where('product_id', $product->id)->exists();
        if ($exists) {
            $user->favorites()->where('product_id', $product->id)->delete();
        } else {
            $user->favorites()->create(['product_id' => $product->id]);
        }

        if (request()->expectsJson()) {
            return response()->json(['favorite' => ! $exists]);
        }

        return back()->with('status', $exists ? 'Removed from favorites.' : 'Added to favorites.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
        auth()->user()->update($data);

        return back()->with('status', 'Profile updated.');
    }

    public function cancelOrder(Order $order): RedirectResponse|JsonResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);
        if (! in_array($order->status, ['Placed', 'Accepted', 'Ready'], true)) {
            return back()->with('error', 'This order can no longer be cancelled.');
        }
        $order->update(['status' => 'Cancelled']);
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->qty);
            }
        }

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('status', "Order {$order->order_number} cancelled.");
    }

    public function markPickedUp(Order $order): RedirectResponse|JsonResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless(in_array($order->status, ['Accepted', 'Ready'], true), 422, 'This order is not ready to be marked as picked up.');

        $order->update(['status' => 'Picked Up']);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('status', "Order {$order->order_number} marked as picked up.");
    }

    public function modifyOrder(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless(in_array($order->status, ['Placed', 'Accepted'], true), 422, 'This order can no longer be modified.');

        $data = $request->validate([
            'pickup_name' => ['required', 'string', 'max:255'],
            'pickup_phone' => ['nullable', 'string', 'max:30'],
            'slot' => ['required', 'string', 'max:255'],
        ]);
        $order->update($data);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('status', "Order {$order->order_number} updated.");
    }
}
