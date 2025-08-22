<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;
use App\Models\User;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_endpoint_returns_clients_report_data()
    {
        $client1 = Client::factory()->create(['age' => 30]);
        $client1->address()->create(['city' => 'Feira', 'state' => 'BA']);

        $client2 = Client::factory()->create(['age' => 30]);
        $client2->address()->create(['city' => 'Feira', 'state' => 'BA']);

        $client3 = Client::factory()->create(['age' => 25]);
        $client3->address()->create(['city' => 'Salvador', 'state' => 'BA']);

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/reports?type=clients');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'clients_by_city',
                'clients_by_state',
                'clients_by_age',
            ])
            ->assertJsonFragment(['city' => 'Feira', 'total' => 2])
            ->assertJsonFragment(['state' => 'BA', 'total' => 3])
            ->assertJsonFragment(['age' => 30, 'total' => 2]);
    }
}
