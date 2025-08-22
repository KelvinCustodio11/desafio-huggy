<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\Contracts\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    public function reportClientsByCity(): array
    {
        return Client::join('addresses', 'clients.id', '=', 'addresses.client_id')
            ->selectRaw('addresses.city, COUNT(*) as total')
            ->groupBy('addresses.city')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    public function reportClientsByState(): array
    {
        return Client::join('addresses', 'clients.id', '=', 'addresses.client_id')
            ->selectRaw('addresses.state, COUNT(*) as total')
            ->groupBy('addresses.state')
            ->orderByDesc('total')
            ->get()
            ->toArray();
   }

    public function reportClientsByAge(): array
    {
        return Client::selectRaw('age, COUNT(*) as total')
            ->groupBy('age')
            ->orderBy('age')
            ->get()
            ->toArray();
    }
}
