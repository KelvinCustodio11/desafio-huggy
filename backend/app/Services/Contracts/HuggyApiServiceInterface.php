<?php

namespace App\Services\Contracts;

interface HuggyApiServiceInterface
{
    public function me(): array;
    public function listContacts(int $page = 1, int $perPage = 100): array;
    public function createContact(array $payload): array;
    public function updateContact(int $huggyId, array $payload): void;
    public function deleteContact(int $huggyId): void;
}
