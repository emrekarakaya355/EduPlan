<?php

namespace App\Providers;

use App\Contracts\AcademicServiceInterface;
use App\Contracts\PdfStrategyInterface;
use App\Services\Adapters\UbysAdapter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AcademicServiceInterface::class, function ($app) {
            return new UbysAdapter();
        });

        //$this->app->bind(UbysServiceInterface::class,UbysServiceAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('livewire.schedule.*', function ($view) {
            $view->with('viewMode', $view->getData()['viewMode'] ?? 'program');
        });

    }
}
