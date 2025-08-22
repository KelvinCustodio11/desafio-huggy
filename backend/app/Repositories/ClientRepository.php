<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Support\Collection;

class ClientRepository implements ClientRepositoryInterface
{
    public function all(): Collection
    {
        return Client::with('address')->get();
    }

    public function find(int $id): ?Client
    {
        return Client::with('address')->find($id);
    }

    public function searchByNameOrPhone(string $text): ?Collection
    {
        return Client::where('name', 'like', "%{$text}%")
            ->orWhere('phone', 'like', "%{$text}%")
            ->with('address')
            ->get();
    }

    public function create(array $data): Client
    {
        $client = Client::create($data);
        if (isset($data['address'])) {
            $client->address()->create($data['address']);
        }
        return $client;
    }

    public function update(int $id, array $data): ?Client
    {
        $client = Client::find($id);
        if (!$client) {
            return null;
        }
        $client->update($data);
        $client->address()->updateOrCreate(
            ['client_id' => $id],
            $data['address'] ?? []
        );
        return $client;
    }

    public function delete(int $id): bool
    {
        $client = Client::find($id);
        if (!$client) {
            return false;
        }
        return $client->delete();
    }
}
