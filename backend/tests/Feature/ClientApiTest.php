<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Services\WebhookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use App\Jobs\SendWelcomeEmail;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_client()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'age' => 30,
            'address' => [
                'street' => '123 Main St',
                'city' => 'Springfield',
                'state' => 'IL',
                'neighborhood' => '62704'
            ]
        ];

        $this->authenticateUser();
        $this->mockWebhookService('createContact');

        $response = $this->postJson('/api/clients', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'John Doe']);

        $this->assertDatabaseHas('clients', ['email' => 'john@example.com', 'email' => 'john@example.com', 'phone' => '123-456-7890', 'age' => 30]);
        $this->assertDatabaseHas('addresses', ['street' => '123 Main St', 'city' => 'Springfield', 'state' => 'IL', 'neighborhood' => '62704']);
    }

    public function test_can_list_clients()
    {
        $this->authenticateUser();

        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_update_client()
    {
        $this->authenticateUser();
        $this->mockWebhookService('updateContact');

        $client = Client::factory()->create(['huggy_contact_id' => 2]);

        $data = ['name' => 'Jane Doe'];

        $response = $this->putJson("/api/clients/{$client->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Jane Doe']);

        $this->assertDatabaseHas('clients', ['name' => 'Jane Doe']);
    }

    public function test_can_delete_client()
    {
        $this->authenticateUser();
        $this->mockWebhookService('deleteContact');

        $client = Client::factory()->create(['huggy_contact_id' => 2]);

        $response = $this->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    public function test_can_search_clients_by_name_or_phone()
    {
        $this->authenticateUser();

        Client::factory()->create(['name' => 'John Doe', 'phone' => '123456789']);
        Client::factory()->create(['name' => 'Jane Smith', 'phone' => '987654321']);
        Client::factory()->create(['name' => 'Alice Johnson', 'phone' => '555123456']);

        $response = $this->getJson('/api/clients/search?text=John');

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'John Doe']);

        $response = $this->getJson('/api/clients/search?text=555123456');

        $response->assertStatus(200)
                 ->assertJsonFragment(['phone' => '555123456']);
    }

    public function test_cannot_create_client_with_invalid_data()
    {
        $this->authenticateUser();
        $this->mockWebhookService('createClient', 'never');

        $response = $this->postJson('/api/clients', []);
        $response->assertStatus(422);

        $data = [
            'name' => 'Invalid Email',
            'email' => 'not-an-email',
        ];
        $response = $this->postJson('/api/clients', $data);
        $response->assertStatus(422);
    }

    public function test_cannot_update_nonexistent_client()
    {
        $this->authenticateUser();
        $this->mockWebhookService('createClient','never');

        $response = $this->putJson('/api/clients/99999', ['name' => 'Does Not Exist']);
        $response->assertStatus(400);
    }

    public function test_cannot_delete_nonexistent_client()
    {
        $this->authenticateUser();
        $this->mockWebhookService('createClient','never');

        $response = $this->deleteJson('/api/clients/99999');
        $response->assertStatus(400);
    }

    public function test_search_clients_returns_empty_when_no_match()
    {
        $this->authenticateUser();

        Client::factory()->create(['name' => 'Alice', 'phone' => '111111111']);

        $response = $this->getJson('/api/clients/search?text=NoMatch');
        $response->assertStatus(200)
                 ->assertJsonCount(0);
    }

    public function test_webhook_service_exception_is_handled_on_create()
    {
        $this->authenticateUser();

        $mock = $this->mock(WebhookService::class);
        $mock->shouldReceive('createContact')->once()->andThrow(new \Exception('Webhook error'));
        $this->app->instance(WebhookService::class, $mock);

        $payload = [
            'name' => 'Exception Test',
            'email' => 'exception@test.com',
            'phone' => '888888888',
        ];

        $response = $this->postJson('/api/clients', $payload);

        $response->assertStatus(400);
    }

    public function test_welcome_email_job_is_dispatched()
    {
        Bus::fake();

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'age' => 30,
            'address' => [
                'street' => '123 Main St',
                'city' => 'Springfield',
                'state' => 'IL',
                'neighborhood' => '62704'
            ]
        ];

        $this->authenticateUser();
        $this->mockWebhookService('createContact');

        $this->postJson('/api/clients', $data);
        Bus::assertDispatched(SendWelcomeEmail::class);
    }
}
