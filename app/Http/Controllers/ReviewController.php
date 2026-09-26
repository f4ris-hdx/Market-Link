<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FavoriteFarmer;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function storeProduct(Request $request, Product $product): RedirectResponse
    {
        abort_unless($this->customerPurchasedProduct($product), 403, 'You can review a product after your order has been picked up.');
        $data = $this->validatedReview($request);

        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['farmer_id' => null, ...$data, 'status' => 'published'],
        );

        $this->syncRatings($product, $product->farmer);

        return back()->with('status', 'Your product review was published.');
    }

    public function storeFarmer(Request $request, Farmer $farmer): RedirectResponse
    {
        abort_unless($farmer->status === 'verified', 404);
        abort_unless($this->customerPurchasedFarmer($farmer), 403, 'You can review a farmer after your order has been picked up.');
        $data = $this->validatedReview($request);

        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'farmer_id' => $farmer->id],
            ['product_id' => null, ...$data, 'status' => 'published'],
        );

        $this->syncRatings(null, $farmer);

        return back()->with('status', 'Your farmer review was published.');
    }

    public function toggleFarmerFavorite(Farmer $farmer): RedirectResponse|JsonResponse
    {
        abort_unless($farmer->status === 'verified', 404);

        $favorite = FavoriteFarmer::query()
            ->where('user_id', auth()->id())
            ->where('farmer_id', $farmer->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorite = false;
        } else {
            FavoriteFarmer::create(['user_id' => auth()->id(), 'farmer_id' => $farmer->id]);
            $isFavorite = true;
        }

        if (request()->expectsJson()) {
            return response()->json(['favorite' => $isFavorite]);
        }

        return back()->with('status', $isFavorite ? 'Farmer saved to favorites.' : 'Farmer removed from favorites.');
    }

    /**
     * @return array{rating: int, review: ?string}
     */
    private function validatedReview(Request $request): array
    {
        return $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    private function syncRatings(?Product $product, ?Farmer $farmer): void
    {
        if ($product) {
            $product->update(['rating' => $product->reviews()->where('status', 'published')->avg('rating') ?? $product->rating]);
        }

        if ($farmer) {
            $farmer->update(['rating' => $farmer->reviews()->where('status', 'published')->avg('rating') ?? $farmer->rating]);
        }
    }

    private function customerPurchasedProduct(Product $product): bool
    {
        return OrderItem::query()
            ->where('product_id', $product->id)
            ->whereHas('order', fn ($query) => $query->where('user_id', auth()->id())->where('status', 'Picked Up'))
            ->exists();
    }

    private function customerPurchasedFarmer(Farmer $farmer): bool
    {
        return OrderItem::query()
            ->whereHas('product', fn ($query) => $query->where('farmer_id', $farmer->id))
            ->whereHas('order', fn ($query) => $query->where('user_id', auth()->id())->where('status', 'Picked Up'))
            ->exists();
    }
}
