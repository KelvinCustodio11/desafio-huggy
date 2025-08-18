<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\Contracts\HuggyApiServiceInterface;

class HuggyApiService implements HuggyApiServiceInterface
{
    public function __construct(protected string $accessToken) {}

    protected function client()
    {
        return Http::withToken($this->accessToken)
            ->baseUrl(config('services.huggy.api_base'))
            ->acceptJson();
    }

    public function me(): array
    {
        // Ex.: perfil do agente (útil p/ capturar company/user id)
        return $this->client()->get('/agents/profile')->throw()->json();
    }

    public function listContacts(int $page = 1, int $perPage = 100): array
    {
        // A doc nao possui seção de paginação! TODO: Analisar como poderia otimizar.
        return $this->client()->get('/contacts', [
            // 'page' => $page,
            // 'perPage' => $perPage,
        ])->throw()->json();
    }
}
