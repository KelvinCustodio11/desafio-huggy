<?php

namespace App\Services;

use App\Services\Contracts\WebhookServiceInterface;
use App\Services\HuggyApiService;

class WebhookService implements WebhookServiceInterface
{
    protected function getApi(string $accessToken): HuggyApiService
    {
        return new HuggyApiService($accessToken);
    }

    public function createContact(array $data, string $accessToken): int
    {
        $payload = $this->formatPayload($data);
        $response = $this->getApi($accessToken)->createContact($payload);
        return $response['id'];
    }

    public function updateContact(int $huggyId, array $data, string $accessToken): void
    {
        $payload = $this->formatPayload($data);
        $this->getApi($accessToken)->updateContact($huggyId, $payload);
    }

    public function deleteContact(int $huggyId, string $accessToken): void
    {
        $this->getApi($accessToken)->deleteContact($huggyId);
    }

    protected function formatPayload(array $data): array
    {
        return [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? '',
            'mobile' => $data['cell'] ?? '',
            'custom_fields' => $data['custom_fields'] ?? [],
            'groups' => $data['groups'] ?? [],
            'organizations' => $data['organizations'] ?? [],
            'company' => [
                'id' => $data['company_id'] ?? 1
            ]
        ];
    }
}
