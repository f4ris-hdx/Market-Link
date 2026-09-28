<?php

namespace App\Services;

use App\Exceptions\AssistantUnavailableException;
use App\Models\Announcement;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class MarketAssistant
{
    public function answer(string $message): string
    {
        $context = $this->databaseContext();

        if (blank(config('services.gemini.key'))) {
            return $this->fallbackReply($message, $context, 'The AI assistant is offline because no Gemini API key is configured.');
        }

        try {
            return $this->askGemini($message, $context);
        } catch (ConnectionException|RequestException $exception) {
            return $this->fallbackReply($message, $context, 'The AI assistant is temporarily offline. Here are the freshest options available right now.');
        }
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function fallbackReply(string $message, array $context, string $status): string
    {
        $products = collect($context['products'] ?? [])->take(3);
        $markets = collect($context['markets'] ?? [])->take(3);
        $productNames = $products->pluck('name')->filter()->implode(', ');
        $marketNames = $markets->pluck('name')->filter()->implode(', ');

        $parts = [
            $status,
            'Based on the current MarketLink catalog, the freshest options are '.($productNames !== '' ? $productNames : 'seasonal farm produce').'.',
            $marketNames !== '' ? 'Common pickup hubs right now include '.$marketNames.'.' : '',
            'You can browse the products and markets pages to filter by what is closest to you.',
        ];

        return trim(implode(' ', array_filter($parts)));
    }

    /**
     * @return array{summary: array<string, int>, products: array<int, array<string, mixed>>, farmers: array<int, array<string, mixed>>, markets: array<int, array<string, mixed>>, announcements: array<int, array<string, mixed>>}
     */
    private function databaseContext(): array
    {
        $summary = [
            'verified_farmers' => Farmer::query()->where('status', 'verified')->count(),
            'available_products' => Product::query()->where('stock', '>', 0)->whereHas('farmer', fn ($query) => $query->where('status', 'verified'))->count(),
            'markets' => Market::query()->count(),
            'categories' => Product::query()->distinct('category_id')->count('category_id'),
            'announcements' => Announcement::query()->count(),
        ];

        $products = Product::query()
            ->with(['farmer', 'category', 'markets'])
            ->where('stock', '>', 0)
            ->whereHas('farmer', fn ($query) => $query->where('status', 'verified'))
            ->orderByDesc('rating')
            ->limit(100)
            ->get()
            ->map(fn (Product $product): array => [
                'name' => $product->name,
                'category' => $product->category?->name,
                'farmer' => $product->farmer?->name,
                'price' => (float) $product->price,
                'unit' => $product->unit,
                'stock' => $product->stock,
                'markets' => $product->markets->pluck('name')->values()->all(),
            ])
            ->values()
            ->all();

        $farmers = Farmer::query()
            ->with('market')
            ->where('status', 'verified')
            ->orderBy('name')
            ->limit(100)
            ->get()
            ->map(fn (Farmer $farmer): array => [
                'name' => $farmer->name,
                'specialty' => $farmer->specialty,
                'location' => $farmer->location,
                'market' => $farmer->market?->name,
            ])
            ->values()
            ->all();

        $markets = Market::query()
            ->withCount(['farmers' => fn ($query) => $query->where('status', 'verified')])
            ->orderBy('name')
            ->limit(100)
            ->get()
            ->map(fn (Market $market): array => [
                'name' => $market->name,
                'location' => $market->location,
                'days' => $market->days,
                'verified_farmers' => $market->farmers_count,
            ])
            ->values()
            ->all();

        $announcements = Announcement::query()
            ->latest()
            ->limit(20)
            ->get(['title', 'message', 'created_at'])
            ->map(fn (Announcement $announcement): array => [
                'title' => $announcement->title,
                'message' => $announcement->message,
                'date' => $announcement->created_at?->toDateString(),
            ])
            ->values()
            ->all();

        return compact('summary', 'products', 'farmers', 'markets', 'announcements');
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function askGemini(string $message, array $context): string
    {
        $service = config('services.gemini');
        $url = rtrim($service['base_url'], '/').'/'.$service['version'].'/models/'.$service['model'].':generateContent';

        $response = Http::connectTimeout((int) $service['connect_timeout'])
            ->timeout((int) $service['timeout'])
            ->withOptions(['verify' => $service['verify']])
            ->retry([200, 500], 1, fn ($exception) => $exception instanceof ConnectionException || ($exception instanceof RequestException && ($exception->response->serverError() || $exception->response->status() === 429)))
            ->post($url.'?key='.urlencode($service['key']), [
                'systemInstruction' => [
                    'parts' => [['text' => 'You are the MarketLink customer assistant. Answer only from the supplied database context. If the context does not contain the answer, say that you do not have that information and direct the customer to the relevant Marketplace, Farmers, or Markets page. Be concise, friendly, and never invent prices, stock, schedules, farmers, or policies.']],
                ],
                'contents' => [[
                    'role' => 'user',
                    'parts' => [['text' => "Database context:\n".json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n\nCustomer question: {$message}"]],
                ]],
                'generationConfig' => ['temperature' => 0.2, 'maxOutputTokens' => 250],
            ]);

        $response->throw();

        $reply = trim((string) $response->json('candidates.0.content.parts.0.text', ''));

        if ($reply === '') {
            throw new AssistantUnavailableException('The AI assistant returned an empty response. Please try again later.');
        }

        return $reply;
    }
}
