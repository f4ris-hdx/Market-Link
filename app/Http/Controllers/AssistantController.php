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
            return response()->json(['reply' => $assistant->answer($request->validated('message'))]);
        } catch (AssistantUnavailableException $exception) {
            report($exception);

            return response()->json([
                'message' => $exception->getMessage(),
                'code' => 'assistant_offline',
            ], 503);
        }
    }
}
