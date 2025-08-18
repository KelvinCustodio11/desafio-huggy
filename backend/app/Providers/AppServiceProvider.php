<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Services\Contracts\ClientServiceInterface;
use App\Services\ClientService;
use App\Services\Contracts\HuggyApiServiceInterface;
use App\Services\Contracts\HuggyOAuthServiceInterface;
use App\Services\HuggyApiService;
use App\Services\HuggyOAuthService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ClientServiceInterface::class,
            ClientService::class
        );
        $this->app->bind(
            ClientRepositoryInterface::class,
            ClientRepository::class
        );
        $this->app->bind(
            HuggyOAuthServiceInterface::class,
            HuggyOAuthService::class
        );
        $this->app->bind(
            HuggyApiServiceInterface::class,
            HuggyApiService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
