<?php

namespace Tests;

use App\Models\User;
use App\Services\WebhookService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function authenticateUser()
    {
        $user = User::factory()->create([
            'huggy_access_token' => 'fake-token-123'
        ]);
        $this->actingAs($user, 'sanctum');
    }

    protected function mockWebhookService($method, $expected = 'once')
    {
        $mock = $this->mock(WebhookService::class);
        $mock->shouldReceive($method)->{$expected}();
        $this->app->instance(WebhookService::class, $mock);
    }
}
