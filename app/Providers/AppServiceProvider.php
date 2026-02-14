<?php

namespace App\Providers;

use App\Repositories\Auth\LoginInterface;
use App\Repositories\Auth\LoginRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            LoginInterface::class,
            LoginRepository::class
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
