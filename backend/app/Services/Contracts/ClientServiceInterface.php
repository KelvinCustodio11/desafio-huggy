<?php

namespace App\Services\Contracts;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

interface ClientServiceInterface
{
    public function getAllClients(): Collection;
    public function getClient(int $id): ?Client;
    public function searchByNameOrPhone(string $text): ?Collection;
    public function createClient(array $data): Client;
    public function updateClient(int $id, array $data): ?Client;
    public function deleteClient(int $id): bool;
}
