<?php

namespace App\Services\Contracts;

interface WebhookServiceInterface
{
    public function createContact(array $data, string $accessToken): int;
    public function updateContact(int $huggyId, array $data, string $accessToken): void;
    public function deleteContact(int $huggyId, string $accessToken): void;
}
