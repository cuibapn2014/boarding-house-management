<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Payment Repository
        $this->app->singleton(
            \App\Repositories\Contracts\PaymentRepositoryInterface::class,
            \App\Repositories\PaymentRepository::class
        );

        // Register Payment Service
        $this->app->singleton(
            \App\Services\Contracts\PaymentServiceInterface::class,
            \App\Services\PaymentService::class
        );

        // Register Strategy Factory
        $this->app->singleton(\App\Strategies\PaymentStrategyFactory::class);

        // Register Webhook Validation Service
        $this->app->singleton(\App\Services\WebhookValidationService::class);

        // Register Point Service
        $this->app->singleton(
            \App\Services\Contracts\PointServiceInterface::class,
            \App\Services\PointService::class
        );

        // Register Service Payment Service
        $this->app->singleton(
            \App\Services\Contracts\ServicePaymentServiceInterface::class,
            \App\Services\ServicePaymentService::class
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
