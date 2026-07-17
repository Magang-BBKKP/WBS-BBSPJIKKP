<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\AuditLogRepositoryInterface::class,
            \App\Repositories\AuditLogRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\LaporanRepositoryInterface::class,
            \App\Repositories\LaporanRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\InvestigationRepositoryInterface::class,
            \App\Repositories\InvestigationRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
