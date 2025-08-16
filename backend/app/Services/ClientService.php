<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Services\Contracts\ClientServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ClientService implements ClientServiceInterface
{
    protected $repository;

    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
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

        return $this->repository->create($data);
    }

    public function updateClient(int $id, array $data): ?Client
    {
        return $this->repository->update($id, $data);
    }

    public function deleteClient(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
