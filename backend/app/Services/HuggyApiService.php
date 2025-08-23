<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\Contracts\HuggyApiServiceInterface;

class HuggyApiService implements HuggyApiServiceInterface
{
    protected string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    protected function client()
    {
        return Http::withToken($this->accessToken)
            ->baseUrl(config('services.huggy.api_base'))
            ->acceptJson();
    }

    public function me(): array
    {
        return $this->client()->get('/agents/profile')->throw()->json();
    }

    public function listContacts(int $page = 1, int $perPage = 100): array
    {
        return $this->client()->get('/contacts')->throw()->json();
    }

    public function createContact(array $payload): array
    {
        return $this->client()->post('/contacts', $payload)->throw()->json();
    }

    public function updateContact(int $id, array $payload): void
    {
        $this->client()->put("/contacts/{$id}", $payload)->throw()->json();
    }

    public function deleteContact(int $id): void
    {
        $this->client()->delete("/contacts/{$id}")->throw()->json();
    }
}
