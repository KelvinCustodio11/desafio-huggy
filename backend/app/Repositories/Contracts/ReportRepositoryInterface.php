<?php

namespace App\Repositories\Contracts;

interface ReportRepositoryInterface
{
    public function reportClientsByCity(): array;
    public function reportClientsByState(): array;
    public function reportClientsByAge(): array;
}
