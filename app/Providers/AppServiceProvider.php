<?php

namespace App\Providers;

use App\Services\CustomerRecord\CustomerRecordInterface;
use App\Services\CustomerRecord\CustomerRecordService;
use App\Services\RandomUserImporter\CustomerImporterInterface;
use App\Services\RandomUserImporter\RandomUserImporterService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerRecordInterface::class, CustomerRecordService::class);
        $this->app->bind(CustomerImporterInterface::class, RandomUserImporterService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
