<?php

namespace App\Http\Controllers;

use App\Exceptions\AssistantUnavailableException;
use App\Http\Requests\ChatMessageRequest;
use App\Services\MarketAssistant;
use Illuminate\Http\JsonResponse;

class AssistantController extends Controller
{
    public function __invoke(ChatMessageRequest $request, MarketAssistant $assistant): JsonResponse
    {
        try {
            $reply = $assistant->answer($request->validated('message'));

            return response()->json([
                'reply' => $reply,
                'code' => 'assistant_online',
                'offline' => false,
            ]);
        } catch (AssistantUnavailableException $exception) {
            report($exception);

            return response()->json([
                'reply' => 'MarketLink AI is temporarily unavailable. Browse the market and product listings for the freshest local picks near you.',
                'code' => 'assistant_offline',
                'offline' => true,
            ]);
        }
    }
}
