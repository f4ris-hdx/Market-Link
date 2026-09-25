<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): RedirectResponse|View
    {
        $user = auth()->user();
        if ($user->isFarmer()) return redirect()->route('farmer.dashboard');
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');

        $orders = Order::with(['items', 'market'])
            ->where('user_id', $user->id)
            ->orderByDesc('placed_at')
            ->get();
        $favorites = $user->favorites()->with('product.farmer')->get();
        $products = Product::with(['farmer', 'category'])->where('stock', '>', 0)->orderByDesc('created_at')->get();

        $frontendData = app(PublicController::class)->homeDataForAuthenticatedUser();
        $frontendData['orders'] = $orders->map(fn (Order $o) => [
            'id' => $o->order_number,
            'date' => optional($o->placed_at)->format('Y-m-d'),
            'market' => ($o->market?->name ?? 'Pickup market').' ('.$o->slot.')',
            'total' => (float) $o->total,
            'items' => $o->items->map(fn ($i) => $i->qty.'x '.$i->name)->implode(', '),
            'status' => $o->status,
            'customer' => $user->name,
        ])->values();
        $frontendData['favorites'] = $favorites->pluck('product_id')->map(fn ($id) => (int) $id)->values();

        return view('frontend.dashboard', compact('frontendData'));
    }

    public function toggleFavorite(Product $product): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $exists = $user->favorites()->where('product_id', $product->id)->exists();
        if ($exists) $user->favorites()->where('product_id', $product->id)->delete();
        else $user->favorites()->create(['product_id' => $product->id]);

        if (request()->expectsJson()) {
            return response()->json(['favorite' => ! $exists]);
        }
        return back()->with('status', $exists ? 'Removed from favorites.' : 'Added to favorites.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'phone' => ['nullable', 'string', 'max:30']]);
        auth()->user()->update($data);
        return back()->with('status', 'Profile updated.');
    }

    public function cancelOrder(Order $order): RedirectResponse
    {
        abort_unless($order->user_id === auth()->id(), 403);
        if (! in_array($order->status, ['Placed', 'Accepted'], true)) {
            return back()->with('error', 'This order can no longer be cancelled.');
        }
        $order->update(['status' => 'Cancelled']);
        foreach ($order->items as $item) {
            if ($item->product) $item->product->increment('stock', $item->qty);
        }
        return back()->with('status', "Order {$order->order_number} cancelled.");
    }
}
