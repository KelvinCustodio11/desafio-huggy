<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\ReportRepository;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Services\Contracts\ClientServiceInterface;
use App\Services\ClientService;
use App\Services\Contracts\HuggyApiServiceInterface;
use App\Services\HuggyApiService;
use App\Services\Contracts\HuggyOAuthServiceInterface;
use App\Services\HuggyOAuthService;
use App\Services\Contracts\ReportServiceInterface;
use App\Services\ReportService;
use App\Services\Contracts\WebhookServiceInterface;
use App\Services\WebhookService;

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
        $this->app->bind(
            WebhookServiceInterface::class,
            WebhookService::class
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
