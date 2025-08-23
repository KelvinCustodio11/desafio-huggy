<?php

namespace Tests;

use App\Models\User;
use App\Services\WebhookService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function authenticateUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    }

    protected function mockWebhookService($expected = 'once')
    {
        $mock = $this->mock(WebhookService::class);
        $mock->shouldReceive('sendContact')->{$expected}();
        $this->app->instance(WebhookService::class, $mock);
    }
}
