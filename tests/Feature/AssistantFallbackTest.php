<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AssistantFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_assistant_returns_a_friendly_local_fallback_when_gemini_is_unavailable(): void
    {
        config()->set('services.gemini.key', '');

        $response = $this->postJson('/assistant/chat', [
            'message' => 'What produce is available near me?',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'reply',
            'code',
        ]);
        $this->assertStringContainsString('MarketLink', $response->json('reply'));
        $this->assertSame('assistant_offline', $response->json('code'));
    }
}
