<?php

namespace App\Providers;

use App\Repositories\ReportRepository;
use App\Services\Contracts\ReportServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Services\Contracts\ClientServiceInterface;
use App\Services\ClientService;
use App\Services\Contracts\HuggyApiServiceInterface;
use App\Services\Contracts\HuggyOAuthServiceInterface;
use App\Services\HuggyApiService;
use App\Services\HuggyOAuthService;
use App\Services\ReportService;

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
        $this->app->bind(
            ReportRepositoryInterface::class,
            ReportRepository::class
        );
        $this->app->bind(
            ReportServiceInterface::class,
            ReportService::class
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
