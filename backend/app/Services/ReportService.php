<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Services\Contracts\ReportServiceInterface;

class ReportService implements ReportServiceInterface
{
    protected $repository;

    public function __construct(ReportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getClientsReport(): array
    {
        return [
            'clients_by_city' => $this->repository->reportClientsByCity(),
            'clients_by_state' => $this->repository->reportClientsByState(),
            'clients_by_age' => $this->repository->reportClientsByAge(),
        ];
    }
}
