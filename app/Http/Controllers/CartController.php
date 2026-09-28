<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $items = $this->cartItems();

        return view('cart.index', $items);
    }

    public function add(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $qty = (int) $request->integer('qty', 1);

        if ($qty < 1) {
            $qty = 1;
        }

        if ($qty > $product->stock) {
            if ($request->expectsJson()) {
                return response()->json(['message' => "Only {$product->stock} in stock for {$product->name}."], 422);
            }

            return back()->with('error', "Only {$product->stock} in stock for {$product->name}.");
        }

        $cart = session()->get('cart', []);
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + $qty, $product->stock);
        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'cart' => $cart]);
        }

        return back()->with('status', "Added {$product->name} to your basket.");
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $quantities = $request->validate([
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'integer', 'min:0'],
        ])['qty'];

        $cart = session()->get('cart', []);

        foreach ($quantities as $productId => $qty) {
            $product = Product::find($productId);

            if ($product === null || $qty === 0) {
                unset($cart[$productId]);

                continue;
            }

            $cart[$productId] = min($qty, $product->stock);
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'cart' => $cart]);
        }

        return back()->with('status', 'Basket updated.');
    }

    public function remove(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'cart' => $cart]);
        }

        return back()->with('status', "Removed {$product->name} from your basket.");
    }

    public function checkout(): View|RedirectResponse
    {
        $items = $this->cartItems();

        if (empty($items['lines'])) {
            return redirect()->route('cart.index')->with('error', 'Your basket is empty.');
        }

        $marketIds = $this->commonMarketIds(session()->get('cart', []));
        if ($marketIds === []) {
            return redirect()->route('cart.index')->with('error', 'Your cart items do not share an approved pickup market. Place separate orders for products from different markets.');
        }
        $markets = Market::whereIn('id', $marketIds)->orderBy('name')->get();

        return view('cart.checkout', $items + ['markets' => $markets]);
    }

    public function place(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'pickup_name' => ['required', 'string', 'max:255'],
            'pickup_phone' => ['nullable', 'string', 'max:30'],
            'slot' => ['required', 'string', 'max:255'],
            'pickup_date' => ['nullable', 'date'],
        ]);

        $cart = session()->get('cart', []);
        if ($cart === []) {
            return back()->with('error', 'Your basket is empty.');
        }

        $marketIds = $this->commonMarketIds($cart);
        if ($marketIds === []) {
            return back()->with('error', 'Your cart items do not share an approved pickup market. Place separate orders for products from different markets.');
        }
        $data['market_id'] = $request->validate([
            'market_id' => ['required', 'integer', Rule::in($marketIds)],
        ])['market_id'];

        $products = Product::with('farmer')->whereIn('id', array_keys($cart))->get();

        if ($products->isEmpty()) {
            return back()->with('error', 'Your basket is empty.');
        }

        try {
            $order = DB::transaction(function () use ($data, $cart): Order {
                $products = Product::with([
                    'farmer',
                    'markets' => fn ($query) => $query->wherePivot('status', 'approved'),
                ])->whereIn('id', array_keys($cart))->lockForUpdate()->get();
                if ($products->count() !== count($cart)) {
                    throw new \RuntimeException('One or more products are no longer available.');
                }
                foreach ($products as $product) {
                    $qty = (int) ($cart[$product->id] ?? 0);
                    if ($qty < 1 || $qty > $product->stock) {
                        throw new \RuntimeException("Insufficient stock for {$product->name}.");
                    }
                    if (! $product->markets->contains(fn (Market $market): bool => (int) $market->id === (int) $data['market_id'])) {
                        throw new \RuntimeException("{$product->name} is not available at the selected pickup market.");
                    }
                }
                $total = $products->sum(fn ($product) => $product->price * $cart[$product->id]);

                $order = Order::create([
                    'order_number' => 'MK-'.strtoupper(Str::random(8)),
                    'user_id' => auth()->id(),
                    'market_id' => $data['market_id'] ?? null,
                    'slot' => $data['slot'],
                    'pickup_date' => $data['pickup_date'] ?? null,
                    'pickup_name' => $data['pickup_name'],
                    'pickup_phone' => $data['pickup_phone'],
                    'total' => $total,
                    'status' => 'Placed',
                    'placed_at' => now()->toDateString(),
                ]);

                foreach ($products as $product) {
                    $qty = $cart[$product->id];

                    $order->items()->create([
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'unit' => $product->unit,
                        'price' => $product->price,
                        'qty' => $qty,
                    ]);

                    $product->decrement('stock', $qty);
                }

                return $order;
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        session()->forget('cart');

        Mail::to(auth()->user()->email)->send(new OrderPlacedMail($order->load('user')));

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'order_number' => $order->order_number]);
        }

        return redirect()->route('dashboard')->with('status', "Order {$order->order_number} placed successfully.");
    }

    /**
     * Resolve and clean the session cart.
     *
     * @return array<string, mixed>
     */
    private function cartItems(): array
    {
        $cart = session()->get('cart', []);
        $products = Product::with([
            'farmer',
            'markets' => fn ($query) => $query->wherePivot('status', 'approved'),
        ])->whereIn('id', array_keys($cart))->get();

        $lines = $products->map(fn (Product $product): array => [
            'product' => $product,
            'qty' => $cart[$product->id],
            'total' => $product->price * $cart[$product->id],
        ]);

        $total = $lines->sum('total');

        return ['lines' => $lines, 'total' => $total, 'market_ids' => $this->commonMarketIds($cart)];
    }

    /**
     * @param  array<int|string, int>  $cart
     * @return array<int, int>
     */
    private function commonMarketIds(array $cart): array
    {
        if ($cart === []) {
            return [];
        }

        $products = Product::query()
            ->with(['markets' => fn ($query) => $query->wherePivot('status', 'approved')])
            ->whereIn('id', array_keys($cart))
            ->get();

        if ($products->count() !== count($cart)) {
            return [];
        }

        $commonMarketIds = null;
        foreach ($products as $product) {
            $productMarketIds = $product->markets->pluck('id')->map(fn ($id): int => (int) $id)->all();
            $commonMarketIds = $commonMarketIds === null
                ? $productMarketIds
                : array_values(array_intersect($commonMarketIds, $productMarketIds));
        }

        return $commonMarketIds ?? [];
    }
}
