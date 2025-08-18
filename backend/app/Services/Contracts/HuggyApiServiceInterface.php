<?php

namespace App\Services\Contracts;

interface HuggyApiServiceInterface
{
    public function me(): array;
    public function listContacts(int $page = 1, int $perPage = 100): array;
}
