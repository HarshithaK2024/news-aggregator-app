<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MongoDBService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MongoDBService::class, function ($app) {
            return new MongoDBService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
