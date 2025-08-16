<?php

namespace App\Repositories\Contracts;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;

interface ClientRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Client;
    public function searchByNameOrPhone(string $text): ?Collection;
    public function create(array $data): Client;
    public function update(int $id, array $data): ?Client;
    public function delete(int $id): bool;
}
