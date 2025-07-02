<?php

namespace App\Providers;

use App\Services\CustomerRecord\CustomerImporterInterface;
use App\Services\CustomerRecord\RandomUserImporterService;
use App\Services\RandomUserImporter\CustomerRecordInterface;
use App\Services\RandomUserImporter\CustomerRecordService;
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
