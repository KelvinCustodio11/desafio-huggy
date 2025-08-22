<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    }

    public function test_can_create_client()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->authenticateUser();

        $response = $this->postJson('/api/clients', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'John Doe']);

        $this->assertDatabaseHas('clients', ['email' => 'john@example.com']);
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

        $client = Client::factory()->create();

        $data = ['name' => 'Jane Doe'];

        $response = $this->putJson("/api/clients/{$client->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Jane Doe']);

        $this->assertDatabaseHas('clients', ['name' => 'Jane Doe']);
    }

    public function test_can_delete_client()
    {
        $this->authenticateUser();

        $client = Client::factory()->create();

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
}
