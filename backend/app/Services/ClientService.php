<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Services\Contracts\ClientServiceInterface;
use App\Services\Contracts\WebhookServiceInterface;
use Illuminate\Support\Collection;

class ClientService implements ClientServiceInterface
{
    protected $repository;
    protected $webhookService;

    public function __construct(ClientRepositoryInterface $repository, WebhookServiceInterface $webhookService)
    {
        $this->repository = $repository;
        $this->webhookService = $webhookService;
    }

    public function getAllClients(): Collection
    {
        return $this->repository->all();
    }

    public function getClient(int $id): ?Client
    {
        return $this->repository->find($id);
    }

    public function searchByNameOrPhone(string $text): ?Collection
    {
        return $this->repository->searchByNameOrPhone($text);
    }

    public function createClient(array $data): Client
    {
        if (!isset($data['email'])) {
            throw new \InvalidArgumentException('O email é obrigatório.');
        }

        $client = $this->repository->create($data);

        if (!$client) {
            throw new \RuntimeException('Erro ao criar cliente.');
        }

        $this->webhookService->sendContact($client->toArray());

        return $client;
    }

    public function updateClient(int $id, array $data): ?Client
    {
        $client = $this->repository->update($id, $data);

        if (!$client) {
            throw new \InvalidArgumentException('Cliente não encontrado.');
        }

        $this->webhookService->sendContact($client->toArray());

        return $client;
    }

    public function deleteClient(int $id): bool
    {
        $client = $this->repository->find($id);
        if (!$client) {
            throw new \InvalidArgumentException('Cliente não encontrado.');
        }

        $result = $this->repository->delete($id);
        if (!$result) {
            throw new \RuntimeException('Erro ao deletar cliente.');
        }

        $this->webhookService->sendContact($client->toArray());

        return $result;
    }
}
