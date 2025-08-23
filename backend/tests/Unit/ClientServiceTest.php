<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Services\ClientService;
use App\Services\Contracts\WebhookServiceInterface;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\TestCase;

class ClientServiceTest extends TestCase
{
    protected $webhookServiceMock;
    protected $repositoryMock;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = $this->createMock(ClientRepositoryInterface::class);
        $this->webhookServiceMock = $this->createMock(WebhookServiceInterface::class);
        $this->service = new ClientService($this->repositoryMock, $this->webhookServiceMock);
    }

    public function test_can_create_client()
    {
        $user = new \stdClass();
        $user->huggy_access_token = 'fake-token';
        Auth::shouldReceive('user')->andReturn($user);

        $data = ['name' => 'John Doe', 'email' => 'john@example.com', 'huggy_contact_id' => 1];

        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save', 'toArray'])
            ->getMock();

        $clientMock->expects($this->once())->method('save')->willReturn(true);
        $clientMock->expects($this->once())->method('toArray')->willReturn($data);

        $this->repositoryMock
            ->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($clientMock);

        $this->webhookServiceMock
            ->expects($this->once())
            ->method('createContact')
            ->with($data, 'fake-token')
            ->willReturn(123);

        $clientMock->huggy_contact_id = null; // simula antes do webhook

        $client = $this->service->createClient($data);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_can_get_all_clients()
    {
        $clients = collect([new Client(['name' => 'Client 1'])]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('all')
            ->willReturn($clients);

        $result = $this->service->getAllClients();

        $this->assertCount(1, $result);
        $this->assertEquals('Client 1', $result->first()->name);
    }

    public function test_can_update_client()
    {
        $client = new Client(['id' => 1, 'name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        $this->repositoryMock
            ->expects($this->once())
            ->method('update')
            ->with(1, $data)
            ->willReturn(new Client(array_merge($client->toArray(), $data)));

        $updatedClient = $this->service->updateClient(1, $data);

        $this->assertEquals('New Name', $updatedClient->name);
    }

    public function test_can_delete_client()
    {
        $client = new Client(['id' => 1]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($client);

        $this->repositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $result = $this->service->deleteClient(1);

        $this->assertTrue($result);
    }

    public function test_can_search_clients_by_name()
    {
        $searchText = 'John';
        $clients = collect([new Client(['name' => 'John Doe'])]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('searchByNameOrPhone')
            ->with($searchText)
            ->willReturn($clients);

        $result = $this->service->searchByNameOrPhone($searchText);

        $this->assertCount(1, $result);
        $this->assertEquals('John Doe', $result->first()->name);
    }

    public function test_can_search_clients_by_phone()
    {
        $searchText = '123-456-7890';
        $clients = collect([new Client(attributes: ['phone' => '123-456-7890'])]);

        $this->repositoryMock
            ->expects($this->once())
            ->method('searchByNameOrPhone')
            ->with($searchText)
            ->willReturn($clients);

        $result = $this->service->searchByNameOrPhone($searchText);

        $this->assertCount(1, $result);
        $this->assertEquals('123-456-7890', $result->first()->phone);
    }
}
